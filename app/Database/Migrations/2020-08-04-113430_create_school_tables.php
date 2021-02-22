<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchoolTables extends Migration
{
	public function up()
	{
        /*
 * Classes
 */
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 90, 'null' => true],
            'internal_id'      => ['type' => 'varchar', 'constraint' => 255],
            'begin'            => ['type' => 'datetime', 'null' => true],
            'end'              => ['type' => 'datetime', 'null' => true],
            'enrolled_students'       => ['type' => 'tinyint', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('classes', true);






/*
* Vacations
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'begin'            => ['type' => 'datetime', 'null' => true],
            'end'              => ['type' => 'datetime', 'null' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('vacations', true);





        /*
* classesvacations
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'classes_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'vacations_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('classes_id', 'classes', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('vacations_id', 'vacations', 'id', false, 'CASCADE');
        $this->forge->createTable('classesvacations', true);









        /*
* Rooms
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 90, 'null' => true],
            'capacity'       => ['type' => 'tinyint', 'null' => true],
            'installed_equipment'   => ['type' => 'varchar', 'constraint' => 255, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('rooms', true);











	}






	//--------------------------------------------------------------------

	public function down()
	{

        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3')
        {
            $this->forge->dropForeignKey('classesvacations', 'classesvacations_classes_id_foreign');
            $this->forge->dropForeignKey('classesvacations', 'classesvacations_vacations_id_foreign');

        }

        $this->forge->dropTable('classes', true);
        $this->forge->dropTable('vacations', true);
        $this->forge->dropTable('classesvacations', true);
        $this->forge->dropTable('rooms', true);


	}
}
