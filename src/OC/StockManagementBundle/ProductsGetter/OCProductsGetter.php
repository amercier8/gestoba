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
            'base_uri' => 'https://sandbox.wizaplace.com',
        ]);

        $promise = $client->requestAsync('GET', '/api/v1/catalog/export');
        $response = $promise->wait();

            $body = json_decode($response
                ->getBody()
                ->getContents(), true);

            return $body;
    }
}