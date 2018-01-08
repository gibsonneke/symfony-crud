<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class UsersController extends Controller
{
    /**
     * @Route("/users", name="users")
     */
    public function indexAction()
    {
        $users = $this->getDoctrine()->getRepository('AppBundle:User')->findAll();
        $form = $this->createForm(UserType::class);

        return $this->render('users/index.html.twig', array(
            'users' => $users,
            'form' => $form->createView(),
        ));
    }
    
    /**
     * @Route("/users/new", name="createUser", options={"expose"=true})
     */
    public function createAction(Request $request)
    {
        if (!$request->isXmlHttpRequest()) {
            return new JsonResponse(array('message' => 'Use only ajax!'), 400);
        }

        $form = $this->createForm(UserType::class, $user = new User());
        $form->handleRequest($request);

        if($form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $em->persist($user);
            $em->flush();

            return new JsonResponse(array('message' => 'Success!'), 200);
        }
        $response = new JsonResponse(
            array(
                'message' => 'Error',
                'form' => $this->renderView('users/form.html.twig',
                    array(
                        'form' => $form->createView(),
                    ))), 400);

        return $response;
    }
    
    /**
     * @Route("/users/delete/{id}", name="deleteUser", options={"expose"=true})
     */
    public function deleteAction(User $id)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($id);
        $em->flush();

        return new Response();
    }
}