<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePrivilegeMenusTable extends Migration
{
    private $tableName = 'privilege_menus';

    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type' => 'BIGINT',
                'constraint' => 20,
                'unsigned' => true,
                'auto_increment' => true
            ],
            'privilege_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'menu_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'is_selected' => [
                'type' => 'TINYINT',
                'constraint' => 1,
                'default' => 1
            ]
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addKey(['privilege_id', 'menu_id'], false, true);
        $this->forge->addForeignKey('menu_id', 'menus', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('privilege_id', 'privileges', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
