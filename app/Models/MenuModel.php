<?php

namespace App\Models;

use CodeIgniter\Model;

class MenuModel extends Model {
  protected $table = 'menus as m';
  protected $allowedFields = ['parent_id', 'position', 'name', 'slug', 'link', 'icon', 'is_last', 'is_active'];

  protected $useTimestamps = false;

  protected $returnType = 'object';

  protected $validationRules = [
    "parent_id" => "numeric",
    "potition" => "required|in_list[top,left]",
    "name" => "required|alpha_numeric_space",
    "slug" => "required|alpha_dash",
    "link" => "required",
    "icon" => "alpha_numeric_punct",
    "is_last" => "required|numeric",
    "is_active" => "required|numeric"
  ];

  public function getActive() {
    return $this->where(['is_active' => 1])->findAll();
  }
}