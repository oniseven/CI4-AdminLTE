<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class UserSeeder extends Seeder
{
  private $tableName = 'users';

  public function run() {
    $data = [
      [
        'fullname' => 'administrator',
        'username' => 'admin',
        'email'    => 'admin@example.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
      ],
      [
        'fullname' => 'user 123',
        'username' => 'user',
        'email'    => 'user@example.com',
        'password' => password_hash('password123', PASSWORD_DEFAULT),
      ],
    ];

    // Insert data
    $this->db->table($this->tableName)->insertBatch($data);
  }
}
