<?php

namespace OC\StockManagementBundle\LowStockProductsCSVGetter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCProductsVsCategoriesStockComparator
{
    public function compareProductsVsCategoriesStock($productsFiltered) {
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
    }
}