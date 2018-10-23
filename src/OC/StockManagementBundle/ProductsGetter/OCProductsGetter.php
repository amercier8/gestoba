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

            'base_uri' => 'https://alexandbox.sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic YWxleCt0ZXN0c3VwcG9ydDJAd2l6YXBsYWNlLmNvbTpMMjlBQUtNcFpzejZxNWUwREZPbjRLY0I0YzRTK3JmQ0Yrd3AwSGRl'
            ]
        ]);

        $response = $client->request('GET', '/api/v1/catalog/export/1');

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);

            return $body;
    }
}