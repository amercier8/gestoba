<?php

namespace OC\StockManagementBundle\ProductsGetter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCProductsGetter
{
    public function getProducts() {

        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            //Marchand asas115
            'base_uri' => 'https://alexandbox.sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic YWxleCt0ZXN0MTgxNkB3aXphcGxhY2UuY29tOlZYK0w4YWxjaVFHOHRmMWVUWkpISUtkQm5wNnM2VU1FNTdNdWN6MDg='
            ]
        ]);

        $response = $client->request('GET', '/api/v1/catalog/search/products');

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);

            return $body;
    }
}