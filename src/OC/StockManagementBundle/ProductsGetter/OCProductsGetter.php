<?php

namespace OC\StockManagementBundle\ProductsGetter;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class OCProductsGetter
{
    public function getProducts() {
        // $usermail = app.user.email;
        // $userPassword = app.user.password;


        // $userCredentials = base64_encode($userCredentials);
        $userCredentials = base64_encode("aa");
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests
            //Marchand asas115
            'base_uri' => 'https://sandbox.wizaplace.com',
            // 'headers' => [
            //     'Authorization' => 'Basic .$userCredentials'
            // ]
        ]);

        $response = $client->request('GET', '/api/v1/catalog/export');

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);

            return $body;
    }
}