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
use GuzzleHttp\Client;
use OC\StockManagementBundle\Entity\Category;
use OC\StockManagementBundle\Form\CategoryType;


class StockManagementController extends Controller
{
    public function getCategoriesAction() {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        //////POUR TEST
        // $hifi = array('name'=>'hifi', 'wizaplaceId'=>19, 'parentId'=>'null');
        // $enceintes = array('name'=>'enceintes', 'wizaplaceId'=>20, 'parentId'=>19);   
        // $amplis = array('name'=>'amplis', 'wizaplaceId'=>21, 'parentId'=>19);   

        // $categoriesTest = array($hifi, $enceintes, $amplis);

        // foreach($categoriesTest as $categoryTest) {
        //     $category3 = new Category();

        //     $category3
        //         ->setName($categoryTest['name'])
        //         ->setWizaplaceId($categoryTest['wizaplaceId'])
        //     ;

        //     $em->persist($category3);
        // }

        // $em->flush();
        ////////FIN POUR TEST

        $hifi = array('name'=>'hifi', 'wizaplaceId'=>19, 'parentId'=>'null');
        $bricolage = array('name'=>'Bricolage', 'wizaplaceId'=>15, 'parentId'=>'null');
        $tournevis = array('name'=>'tournevis', 'wizaplaceId'=>23, 'parentId'=>15);
        $enceinteNomade = array('name'=>'enceinte nomade', 'wizaplaceId'=>89, 'parentId'=>19);
        $cableEnceinte = array('name'=>'cable enceinte', 'wizaplaceId'=>678, 'parentId'=>19);
        $cableEnceinteCuivre = array('name'=>'cable enceinte cuivre', 'wizaplaceId'=>7878, 'parentId'=>678);

        $categories = array($bricolage, $tournevis, $enceinteNomade, $cableEnceinte, $hifi, $cableEnceinteCuivre);

        foreach($categories as $category) {

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

        $category = new Category();
        $form = $this->get('form.factory')->create(CategoryType::class, $category);
        
        return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
            'form' => $form->createView(),
            'listCategories' => $repository->findAll()
        ));

        // $listCategories = $repository->getFirstLevelCategories();

        // return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
        //     'listCategories' => $listCategories,
        //     // 'childCategories' => $childCategories,
        //     // 'form' => $form->createView(),
        // ));

    }

    //Useless
    public function changeLowStock() {
        $category = new Category();

        $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $category);

        $formBuilder
            ->add('name', TextType::class)
            ->add('lowStock', IntegerType::class)
            ->add('save', SubmitType::class)
        ;

        $form = $formBuilder->getForm();

        return $this->render('OCStockManagementBunle:Category:manageCategories.html.twig', array(
            'form' => $form->createView(),
        ));
    }
}