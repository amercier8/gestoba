<?php

namespace OC\StockManagementBundle\ProductsVsCategoriesStockComparator;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCProductsVsCategoriesStockComparator
{
    public function compareProductsVsCategoriesStock($products, $repository) {

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

        $categories =  $repository->findAll();
        foreach($categories as $category) {
            $name = $category->getName();
        }

        // var_dump($productsFiltered);
        
        for ($i =  0; $i<count($productsFiltered); $i++) {
            $category = $repository->findBy(array(
                'wizaplaceId' => $productsFiltered[$i]['wizaplaceCategoryId'],
            ));
            if (empty($category)) {
                $lowStockProducts[] = [];
                // break;
            } else if($productsFiltered[$i]['stock'] < $category[0]->getLowStock()) {
                $lowStockProducts[] = $productsFiltered[$i];
            }
        }
    return $lowStockProducts;
    }

}