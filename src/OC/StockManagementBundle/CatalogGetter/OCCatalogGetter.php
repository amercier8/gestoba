<?php

namespace OC\StockManagementBundle\CatalogGetter;

use GuzzleHttp\Client;
// use Doctrine\ORM\EntityManager;
// use OC\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class OCCatalogGetter
{

    public function getCategories() {
        $client = new Client([
            // Base URI is used with relative requests
            'base_uri' => 'https://alexandbox.sandbox.wizaplace.com/api/v1',
            // You can set any number of default request options.
            'timeout'  => 2.0,
        ]);

        $response = $client->request('GET', '/catalog/categories/tree');

        return $response;
    }

}

