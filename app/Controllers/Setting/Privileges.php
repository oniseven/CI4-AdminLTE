<?php
namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Libraries\Datatables;
use App\Services\ValidationService;
use App\Models\MenuModel;
use App\Models\PrivilegeModel;
use App\Models\PrivilegeMenuModel;

class Privileges extends BaseController 
{
  public function index()
  {
    return $this->template
      ->page_title('Setting Privileges')
      ->plugins([
        'datatables', 
        'jstree',
        'select2',
        'validation'
      ])
      ->page_js([
        'assets/js/pages/setting/privileges.js', 
        'assets/js/pages/setting/user.js'
      ])
      ->render('setting/privileges/main');
  }

  public function save()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $status = false;
    $message = 'Data gagal disimpan, silahkan coba lagi!';
    $error = null;

    $id = $this->request->getPost('id');
    $name = $this->request->getPost('name');
    $is_active = $this->request->getPost('is_active');

    // input validation
    $validation = \Config\Services::validation();
    $validation->setRules(
      ValidationService::getRules('privilege', ['id' => $id]),
      ValidationService::getMessages('privilege')
    );

    if (!$validation->withRequest($this->request)->run()) {
      return $this->response->setJSON([
        "metadata" => [
          "status" => false,
          "message" => array_values($validation->getErrors())[0]
        ]
      ]);
    }

    $data = [
      "name" => $name,
      "is_active" => $is_active
    ];
    if((int) $id){
      $data['id'] = $id;
    }

    $model = new PrivilegeModel();

    if ($model->save($data) === false) {
      $error = $model->errors();
    } else {
      $status = true;
      $message = 'Success';
    }

    $respose = [
      "metadata" => [
        "status" => $status,
        "message" => $message,
        "error" => $error
      ]
    ];

    return $this->response->setJSON($respose);
  }

  public function delete($id)
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $model = new PrivilegeModel();
    list(
      $status,
      $message
    ) = $model->isUsed($id);
    if(!$status){
      $this->response->setJSON([
        'metadata' => [
          'status' => $status,
          'message' => $message
        ]
      ]);
    }

    $status = false;
    $message = "Gagal menghapus data";
    $error = null;

    // delete privilege
    $delete = $model->delete($id);
    if($delete){
      $status = true;
      $message = 'Privilege berhasil dihapus!';
    } else {
      $error = $model->errors();
    }

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => $message,
        'info' => $error,
      ]
    ];

    return $this->response->setJSON($response);
  }

  public function status()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $input = $this->request->getRawInput();

    $status = false;
    $message = 'Gagal updated status!';
    $error = null;

    $model = new PrivilegeModel();
    $data = $model->find($input['id']);
    $data->is_active = $input['checked'];

    $update = $model->save($data);
    if($update){
      $status = true;
      $message = 'Privilege status berhasil diperbarui!';
    } else {
      $error = $model->errors();
    }

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => $message,
        'info' => $error,
      ]
    ];

    return $this->response->setJSON($response);
  }

  public function menus()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $data = $this->request->getPost('data');
		$group_id = $this->request->getPost('group_id');
		$menu_id = $this->request->getPost('menu_id');

    $model = new PrivilegeMenuModel();
    $model->transBegin();

    // delete first
    $model->where('privilege_id', $group_id)->delete();

    if(!empty($data)){
      $model->insertBatch($data);
    }

    $status = false;
    $message = "Menu gagal disimpan!";
    if ($model->transStatus() === false) {
      $model->transRollback();
    } else {
      $model->transCommit();
      $status = true;
      $message = 'success';
    }

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => $message
      ],
    ];

    return $this->response->setJSON($response);
  }

  public function list()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }
    
    $status = true;
    $message = "sukses";

    $keyword = $this->request->getGet('q');

    $model = new PrivilegeModel();
    $model->select(['id', 'name as text'])
      ->where('is_active', 1);
    if(!empty($keyword)){
      $model->like('name', $keyword, 'both');
    }
    $data = $model->findAll();

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => 'sukses'
      ],
      "data" => $data ?? []
    ];

    return $this->response->setJSON($response);
  }

  public function menutree()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $id = $this->request->getGet('id');
    $id = $id === '#' ? NULL : $id;
    $group_id = $this->request->getGet('group_id');

    $model = new MenuModel();
    $model->select(['m.*', 'IFNULL(pm.is_selected, 0) as selected'], false)
      ->join('privilege_menus as pm', "pm.menu_id = m.id AND pm.privilege_id = {$group_id}", 'left')
      ->where('parent_id', $id)
      ->where('is_active', 1)
      ->orderBy('m.parent_id', 'ASC');

    $menus = $model->findAll() ?? [];
    foreach ($menus as $key => $menu) {
      $icon = $menu->is_last ? 'fa-file text-primary' : 'fa-folder text-warning';
			$data[$key] = [
				"id" => $menu->id,
				"text" => $menu->name,
				'icon' => "fa {$icon} icon-md",
				"state" => [
					"selected" => (int) $menu->selected ? true : false,
				],
				"children" => !(int) $menu->is_last ? true : false
			];
    }

    $response = [
      'metadata' => [
        'status' => true,
        'message' => 'success'
      ],
      'data' => $data
    ];

    return $this->response->setJSON($data);
  }

  public function datatable() 
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $dt = new Datatables(service('request'));

    $columns = [
			'id', 
			'name', 
			'1 as menu',
			'is_active',
		];

    $data = $dt
      ->select($columns)
      ->searchType('column')
      ->showQuery()
      ->showConfigs()
      ->loadData('privileges');

    return $this->response->setJSON($data);
  }
}