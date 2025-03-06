<?php
namespace App\Controllers;

class Login extends BaseController
{
  public function index()
  {
    return $this->template
      ->page_title('Login Page')
      ->tag_class('body', 'hold-transition login-page')
      ->render('login');
  }
}