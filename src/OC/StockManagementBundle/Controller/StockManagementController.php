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

    public function manageCategoryAction() {
        $em = $this->getDoctrine()->getManager();


        //getCategory n'existe pas
        $category = $em->getRepository('OCPlatformBundle:Category')->getcategory();

    }
}