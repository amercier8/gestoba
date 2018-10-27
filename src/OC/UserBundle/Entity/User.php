<?php
// src/OC/UserBundle/Entity/User.php

namespace OC\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as BaseUser;

    /**
     * @ORM\Table(name="oc_user")
     * @ORM\Entity(repositoryClass="OC\UserBundle\Repository\UserRepository")
     */
    class User extends BaseUser
        {
            /**
             * @ORM\Column(name="id", type="integer")
             * @ORM\Id
             * @ORM\GeneratedValue(strategy="AUTO")
             */
            protected $id;

            /**
             * @var int
             *
             * @ORM\Column(name="apiKey", type="text", length=255, nullable=true)
             */
            private $apiKey;

            /**
             * Get apiKey.
             *
             * @return text
             */
            public function getApiKey()
            {
                return $this->apiKey;
            }

            /**
             * Set apiKey.
             *
             * @param text $apiKey
             *
             * @return User
             */
            public function setApiKey($apiKey)
            {
                $this->apiKey = $apiKey;

                return $this;
            }
        }