<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Task;
use AppBundle\Form\TaskType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class TasksController extends Controller
{
    /**
     * @Route("/", name="home")
     * @Route("/tasks", name="tasks")
     */
    public function indexAction()
    {
        $data = [];
    
        $tasks = $this->getDoctrine()->getRepository(Task::class)->findAll();
        $data['tasks'] = $tasks;

        return $this->render('tasks/index.html.twig', $data);
    }

    /**
     * @Route("/tasks/create", name="createTask")
     */
    public function createAction(Request $request, ValidatorInterface $validator)
    {
        $data = [];
        $data['mode'] = 'new_task';
        $data['form'] = [];
        $data['errors'] = [];

        $form = $this   ->createFormBuilder()
                        ->add('title')
                        ->add('description')
                        ->getForm()
                ;

        $form->handleRequest($request);

        if($form->isSubmitted())
        {
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;

            $em = $this->getDoctrine()->getManager();
            $task = new Task();
            $task->setTitle($form_data['title']);
            $task->setDescription($form_data['description']);
            
            $errors = $validator->validate($task);
            $data['errors'] = $errors;
            
            if(count($errors) == 0)
            {
                $em->persist($task);
    
                $em->flush();
    
                return $this->redirectToRoute('tasks');
            }
        }
        
        return $this->render("tasks/form.html.twig", $data);
    }
    
    /**
    * @Route("/tasks/edit/{id}", name="editTask")
    **/
    public function editAction(Request $request, ValidatorInterface $validator, $id)
    {
        $data = [];

        $task_repo = $this->getDoctrine()->getRepository(Task::class);
        $data['mode'] = 'modify';
        $data['form'] = [];
        $data['errors'] = [];
        
        $form = $this   ->createFormBuilder()
                        ->add('title')
                        ->add('description')
                        ->getForm()
                ;

        $form->handleRequest( $request );

        if( $form->isSubmitted() )
        {
            $form_data = $form->getData();
            $data['form'] = [];
            $data['form'] = $form_data;
            $task = $task_repo->find($id);

            $task->setTitle($form_data['title']);
            $task->setDescription($form_data['description']);
            
            $errors = $validator->validate($task);
            $data['errors'] = $errors;
            
            if(count($errors) == 0)
            {
                $em = $this->getDoctrine()->getManager();
                $em->flush();
    
                return $this->redirectToRoute('tasks');
            }

        }else
        {
            $task = $task_repo->find($id);
            
            $task_data['title'] = $task->getTitle();
            $task_data['description'] = $task->getDescription();

            $data['form'] = $task_data;
        }

        return $this->render("tasks/form.html.twig", $data);

    }
    
    /**
     * @Route("/tasks/delete/{id}", name="deleteTask")
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $task = $em->getRepository(Task::class)->find($id);
        $em->remove($task);
        $em->flush();

        return $this->redirectToRoute('tasks');
    }
}