<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddCourses2DoTables extends Migration
{
	public function up()
	{
        /*
* courses2do
*/
$this->forge->addField([
	'classes_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
	'courses_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true],
]);



$this->forge->addForeignKey('classes_id', 'classes', 'id', false, 'CASCADE');
$this->forge->addForeignKey('courses_id', 'courses', 'id', false, 'CASCADE');


$this->forge->createTable('courses2do', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		        // drop constraints first to prevent errors
				if ($this->db->DBDriver != 'SQLite3')
				{
					$this->forge->dropForeignKey('courses2do', 'courses2do_classes_id_foreign');
					$this->forge->dropForeignKey('courses2do', 'courses2do_courses_id_foreign');


		
		
		
		
				}
		
				$this->forge->dropTable('courses2do', true);

	}
}
