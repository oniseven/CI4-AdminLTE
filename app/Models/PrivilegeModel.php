<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Models\PrivilegeMenuModel;
use App\Models\UserPrivilegeModel;

class PrivilegeModel extends Model {
  protected $table = 'privileges';
  protected $allowedFields = ['name', 'is_active'];

  protected $useTimestamps = false;

  // for the sake of simplicity I didnt use entity in here
  protected $returnType = 'object';

  public function getActive() {
    return $this->where(['is_active' => 1])->findAll();
  }

  public function isUsed($privilege_id) {
    $pmModel = new PrivilegeMenuModel();
    $totalRows = $pmModel->where('privilege_id', $privilege_id)
      ->countAllResults();
    if($totalRows){
      return [false, 'Tidak dapat dihapus dikarenakan<br>telah memiliki menu!'];
    }

    $upModel = new UserPrivilegeModel();
    $totalRows = $upModel->where('privilege_id', $privilege_id)
      ->countAllResults();
    if($totalRows){
      return [false, 'Tidak dapat dihapus dikarenakan<br>beberapa pengguna telah memiliki hak akses ini!'];
    }

    return [true, ''];
  }
}