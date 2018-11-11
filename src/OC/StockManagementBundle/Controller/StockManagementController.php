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
    public function homeRedirectAction() {
        $userRole = $this->getUser()->getRoles();
        if (in_array("ROLE_ADMIN", $userRole)) {
            return $this->redirectToRoute('oc_users_management');
        } elseif (in_array("ROLE_VENDOR", $userRole)){
            return $this->redirectToRoute('oc_user_management');
        } else {
            return $this->redirectToRoute('oc_user_management_noRoleMessage');
        }
    }


    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function getCategoriesAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        $userMail = $this->getUser()->getEmail();
        $userApiKey = $this->getUser()->getApiKey();
        $userCredentials = base64_encode($userMail.':'.$userApiKey);
        //Getting the categories from the external API
        $categories = $this->container->get('oc_platform.get.catalog')->getCategories($userCredentials);

        //Getting what is necessary from the return from the API
        //For each category, update or edit the doctrine objects
        $this->container->get('oc_platform.update.categories')->updateCategories($categories, $repository, $em);

        $listCategories = $repository->findAll();

        $request
        ->getSession()
        ->getFlashBag()
        ->add('notice', 'Catégories mises à jour');

        //Return n'a pas lieu d'être
        return $this->redirectToRoute('oc_users_management');
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

        $userMail = $this->getUser()->getEmail();
        $userPassword = $this->getUser()->getPassword();
        $userCredentials = $userMail.$userPassword;

        $products = $this->container->get('oc_platform.get.products')->getProducts();

        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category');

        $lowStockProducts = $this->container->get('oc_platform.compare.productsVsCategoriesStock')->compareProductsVsCategoriesStock($products, $repository);

        //Récupération du CSV
        $this->container->get('oc_platform.get.lowStockProductsCSV')->getLowStockProductsCSV($lowStockProducts);
    }
}