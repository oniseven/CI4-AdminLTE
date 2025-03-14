<?php

namespace App\Models;

use CodeIgniter\Model;

class UserPrivilegeModel extends Model {
  protected $table = 'user_privileges';
  protected $allowedFields = ['user_id', 'privilege_id'];

  protected $useTimestamps = false;

  // for the sake of simplicity I didnt use entity in here
  protected $returnType = 'object';
}