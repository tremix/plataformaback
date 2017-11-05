<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints as Assert;
use BackendBundle\Entity\User;
use AppBundle\Services\Helpers;

class UserController extends Controller{

    /**
     * @param ContainerInterface $container
     */
    public function newAction(Request $request)
    {
        $helpers = $this->get(Helpers::class);
        $json = $request->get("json", null);
        $params =  json_decode($json);

        $data = array(
          'status' => 'error',
            'code' => 400,
            'msg' => 'User not created'
        );

        if ($json != null){
            $createdAt = new \DateTime("now");
            $role = 'user';

            $email = (isset($params->email)) ? $params->email : null;
            $name = (isset($params->name)) ? $params->name : null;
            $surname = (isset($params->surname)) ? $params->surname : null;
            $password = (isset($params->password)) ? $params->password : null;

            $emailConstraint = new Assert\Email();
            $emailConstraint->message = "This email is not valid";
            $validateEmail = $this->get("validator")->validate($email, $emailConstraint);

            if ($email != null && count($validateEmail) == 0 && $password != null && $name != null && $surname != null){
                $user = new User();
                $user->setCreatedAt($createdAt);
                $user->setRole($role);
                $user->setEmail($email);
                $user->setName($name);
                $user->setSurname($surname);

                $em = $this->getDoctrine()->getManager();
                $isset_user = $em->getRepository('BackendBundle:User')->findBy(array(
                    'email' => $email
                ));

                if(count($isset_user) == 0){
                    $em->persist($user);
                    $em->flush();

                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'msg' => 'New User  created!!',
                        'user' => $user
                    );
                }else {

                    $data = array(
                        'status' => 'success',
                        'code' => 200,
                        'msg' => 'User not created, duplicated!!'
                    );
                }
            }

        }

        return $helpers->json($data);
    }

}