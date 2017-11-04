<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use AppBundle\Services\Helpers;

class DefaultController extends Controller
{
    
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }

    
    public function pruebasAction(){

        //echo "HOLA MUNDO NOPM";
        $em =  $this->getDoctrine()->getManager();
        $userRepo = $em->getRepository('BackendBundle:User');
        $users = $userRepo->findAll();

        /*var_dump($users[0]);
        die();
        */

        /*return new JsonResponse(array(
            'status' => 'success',
            'users' => $users[0]->getName()
        ));
        */

        $helpers = $this->get(Helpers::class);
        /*echo $helpers->holaMundo();
        die();*/

        return $helpers->json($users);
        die();

        return $this->json(array(
            'status' => 'success',
            'users' => $users[0]->getName()
        ));
        

        
    }
}
