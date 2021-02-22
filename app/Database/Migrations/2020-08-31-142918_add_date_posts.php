<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddDatePosts extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'post'            => ['type' => 'TEXT'],
            'created_at'       => ['type' => 'datetime', 'null' => true],
            'updated_at'       => ['type' => 'datetime', 'null' => true],
            'deleted_at'       => ['type' => 'datetime', 'null' => true],
			'dates_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
			'user_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
		$this->forge->addForeignKey('dates_id', 'datesofcourse', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('user_id', 'users', 'id', false, 'CASCADE');
		

        $this->forge->createTable('dateposts', true);

	}

	//--------------------------------------------------------------------

	public function down()
	{

		// drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3')
        {
			$this->forge->dropForeignKey('dateposts', 'dateposts_dates_id_foreign');
            $this->forge->dropForeignKey('dateposts', 'dateposts_user_id_foreign');
			

        }

		$this->forge->dropTable('dateposts', true);
}
}