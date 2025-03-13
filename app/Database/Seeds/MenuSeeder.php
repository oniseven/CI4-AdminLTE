<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuSeeder extends Seeder
{
    private $tableName = 'menus';

    public function run()
    {
        // top menu
        $data = [
            [
                "id" => 1,
                "position" => 'top',
                "name" => "Home",
                "slug" => "home",
                "link" => '#',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
            [
                "id" => 2,
                "position" => 'top',
                "name" => "Contact",
                "slug" => "contact",
                "link" => '#',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
        ];

        // insert data top menu
        $this->db->table($this->tableName)->insertBatch($data);

        // left menu
        $data = [
            [
                "id" => 3,
                "parent_id" => null,
                "position" => 'left',
                "name" => "Starter Page",
                "slug" => "/",
                "link" => '/',
                "icon" => 'fas fa-tachometer-alt',
                "is_last" => 1
            ],
            [
                "id" => 4,
                "parent_id" => null,
                "position" => 'left',
                "name" => "Tables",
                "slug" => "tables",
                "link" => '#',
                "icon" => 'fas fa-table',
                "is_last" => 0
            ],
            [
                "id" => 5,
                "parent_id" => 4,
                "position" => 'left',
                "name" => "Simple Table",
                "slug" => "simple",
                "link" => 'tables/simple',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
            [
                "id" => 6,
                "parent_id" => 4,
                "position" => 'left',
                "name" => "Datatables",
                "slug" => "dtables",
                "link" => 'tables/dtables',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
            [
                "id" => 7,
                "parent_id" => null,
                "position" => 'left',
                "name" => "Extra",
                "slug" => "extra",
                "link" => '#',
                "icon" => 'far fa-plus-square',
                "is_last" => 0
            ],
            [
                "id" => 8,
                "parent_id" => 7,
                "position" => 'left',
                "name" => "Login",
                "slug" => "login",
                "link" => 'login',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
            [
                "id" => 9,
                "parent_id" => 7,
                "position" => 'left',
                "name" => "Register",
                "slug" => "register",
                "link" => 'register',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
            [
                "id" => 10,
                "parent_id" => null,
                "position" => 'left',
                "name" => "Setting",
                "slug" => "setting",
                "link" => '#',
                "icon" => 'far fa-circle',
                "is_last" => 0
            ],
            [
                "id" => 11,
                "parent_id" => 10,
                "position" => 'left',
                "name" => "Privileges",
                "slug" => "privileges",
                "link" => 'setting/privileges',
                "icon" => 'far fa-circle',
                "is_last" => 1
            ],
        ];

        // insert data left menu
        $this->db->table($this->tableName)->insertBatch($data);
    }
}
