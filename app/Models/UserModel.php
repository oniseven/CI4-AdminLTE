<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
  protected $table = 'users';
  protected $allowedFields = ['fullname', 'username', 'email', 'password', 'is_active'];

  protected $useTimestamps = false;

  // for the sake of simplicity I didnt use entity in here
  protected $returnType = 'object';

  public function getActive() {
    return $this->where(['is_active' => 1])->findAll();
  }
}