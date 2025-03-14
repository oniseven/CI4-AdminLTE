<?php

namespace App\Models;

use CodeIgniter\Model;

class PrivilegeMenuModel extends Model {
  protected $table = 'privilege_menus';
  protected $allowedFields = ['privilege_id', 'menu_id', 'is_selected'];

  protected $useTimestamps = false;

  // for the sake of simplicity I didnt use entity in here
  protected $returnType = 'object';
}