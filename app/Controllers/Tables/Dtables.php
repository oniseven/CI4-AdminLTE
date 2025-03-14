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
      ->page_js('assets/js/pages/datatables.js')
      ->render('tables/datatable');
  }

  public function datatable()
  {
    if ($this->request->isAJAX()) {
      $dt = new \App\Libraries\Datatables(service('request'));

      $columns = [
        'id', 
        'fullname',  
        'username', 
        'email', 
        'is_active'
      ];

      $data = $dt
        ->select($columns, false)
        ->searchType('column')
        ->showQuery()
        ->showConfigs()
        ->loadData('users as u');

      // $data = $dt->loadQuery('select * from users order by fullname asc');

      return $this->response->setJSON($data);
    }

    return $this->respondNoContent();
  }
}