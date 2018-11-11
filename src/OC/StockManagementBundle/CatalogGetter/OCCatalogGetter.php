<?php

namespace OC\StockManagementBundle\CatalogGetter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCCatalogGetter
{
    public function getCategories($userCredentials) {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests

            'base_uri' => 'https://sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic '.$userCredentials
            ]
        ]);

        $promise = $client->requestAsync('GET', '/api/v1/categories');
        $response = $promise->wait();

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);

        return $body;
    }

}

