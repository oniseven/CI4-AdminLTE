<?php

namespace App\Validations;

class PrivilegeValidation {
  public static function rules($id = null) {
    $uniqueName = $id ? "|is_unique[privileges.name, privileges.id, {$id}]" : '|is_unique[privileges.name]';

    $rules = [
      'id' => 'numeric|permit_empty',
      'name' => 'required|alpha_numeric_space'.$uniqueName,
      'is_active' => 'required|numeric'
    ];

    return $rules;
  }

  public static function errorMessages() {
    return [
      'name' => [
        'required' => 'Privilege name is required',
        'alpha_numeric_space' => 'Privilege name must be in alpha numeric and space',
        'is_unique'  => 'Privilege name is already taken.',
      ],
      'is_active' => [
        'required'   => 'Status active is required.',
        'numeric' => 'Status Active must be in numeric',
      ],
    ];
  }
}