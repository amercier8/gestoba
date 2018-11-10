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

        foreach($categoriesFiltered as $category) {
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
        //Ci-dessous : Le bouton sera placé dans la page de gestion DES utilisateurs, il faudra faire un redirect vers cette page
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
        // for ($i =  0; $i<count($productsFiltered); $i++) {
        //     $category = $repository->findBy(array(
        //         'wizaplaceId' => $productsFiltered[$i]['wizaplaceCategoryId'],
        //     ));
        //     if (empty($category)) {
        //         $lowStockProducts[] = [];
        //     } else if($productsFiltered[$i]['stock'] < $category[0]->getLowStock()) {
        //         $lowStockProducts[] = $productsFiltered[$i];
        //     }
        // }
        $this->container->get('oc_platform.compare.productsVsCategoriesStock')->compareProductsVsCategoriesStock($productsFiltered);


        //Récupération du CSV
        $this->container->get('oc_platform.get.lowStockProductsCSV')->getLowStockProductsCSV($lowStockProducts);
    }
}