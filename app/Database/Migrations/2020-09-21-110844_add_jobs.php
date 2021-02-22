<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddJobs extends Migration
{
    public function up()
    {

/* -------------------------------------------------------------------------- */
/*                              create jobs table                             */
/* -------------------------------------------------------------------------- */

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'jobs' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
            'users_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('users_id', 'users', 'id', false, 'CASCADE');

        $this->forge->createTable('jobs', true);

/* -------------------------------------------------------------------------- */
/*                      create classesjobs relation table                     */
/* -------------------------------------------------------------------------- */
        $this->forge->addField([
            'classes_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'jobs_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);


        $this->forge->addForeignKey('classes_id', 'classes', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('jobs_id', 'jobs', 'id', false, 'CASCADE');

        $this->forge->createTable('classesjobs', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            $this->forge->dropForeignKey('jobs', 'jobs_users_id_foreign');
            $this->forge->dropForeignKey('classesjobs', 'classesjobs_classes_id_foreign');
            $this->forge->dropForeignKey('classesjobs', 'classesjobs_jobs_id_foreign');

        }

		$this->forge->dropTable('jobs', true);
        $this->forge->dropTable('classesjobs', true);
		
    }
}
