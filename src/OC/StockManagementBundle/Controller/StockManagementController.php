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


        //Ci-dessous : plus tard, d'abord test avec petit array
        //$categories = $this->container->get('oc_platform.get.catalog')->getCategories();
        //

        //renseigner les objets de test ici
        $bricolage = array('name'=>'Bricolage', 'wizaplaceId'=>15, 'parentId'=>'null');
        $tournevis = array('name'=>'tournevis', 'wizaplaceId'=>23, 'parentId'=>15);
        $enceinteNomade = array('name'=>'enceinte nomade', 'wizaplaceId'=>89, 'parentId'=>1);

        $categories = array($bricolage, $tournevis, $enceinteNomade);

        var_dump($bricolage);
        var_dump($tournevis);
        var_dump($categories);

        foreach($categories as $category) {
            $category2 = new Category();

            $parent = $repository->findOneBy(
                array('parentId' => $category['parentId'])
            )
            ;

            var_dump($parent);

            // $category2
            //     ->setName($category['name'])
            //     ->setParent($parent)
            //     ->setWizaplaceId($category['id'])
            // ;

            // var_dump($category2);
        }
    }

        ///////
        // foreach($categories as $category) {

        //     $category2 = new Category();

        //     $category2
        //         ->setName($category['name'])
        //         // ->setParentId($category['parentId'])
        //         ->setPosition($category['position'])
        //         ->setId($category['id'])
        //     ;

        //     $em->persist($category2);
        // }

        // $em->flush();

        ///////

        // var_dump($categories[0]);
    

    public function manageCategoriesAction() {
        //Categories
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        //$categories = $repository->getCategories();

        $listCategories = $repository->getFirstLevelCategories();
        // $listCategories = $repository->findAll();
////
        

        // foreach ($listCategories as $category) {
        //     $childCategories['parentId'][$category->getId()] = $repository->getChildCategories($category->getId());
        // }
        //End Categories

        //Form
        //$category = new Category();

        // $formBuilder = $this->get('form.factory')->createBuilder(FormType::class, $category);

        // $formBuilder
        //     ->add('name', TextType::class)
        //     ->add('lowStock', IntegerType::class)
        //     ->add('save', SubmitType::class)
        // ;

        // $form = $formBuilder->getForm();
        //End Form

            return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
                'listCategories' => $listCategories,
                // 'childCategories' => $childCategories,
                // 'form' => $form->createView(),
            ));

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