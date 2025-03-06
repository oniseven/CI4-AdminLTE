<?php

namespace App\Validations;

class UserValidation {
  public static function rules($userId = null) {
    $uniqueUsername = $userId ? '|is_unique[users.username]' : "|is_unique[users.username, users.id, {$userId}]";
    $uniqueEmail = $userId ? '|is_unique[users.email]' : "|is_unique[users.email, users.id, {$userId}]";

    $rules = [
      'id' => 'numeric',
      'fullname' => 'required|alpha_numeric_space',
      'username' => 'required|min_length[3]|max_length[255]'.$uniqueUsername,
      'email' => 'required|valid_email'.$uniqueEmail,
      'password' => 'required_without(id)|min_length[6]',
      'is_active' => 'required|numeric'
    ];

    return $rules;
  }

  public static function errorMessages() {
    return [
      'fullname' => [
        'required' => 'Fullname is required',
        'alpha_numeric_space' => 'Fullname must be in alpha numeric and space',
      ],
      'username' => [
        'required' => 'Username is required',
        'min_length' => 'Username must be at least 3 characters long.',
        'is_unique'  => 'This username is already taken.',
      ],
      'email' => [
        'required'   => 'Email is required.',
        'valid_email' => 'Please provide a valid email address.',
        'is_unique'  => 'This email is already registered.',
      ],
      'password' => [
        'required'   => 'Password is required.',
        'min_length' => 'Password must be at least 6 characters long.',
      ],
      'is_active' => [
        'required'   => 'Status active is required.',
        'numeric' => 'Status Active must be in numeric',
      ],
    ];
  }
}