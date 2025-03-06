<?php
namespace App\Controllers;

class Register extends BaseController
{
  public function index()
  {
    return $this->template
      ->page_title('Register Page')
      ->tag_class('body', 'hold-transition register-page')
      ->render('register');
  }
}