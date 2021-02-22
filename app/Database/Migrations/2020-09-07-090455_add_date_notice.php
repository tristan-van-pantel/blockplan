<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDateNotice extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'notice'            => ['type' => 'TEXT'],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
			'dates_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
		$this->forge->addForeignKey('dates_id', 'datesofcourse', 'id', false, 'CASCADE');
		

        $this->forge->createTable('datenotice', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
				// drop constraints first to prevent errors
				if ($this->db->DBDriver != 'SQLite3')
				{
					$this->forge->dropForeignKey('datenotice', 'datenotice_dates_id_foreign');
					
		
				}
		
				$this->forge->dropTable('dateposts', true);
	}
}