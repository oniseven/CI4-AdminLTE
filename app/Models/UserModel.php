<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model {
  protected $table = 'users';
  protected $allowedFields = ['fullname', 'username', 'email', 'password', 'is_active'];

  protected $useTimestamps = false;

  // for the sake of simplicity I didnt use entity in here
  protected $returnType = 'object';

  protected $beforeInsert = ['hashPassword'];
  protected $beforeUpdate = ['hashPasswordIfProvided'];

  public function getActive() {
    return $this->where(['is_active' => 1])->findAll();
  }

  protected function hashPassword(array $data) {
    if(isset($data['data']['password'])) {
      $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    }
    return $data;
  }

  protected function hashPasswordIfProvided(array $data) {
    if(isset($data['data']['password'])){
      $data['data']['password'] = password_hash($data['data']['password'], PASSWORD_DEFAULT);
    } else {
      // remove password from data if no provide
      unset($data['data']['password']);
    }

    return $data;
  }
}