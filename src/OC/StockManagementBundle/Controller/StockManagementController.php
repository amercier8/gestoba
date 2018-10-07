<?php

namespace OC\StockManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use GuzzleHttp\Client;


class StockManagementController extends Controller
{
    public function viewCategoriesAction() {
        $categories = $this->container->get('oc_platform.get.catalog')->getCategories();

        var_dump($categories);
    }
}