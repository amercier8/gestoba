<?php

namespace OC\StockManagementBundle\LowStockProductsCSVGetter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCLowStockProductsCSVGetter
{
    public function getLowStockProductsCSV($lowStockProducts) {
        $csvFile = fopen('php://memory', 'w');

        $csvHeader = ['wizaplaceId', 'wizaplaceName', 'stock', 'wizaplaceCategoryName', 'wizaplaceCategoryId'];
        fputcsv($csvFile, $csvHeader, $delimiter = ";", $enclosure = '"');

        foreach($lowStockProducts as $lowStockProduct) {
            fputcsv($csvFile, $lowStockProduct, $delimiter = ";", $enclosure = '"'); 
        }
        fseek($csvFile, 0);
        header('Content-Type: application/csv');
        header('Content-Disposition: attachment; Filename="export_catalog.csv";');
        fpassthru($csvFile);
        exit;
    }
}