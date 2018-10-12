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

        $positions = array(
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
            '0',
            '1',
            '1',
            '3',
            '3',
            '3',
            '2',
            '2',
            '0',
            '50',
            '50',
            '52',
            '52',
            '50',
            '55',
            '56',
        );

        $lowStocks = array(
            15,
            20,
            20,
            50,
            500,
            10,
            8,
            50,
            21,
            30,
            32,
            9,
            30,
            7,
            30,
            30,
        );

        for ($i = 0; $i < count($parentIds); $i++) {
            $category = new Category();
            $category->setName($names[$i]);
            $category->setId($ids[$i]);
            $category->setPosition($positions[$i]);
            $category->setParentId($parentIds[$i]);
            $category->setLowStock($lowStocks[$i]);

        // On la persiste
        $manager->persist($category);
        }

        $manager->flush();
    }
}