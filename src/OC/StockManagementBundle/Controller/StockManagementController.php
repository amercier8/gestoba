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


class StockManagementController extends Controller
{
    public function getCategoriesAction() {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        // $hifi = array('name'=>'hifi', 'wizaplaceId'=>19, 'parentId'=>'null');
        // $bricolage = array('name'=>'Bricolage', 'wizaplaceId'=>15, 'parentId'=>'null');
        // $tournevis = array('name'=>'tournevis', 'wizaplaceId'=>23, 'parentId'=>15);
        // $enceinteNomade = array('name'=>'enceinte nomade', 'wizaplaceId'=>89, 'parentId'=>19);
        // $cableEnceinte = array('name'=>'cable enceinte', 'wizaplaceId'=>678, 'parentId'=>19);
        // $cableEnceinteCuivre = array('name'=>'cable enceinte cuivre', 'wizaplaceId'=>7878, 'parentId'=>678);

        //TESTS
        $categories = $this->container->get('oc_platform.get.catalog')->getCategories();
        var_dump($categories);

        foreach ($categories as $category) {
            $categoriesFiltered[] = array(
                'name' => $category['name'],
                'wizaplaceId' => $category['id'],
                'parentId' => $category['parentId']
            );

        }

        //FIN DES TESTS

        // $categories = array($bricolage, $tournevis, $enceinteNomade, $cableEnceinte, $hifi, $cableEnceinteCuivre);


        foreach($categoriesFiltered as $category) {

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

        $em->flush();

        $listCategories = $repository->findAll();

        return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
            'listCategories' => $listCategories,
        ));
    }
    

    public function manageCategoriesAction(Request $request) {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        $categories =  $repository->findAll();

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

    public function getProductsAction() {

        //On récupère tous les produits, on en fait un array propre
        $products = $this->container->get('oc_platform.get.products')->getProducts();

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
        // var_dump($productsFiltered);
        //Fin de la récup de produits

        //Récupération des catégories
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;
        $categories =  $repository->findAll();
        foreach($categories as $category) {
            // $name = $this->getName();
            // var_dump($name);
        }
        //Fin récup des catégories

    }

    public function CompareProductsCategoriesStockAction($productsFiltered) {

    }



        // foreach ($categories as $category) {
        //     $categoriesFiltered[] = array(
        //         'name' => $category['name'],
        //         'wizaplaceId' => $category['id'],
        //         'parentId' => $category['parentId']
        //     );

        // }
}