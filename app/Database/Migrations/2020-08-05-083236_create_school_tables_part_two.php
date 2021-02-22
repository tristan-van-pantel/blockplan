<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateSchoolTablesPartTwo extends Migration
{
	public function up()
	{
        /*
* Courses
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'name'             => ['type' => 'varchar', 'constraint' => 90, 'null' => true],
            'internal_id'      => ['type' => 'varchar', 'constraint' => 255],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->createTable('courses', true);




        /*
* datesofcourse
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'begin'            => ['type' => 'datetime', 'null' => true],
            'end'              => ['type' => 'datetime', 'null' => true],
            'rooms_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'courses_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'users_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('rooms_id', 'rooms', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('courses_id', 'courses', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('users_id', 'users', 'id', false, 'CASCADE' );


        $this->forge->createTable('datesofcourse', true);






        /*
* classesdatesofcourse
*/
        $this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'classes_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
            'datesofcourse_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
        ]);

        $this->forge->addKey('id', true);

        $this->forge->addForeignKey('classes_id', 'classes', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('datesofcourse_id', 'datesofcourse', 'id', false, 'CASCADE');


        $this->forge->createTable('classesdatesofcourse', true);





	}

	//--------------------------------------------------------------------

	public function down()
	{

        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3')
        {
            $this->forge->dropForeignKey('datesofcourse', 'datesofcourse_rooms_id_foreign');
            $this->forge->dropForeignKey('datesofcourse', 'datesofcourse_courses_id_foreign');
            $this->forge->dropForeignKey('datesofcourse', 'datesofcourse_users_id_foreign');

            $this->forge->dropForeignKey('classesdatesofcourse', 'classesdatesofcourse_classes_id_foreign');
            $this->forge->dropForeignKey('classesdatesofcourse', 'classesdatesofcourse_datesofcourse_id_foreign');






        }

        $this->forge->dropTable('courses', true);
        $this->forge->dropTable('datesofcourse', true);
        $this->forge->dropTable('classesdatesofcourse', true);

	}
}
