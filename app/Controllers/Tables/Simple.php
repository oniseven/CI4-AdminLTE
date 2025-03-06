<?php
namespace App\Controllers\Tables;

use App\Controllers\BaseController;

class Simple extends BaseController
{
  public function index()
  {
    return $this->template
      ->page_title('Datatables')
      ->render('tables/simple');
  }
}