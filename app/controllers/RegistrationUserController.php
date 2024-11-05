<?php

namespace App\controllers;

use SimpleMail;
use Delight\Auth\Auth;
use League\Plates\Engine;
use Tamtamchik\SimpleFlash\Flash;

class RegistrationUserController {

   private $templates;

   private $auth;

    public function __construct(Engine $engine, Auth $auth)
    {
        $this -> templates = $engine;

        $this -> auth = $auth;

    }

    public function index() {

        echo $this -> templates -> render('index_view');

    }

    public function login() {

        echo $this -> templates -> render('login');
        
    } 

    public function registerUser()
    {
        try {
            $this->auth->register($_POST['email_user'], $_POST['pass_user'], $_POST['name_user'], function ($selector, $token) {
                $verification = "<a href='".$_SERVER['HTTP_REFERER'] . 'verification/' . \urlencode($selector) . '/' . \urlencode($token)."'>Verificate your account</a>";
                   SimpleMail::make()
                           ->setTo($_POST['name_user'], $_POST['email_user'])
                           ->setFrom('info@example.com', 'Admin')
                           ->setSubject('Вы успешно зарегистрировались!')
                           ->setMessage('Вы зарегистрировались на сайте, для подтверждения почты перейдите по '.$verification)
                           ->setHtml()
                           ->send();                                                     
            
            });
            
            
            Flash::message('Вы успешно зарегистрировались!', 'success');
            echo $this->templates->render('login');
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Flash::message('Неверный адрес электронной почты!', 'error');
            echo $this->templates->render('index_view');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Flash::message('Неверный пароль!', 'error');
            echo $this->templates->render('index_view');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            Flash::message('Такой пользователь уже существует!', 'error');
            echo $this->templates->render('index_view');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Слишком много запросов!', 'error');
            echo $this->templates->render('index_view');
        }
    }

    public function verificationUser()
    {
        try {
            $this->auth->confirmEmailAndSignIn('_e6imeOEiHPBkDzt', 'hXlsqmkjo-YVKIdM');

            Flash::message('Почта успешно подтверждена', 'success');
            echo $this->templates->render('login');
        }
        catch (\Delight\Auth\InvalidSelectorTokenPairException $e) {
            die('Invalid token');
        }
        catch (\Delight\Auth\TokenExpiredException $e) {
            die('Token expired');
        }
        catch (\Delight\Auth\UserAlreadyExistsException $e) {
            die('Email address already exists');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            die('Too many requests');
        }
    }

    public function loginUser()
    {
        try {
            $this->auth->login($_POST['email_user'], $_POST['pass_user']);
            Flash::message('Главная страница', 'success');
            echo $this -> templates -> render('users');
            
        }
        catch (\Delight\Auth\InvalidEmailException $e) {
            Flash::message('Неправильный адрес электронной почты!', 'error');
            echo $this->templates->render('login');
        }
        catch (\Delight\Auth\InvalidPasswordException $e) {
            Flash::message('Неправильный пароль!', 'error');
            echo $this->templates->render('login');
        }
        catch (\Delight\Auth\EmailNotVerifiedException $e) {
            Flash::message('Электронная почта не подтверждена!', 'error');
            echo $this->templates->render('login');
        }
        catch (\Delight\Auth\TooManyRequestsException $e) {
            Flash::message('Слишком много запросов!', 'error');
            echo $this->templates->render('login');
        }

    }

    public function logOut()
    {
        $this->auth->logOut();
        echo $this -> templates -> render('index_view');
    }

}

?>