<?php
namespace App\Controllers\Setting;

use App\Controllers\BaseController;
use App\Libraries\Datatables;
use App\Models\UserModel;
use App\Models\UserPrivilegeModel;
use App\Services\ValidationService;

class User extends BaseController 
{
  public function save() 
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $status = false;
    $message = 'Data gagal disimpan, silahkan coba lagi!';
    $info = null;

    $id = $this->request->getPost('id');
    $fullname = $this->request->getPost('fullname');
    $username = $this->request->getPost('username');
    $email = $this->request->getPost('email');
    $password = $this->request->getPost('password');
    $privilege = $this->request->getPost('privilege');
    $is_active = $this->request->getPost('is_active');

    // input validation
    $validation = \Config\Services::validation();
    $validation->setRules(
      ValidationService::getRules('user', ['userId' => $id, 'password' => $password]),
      ValidationService::getMessages('user')
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
      'id' => $id,
      'fullname' => $fullname,
      'username' => $username,
      'email' => $email,
      'password' => $password,
      'is_active' => $is_active
    ];

    // insert new user
    $userModel = new UserModel();
    $user = $userModel->save($data);
    if(empty($id)){
      $id = $userModel->getInsertID();
    }
    
    // insert privilege
    $userPrivilegeModel = new UserPrivilegeModel();
    $deleteFirst = $userPrivilegeModel->where('user_id', $id)->delete();
    $userPrivilege = $userPrivilegeModel->save([
      'user_id' => $id,
      'privilege_id' => $privilege
    ]);

    if($user && $userPrivilege){
      $status = true;
      $message = 'success';
    } else {
      $info = $userModel->errors();
    }

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => $message,
        'info' => $info
      ]
    ];

    return $this->response->setJSON($response);
  }

  public function delete($id)
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $status = false;
    $message = "Gagal menghapus data";
    $info = null;

    $modelPriv = new UserPrivilegeModel();
    $modelPriv->where('user_id', $id)->delete();

    $model = new UserModel();
    $delete = $model->delete($id);
    if($delete){
      $status = true;
      $message = 'Pengguna berhasil dihapus!';
    } else {
      $info = $model->errors();
    }

    $response = [
      'metadata' => [
        'status' => $status,
        'message' => $message,
        'info' => $info,
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

    $model = new UserModel();
    $data = $model->find($input['id']);
    $data->is_active = $input['checked'];

    $update = $model->save($data);
    if($update){
      $status = true;
      $message = 'Status pengguna berhasil diperbarui!';
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

  public function datatable()
  {
    if(!$this->request->isAJAX()) {
      return $this->respondNoContent();
    }

    $dt = new Datatables(service('request'));

    $columns = [
			'u.id', 
			'fullname', 
			'username',
			'email',
      'p.name as privilege',
			'u.is_active',
      'p.id as privilege_id'
		];

    $joins = [
      [
        'user_privileges as up',
        'up.user_id = u.id',
        'inner'
      ],
      [
        'privileges as p',
        'p.id = up.privilege_id',
        'inner'
      ]
    ];

    $data = $dt
      ->select($columns)
      ->joins($joins)
      ->searchType('column')
      ->showQuery()
      ->showConfigs()
      ->loadData('users as u');

    return $this->response->setJSON($data);
  }
}