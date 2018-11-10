<?php

namespace OC\StockManagementBundle\CategoriesUpdater;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use OC\StockManagementBundle\Entity\Category;

class OCCategoriesUpdater
{
    public function updateCategories($categories, $repository, $em) {
                //Getting what is necessary from the return of the API
                foreach ($categories as $category) {
                    $categoriesFiltered[] = array(
                        'name' => $category['category'],
                        'wizaplaceId' => $category['category_id'],
                        'parentId' => $category['parent_id'],
                        'status' => $category['status'],
                    );
                }
        
                //For each category, update or edit the doctrine objects
                foreach($categoriesFiltered as $category) {
                    if($category['status'] === "A") {
                        if ($repository->findOneBy(array('wizaplaceId' => $category['wizaplaceId']))) {
                            $categoryToEdit = $repository->findOneBy(
                                array('wizaplaceId' => $category['wizaplaceId'])
                            );
                        } else {
                            $categoryToEdit = new Category();
                        }
        
                        $parent = $repository->findOneBy(
                            array('wizaplaceId' => $category['parentId'])
                        );
        
                        $categoryToEdit
                            ->setName($category['name'])
                            ->setParent($parent)
                            ->setWizaplaceId($category['wizaplaceId'])
                        ;
        
                        $em->persist($categoryToEdit);
                    }
                }
                $em->flush();
    }
}