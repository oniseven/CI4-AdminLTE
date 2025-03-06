<?php
namespace App\Controllers\Tables;

use App\Controllers\BaseController;

class Dtables extends BaseController
{
  public function index()
  {
    return $this->template
      ->page_title('Datatables')
      ->plugins('datatables')
      ->page_js('assets/dist/js/pages/datatables.js')
      ->render('tables/datatable');
  }

  public function datatable()
  {
    if ($this->request->isAJAX()) {
      $dt = new \App\Libraries\Datatables(service('request'));

      $dt->columns = [
        'id', 
        'fullname',  
        'username', 
        'email', 
        'is_active'
      ];

      $dt->search_type = 'column';
      $data = $dt->loadData('UserModel');

      // $data = [
      //   "recordsTotal" => 0,
      //   "recordsFiltered" => 0,
      //   "data" => [],
      //   "conditions" => ''
      // ];

      return $this->response->setJSON($data);
    }

    return $this->respondNoContent();
  }
}