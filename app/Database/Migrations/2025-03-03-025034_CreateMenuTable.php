<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateMenuTable extends Migration
{
    private $tableName = 'menus';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'parent_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
                'null' => true
            ],
            'position' => [
                'type' => 'ENUM',
                'constraint' => ['left', 'top'],
                'default' => 'left'
            ],
            'name' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'slug' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
                'unique' => true,
            ],
            'link' => [
                'type' => 'VARCHAR',
                'constraint' => 100,
            ],
            'icon' => [
                'type' => 'VARCHAR',
                'constraint' => 30,
            ],
            'is_last' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ],
            'is_active' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
