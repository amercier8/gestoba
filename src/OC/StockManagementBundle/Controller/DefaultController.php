<?php

namespace OC\StockManagementBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction()
    {
        return $this->render('OCStockManagementBundle:Default:index.html.twig');
    }
}
