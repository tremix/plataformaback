<?php

namespace AppBundle\Controller;

use AppBundle\Services\JwtAuth;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\Tests\Compiler\J;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Validator\Constraints as Assert; 
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

    
    public function pruebasAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $jwt_auth = $this->get(JwtAuth::class);
        $token = $request->get("authorization", null);

        if ($token  && $jwt_auth->checkToken($token) == true) {

            //echo "HOLA MUNDO NOPM";
            $em = $this->getDoctrine()->getManager();
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


            /*echo $helpers->holaMundo();
            die();*/

            //return $helpers->json($users);
            //die();

            return $this->json(array(
                'status' => 'success',
                'users' => $users[0]->getName()
            ));
        }else
        {
            return $this->json(array(
                'status' => 'error',
                'code' => 400,
                'data' => 'Login Failed'
            ));
        }

        
    }

    public function loginAction(Request $request)
    {
        /*echo "Hola Login";
        die();*/

        $helpers = $this->get(Helpers::class);

        //Recibir Json por Post
        $json = $request->get('json', null);

        //array a devolver con data
        $data = array(
            'status' => 'error' ,
            'data' => 'send json via post'
         );

        if ($json != null) {
            //hacer Login
            
            //convertimos un json a un objeto de php
            $params = json_decode($json);

            $email = (isset($params->email)) ? $params->email : null;

            $password = (isset($params->password)) ? $params->password : null;

            $getHash = (isset($params->getHash)) ? $params->getHash : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid!!";
            $validate_email = $this->get("validator")->validate($email,$emailConstraint);

            // Cifrar el password

            $pwd = hash('sha256', $password);

            if($email != null && count($validate_email) == 0 && $password != null){

                $jwt_auth = $this->get(JwtAuth::class);

                if ($getHash == null || $getHash == false){
                    $signup = $jwt_auth->signup($email,$pwd);
                }else{
                    $signup = $jwt_auth->signup($email,$pwd, true);
                }

                return $this->json($signup);
                /*$data = array(
                   'status' => 'success' ,
                   'data' => 'OK',
                    'signup' => $signup
                );*/
            }
            else  { 
            //     # code...
                 $data = array(
                   'status' => 'error' ,
                   'data' => 'Email or Password incorrect'
                 );
            }
        }
        return $helpers->json($data);
    }
}
