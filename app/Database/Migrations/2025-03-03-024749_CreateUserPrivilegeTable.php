<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateUserPrivilegeTable extends Migration
{
    private $tableName = 'user_privileges';
    public function up()
    {
        $this->forge->addField([
            'user_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
            'privilege_id' => [
                'type' => 'INT',
                'constraint' => 10,
                'unsigned' => true,
            ],
        ]);
        $this->forge->addKey(['user_id', 'privilege_id'], true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->addForeignKey('privilege_id', 'privileges', 'id', 'CASCADE', 'RESTRICT');
        $this->forge->createTable($this->tableName);
    }

    public function down()
    {
        $this->forge->dropTable($this->tableName);
    }
}
