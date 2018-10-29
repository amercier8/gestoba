<?php

namespace OC\StockManagementBundle\CatalogGetter;

// use GuzzleHttp;
// use Doctrine\ORM\EntityManager;
// use OC\PlatformBundle\Entity\Advert;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;


class OCCatalogGetter
{
    //attention, apres jsonddecode, modifier le :string
    public function getCategories() {
    // public function getCategories():string {
        $client = new \GuzzleHttp\Client([
            // Base URI is used with relative requests

            'base_uri' => 'https://sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic c3VwcG9ydEB3aXphcGxhY2UuY29tOmJhMjg5ODQyYjFkYjg1NDkwMTUxZDcyOWVkMDI4NTIy'
            ]
        ]);

        $response = $client->request('GET', '/api/v1/catalog/categories');
        //Ci-dessous : exemple pour passer des paramètres dans l'URL
        // $response = $client->request('GET', '/api/v1/catalog/categories', ['query' => ['filter' => 'test']]);

        $body = json_decode($response
            ->getBody()
            ->getContents(), true);
        // $code = $response->getStatusCode(); // 200
        // $reason = $response->getReasonPhrase();

        return $body;
    }

}

