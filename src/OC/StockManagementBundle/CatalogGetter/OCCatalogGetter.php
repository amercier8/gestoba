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

            'base_uri' => 'https://alexandbox.sandbox.wizaplace.com',
            'headers' => [
                'Authorization' => 'Basic YWxleCt0ZXN0c3VwcG9ydDJAd2l6YXBsYWNlLmNvbTpMMjlBQUtNcFpzejZxNWUwREZPbjRLY0I0YzRTK3JmQ0Yrd3AwSGRl'
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

