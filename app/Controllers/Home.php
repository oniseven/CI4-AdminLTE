<?php

namespace App\Controllers;

class Home extends BaseController
{
  public function index()
  {
    return $this->template
      ->page_title('Starter Page')
      ->render('starter_page');
  }
}
