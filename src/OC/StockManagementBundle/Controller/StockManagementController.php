<?php

namespace OC\StockManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;


class StockManagementController extends Controller
{
    public function viewCategoryAction() {
        $category = $this->container->get('oc_platform.get.catalog')->getCategory();

        var_dump($category);
    }

    public function manageCategoriesAction() {
        $repository = $this
            ->getDoctrine()
            ->getManager()
            ->getRepository('OCStockManagementBundle:Category')
        ;

        $listCategories = $repository->getFirstLevelCategories();

        
        // $childCategories = $repository->getChildCategories();


        foreach ($listCategories as $category) {
            // $childCategories = $repository->getChildCategories($category->getId());
            $childCategories[$category->getId()] = $repository->getChildCategories($category->getId());


            //$childCategories[$category->getId()] = $repository->getChildCategories($category->getId($category->getId()));

            //TODO : faire en sorte d'avoir plusieurs variables $childCategories créées, sous la forme "childCategories[parentCategoryId]"
            // $childCategories = array();
            // array_push($childCategories, $repository->getChildCategories($category->getId()));
        }

        $listAllCategories = $repository->findAll();

		return $this->render('OCStockManagementBundle:Category:manageCategories.html.twig', array(
            'listCategories' => $listCategories,
            'childCategories' => $childCategories,
            // 'listAllCategories' => $listAllCategories
        ));

    }

}