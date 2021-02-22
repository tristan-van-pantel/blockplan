<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPostsImages extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'id'               => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
			'dateposts_id'         => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
			'filename'      => ['type' => 'varchar', 'constraint' => 255],
			'filetype'      => ['type' => 'varchar', 'constraint' => 255],
        ]);

        $this->forge->addKey('id', true);
		$this->forge->addForeignKey('dateposts_id', 'dateposts', 'id', false, 'CASCADE');


        $this->forge->createTable('postsimages', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
				// drop constraints first to prevent errors
				if ($this->db->DBDriver != 'SQLite3')
				{
					$this->forge->dropForeignKey('postsimages', 'postsimages_dateposts_id_foreign');

					
		
				}
		
				$this->forge->dropTable('dateposts', true);
	}
}
