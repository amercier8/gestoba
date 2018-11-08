<?php

namespace OC\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;
use GuzzleHttp\Client;
use OC\UserBundle\Entity\User;
use FOS\UserBundle\Model\UserInterface;
use FOS\UserBundle\Entity\UserManager;
use OC\UserBundle\Form\UserType;
use OC\UserBundle\Form\UsersType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;

class UserManagementController extends Controller
{
    /**
    * @Security("has_role('ROLE_ADMIN')")
    */
    public function getUsersAction(Request $request) {
        $users = $this->get('fos_user.user_manager')->findUsers();

        $form = $this
            ->get('form.factory')
            ->create(CollectionType::class, $users, array(
                'entry_type' => UserType::class,
                'attr' => array('class' => 'collection'),
            ))
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            foreach ($users as $user) {
                $em->persist($user);
                $em->flush();
            }
        $request
            ->getSession()
            ->getFlashBag()
            ->add('notice', 'Modifications bien enregistrées');
        }

        return $this->render('OCUserBundle:Security:manageUsers.html.twig', array(
            'formUsers' => $form->createView(),
        ));
    }

    public function getUserAction(Request $request) {
        $user = $this->getUser();

        $form = $this
            ->get('form.factory')
            ->create(UserType::class, $user)
        ;

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
                $em->flush();

            $this->addFlash(
                'notice',
                'Modifications enregistrées'
            );
        }
        return $this->render('OCUserBundle:Security:manageUser.html.twig', array(
            'formUser' => $form->createView(),
            //Ajout
            // 'userMail' => $this->getUser()->getMail(),
            // 'userPassword' => $this->getUser()->getPassword(),
            //Fin ajout
        ));
    }

}