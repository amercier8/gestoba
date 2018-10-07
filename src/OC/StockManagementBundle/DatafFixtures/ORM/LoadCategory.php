<?php
// src/OC/StockManagementBundle/DataFixtures/ORM/LoadCategory.php

namespace OC\StockManagementBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use OC\StockManagementBundle\Entity\Category;

class LoadCategory implements FixtureInterface
{
    public function load (ObjectManager $manager)
    {
        //name id parentId position
        $names = array(
            'Bricolage',
            'Jardin',
            'Intérieur',
            'Scies',
            'Marteaux',
            'Clous',
            'Tondeuse',
            'Rateau',
            'HiFi',
            'Image',
            'Son',
            'Enceintes',
            'Ecouteurs',
            'TV',
            'Vidéoprojecteur',
            'Ampoule vidéoprojecteur',
        );

        $ids = array(
            1,
            2,
            3,
            4,
            17,
            18,
            19,
            20,
            50,
            51,
            52,
            53,
            54,
            55,
            56,
            57,
        );

        $positionIds = array(
            10,
            2,
            2,
            5,
            5,
            10,
            8,
            5,
            3,
            3,
            3,
            3,
            3,
            3,
            3,
            3,
        );

        $parentIds = array(
            null,
            '1',
            '1',
            '3',
            '3',
            '3',
            '2',
            '2',
            null,
            '50',
            '50',
            '52',
            '52',
            '50',
            '55',
            '56',
        );

        for ($i = 0; $i < count($parentId); $i++) {
            $category = new Category();
            $category->setName($names[$i]);
            $category->setId($ids[$i]);
            $category->setPositionId($positionIds[$i]);
            $category->setParentIds($parentIds[$i]);

        // On la persiste
        $manager->persist($advert);
        }
    }
}