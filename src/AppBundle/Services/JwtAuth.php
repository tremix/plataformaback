<?php

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth
{
    public $manager;

    public function __construct($manager)
    {
        $this->manager = $manager;
    }

    public function signup($email, $password)
    {
        $user = $this->manager->getRepository('BackendBundle:User')->findOneBy(array(
            'email' => $email,
            'password' => $password
        ));

        $sigunp = false;
        if (is_object($user)) {
            $sigunp = true;
        }

        if ($sigunp == true) {
            //Generar Token

            $data = array(
                'status' => 'success',
                'user' => $user
            );
        } else {
            $data = array(
                'status' => 'error',
                'data' => 'Login Failed!!'
            );
        }

        //return $email. " ".$password;
        return $data;
    }
}