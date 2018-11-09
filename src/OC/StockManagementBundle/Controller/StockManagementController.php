<?php

namespace OC\StockManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use Symfony\Component\Routing\Annotation\Route;
use GuzzleHttp\Client;
use OC\StockManagementBundle\Entity\Category;
use OC\StockManagementBundle\Form\CategoryType;
use OC\StockManagementBundle\Form\CategoriesType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;


class StockManagementController extends Controller
{
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function getCategoriesAction() {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        //TESTS
        $userMail = $this->getUser()->getEmail();
        $userApiKey = $this->getUser()->getApiKey();
        $userCredentials = base64_encode($userMail.':'.$userApiKey);

        $categories = $this->container->get('oc_platform.get.catalog')->getCategories($userCredentials);

        foreach ($categories as $category) {
            $categoriesFiltered[] = array(
                'name' => $category['category'],
                'wizaplaceId' => $category['category_id'],
                'parentId' => $category['parent_id'],
                'status' => $category['status'],
            );

        }

        //FIN DES TESTS

        // $categories = array($bricolage, $tournevis, $enceinteNomade, $cableEnceinte, $hifi, $cableEnceinteCuivre);
        foreach($categoriesFiltered as $category) {
            var_dump($category);
            if($category['status'] === "A") {
                if ($repository->findOneBy(array('wizaplaceId' => $category['wizaplaceId']))) {
                    $categoryToEdit = $repository->findOneBy(
                        array('wizaplaceId' => $category['wizaplaceId'])
                    );
                } else {
                    $categoryToEdit = new Category();
                }

                $parent = $repository->findOneBy(
                    array('wizaplaceId' => $category['parentId'])
                );

                $categoryToEdit
                    ->setName($category['name'])
                    ->setParent($parent)
                    ->setWizaplaceId($category['wizaplaceId'])
                ;

                $em->persist($categoryToEdit);
            }
        }

        $em->flush();

        $listCategories = $repository->findAll();

        return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
            'listCategories' => $listCategories,
        ));
    }
    
    /**
    * @Security("has_role('ROLE_VENDOR')")
    */
    public function manageCategoriesAction(Request $request) {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        $categories =  $repository
            ->findAll()
        ;

        $form = $this
            ->get('form.factory')
            ->create(CollectionType::class, $categories, array(
                'entry_type' => CategoryType::class,
                'attr' => array('class' => 'collection'),
            ))
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($categories as $category) {
                $em->persist($category);
                $em->flush();
            }
        $request
            ->getSession()
            ->getFlashBag()
            ->add('notice', 'Modifications bien enregistrées');
        }
        
        return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
            'formCategories' => $form->createView(),
            'listCategories' => $repository->findAll()
        ));

    }

    public function resetLowStockAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        $categories =  $repository->findAll();

        foreach ($categories as $category) {
            $category->setLowStock(0);
            $em->persist($category);
        }

        $em->flush();

        $this->addFlash(
            'notice',
            'Niveaux de stock bas réinitialisés'
        );

        return $this->redirectToRoute('oc_stock_management_manageCategories');
        }

    //Récupération des produits en Stock bas
    public function getProductsAction() {

        //On récupère tous les produits, on en fait un array propre
        //1 service
        $userMail = $this->getUser()->getEmail();
        $userPassword = $this->getUser()->getPassword();
        $userCredentials = $userMail.$userPassword;
        // var_dump($userCredentials);
        
        // $products = $this->container->get('oc_platform.get.products')->getProducts();
        //Version modifiée
        $products = $this->container->get('oc_platform.get.products')->getProducts();
        //Fin version modifiée

        foreach($products as $productContainer) {
            foreach($productContainer as $productContent) {
                $productsFiltered[] = array (
                    'wizaplaceId' => $productContent['id'],
                    'wizaplaceName' => $productContent['name'],
                    'stock' => $productContent['declinations'][0]['amount'],
                    'wizaplaceCategoryName' => $productContent['categoryPath'][0]['name'],
                    'wizaplaceCategoryId' => $productContent['categoryPath'][0]['id'],
                );
            }
        }
        //Fin de la récup de produits

        //Récupération des catégories
        //2e service
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category');

        $categories =  $repository->findAll();
        foreach($categories as $category) {
            $name = $category->getName();
        }
        //Fin récup des catégories

        //Comparaison
        //Méthode dans le 2e service

        for ($i =  0; $i<count($productsFiltered); $i++) {
            $category = $repository->findBy(array(
                'wizaplaceId' => $productsFiltered[$i]['wizaplaceCategoryId'],
            ));
            if (empty($category)) {
                $lowStockProducts[] = [];
            } else if($productsFiltered[$i]['stock'] < $category[0]->getLowStock()) {
                $lowStockProducts[] = $productsFiltered[$i];
            }
        }


        //CSV OK --> Sera à appeler via un bouton "Télécharger les produits en Stock Bas"
        $csvFile = fopen('php://memory', 'w');

        $csvHeader = ['wizaplaceId', 'wizaplaceName', 'stock', 'wizaplaceCategoryName', 'wizaplaceCategoryId'];
        fputcsv($csvFile, $csvHeader, $delimiter = ";", $enclosure = '"');

        foreach($lowStockProducts as $lowStockProduct) {
            fputcsv($csvFile, $lowStockProduct, $delimiter = ";", $enclosure = '"'); 
        }
        fseek($csvFile, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; Filename="export_catalog.csv";');
        fpassthru($csvFile);
        exit;
    }
}