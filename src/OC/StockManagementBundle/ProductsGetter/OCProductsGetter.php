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
            'base_uri' => 'https://sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic c3VwcG9ydEB3aXphcGxhY2UuY29tOmJhMjg5ODQyYjFkYjg1NDkwMTUxZDcyOWVkMDI4NTIy'
            ]
        ]);

        $response = $client->request('GET', '/api/v1/catalog/export');

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);

            return $body;
    }
}