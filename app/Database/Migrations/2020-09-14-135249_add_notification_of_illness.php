<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNotificationOfIllness extends Migration
{
    public function up()
    {

/* -------------------------------------------------------------------------- */
/*                        notification of illness table                       */
/* -------------------------------------------------------------------------- */

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
            'begin' => ['type' => 'datetime', 'null' => true],
            'end' => ['type' => 'datetime', 'null' => true],
            'open' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'intime' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
            'users_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('users_id', 'users', 'id', false, 'CASCADE');

        $this->forge->createTable('noticication_of_illness', true);

/* -------------------------------------------------------------------------- */
/*                             illness_form tabele                            */
/* -------------------------------------------------------------------------- */

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
            'noticication_of_illness_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'filename' => ['type' => 'varchar', 'constraint' => 255],
            'filetype' => ['type' => 'varchar', 'constraint' => 255],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('noticication_of_illness_id', 'noticication_of_illness', 'id', false, 'CASCADE');

        $this->forge->createTable('illness_form', true);

/* -------------------------------------------------------------------------- */
/*                         healthe_certificates table                         */
/* -------------------------------------------------------------------------- */

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
            'noticication_of_illness_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'filename' => ['type' => 'varchar', 'constraint' => 255],
            'filetype' => ['type' => 'varchar', 'constraint' => 255],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('noticication_of_illness_id', 'noticication_of_illness', 'id', false, 'CASCADE');

        $this->forge->createTable('health_certificates', true);

    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
			$this->forge->dropForeignKey('noticication_of_illness', 'noticication_of_illness_users_id_foreign');
			$this->forge->dropForeignKey('illness_form', 'illness_form_noticication_of_illness_id_foreign');
            $this->forge->dropForeignKey('health_certificates', 'health_certificates_noticication_of_illness_id_foreign');
			

        }

        $this->forge->dropTable('noticication_of_illness', true);
        $this->forge->dropTable('illness_form', true);
        $this->forge->dropTable('health_certificates', true);
    }
}
