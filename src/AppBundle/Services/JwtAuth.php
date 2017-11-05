<?php

namespace AppBundle\Services;

use Firebase\JWT\JWT;

class JwtAuth
{
    public $manager;
    public $key;

    public function __construct($manager)
    {
        $this->manager = $manager;
        $this->key = 'holaquetalsoylaclave';
    }

    public function signup($email, $password, $getHash = null)
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

            $token =array(
                "sub" => $user->getId(),
                "email" => $user->getEmail(),
                "name" => $user->getName(),
                "surname" => $user->getSurname(),
                "iat" => time(),
                "exp" => time() + (7*24*60*60)
            );

            $jwt = JWT::encode($token, $this->key, 'HS256');
            $decoded = JWT::decode($jwt,$this->key, array('HS256'));

            if($getHash == null){
                $data = $jwt;
            }else{
                $data = $decoded;
            }

            $data = $jwt;

            /*$data = array(
                'status' => 'success',
                'user' => $user
            );*/
        } else {
            $data = array(
                'status' => 'error',
                'data' => 'Login Failed!!'
            );
        }

        //return $email. " ".$password;
        return $data;
    }

    /**
     * @return string
     */
    public function checkToken($jwt, $getIdentity = false)
    {
        $auth =false;

        try {
            $decoded = JWT::decode($jwt, $this->key, array('HS256'));
        } catch (\UnexpectedValueException $e) {
            $auth = false;
        } catch (\DomainException $e){
            $auth = false;
        }

        if (isset($decoded) && is_object($decoded) && isset($decoded->sub)) {
            $auth = true;
        } else {
            $auth = false;
        }

        if ($getIdentity == false) {
            return $auth;
        } else {
            return $decoded;
        }
    }
}