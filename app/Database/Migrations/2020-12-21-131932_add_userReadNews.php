<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserReadNews extends Migration
{
	public function up()
	{
		$this->forge->addField([
            'news_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
			'users_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
			'read' => ['type' => 'tinyint', 'constraint' => 1, 'null' => 0, 'default' => 0],
        ]);


        $this->forge->addForeignKey('news_id', 'news', 'id', false, 'CASCADE');
		$this->forge->addForeignKey('users_id', 'users', 'id', false, 'CASCADE');
		
        $this->forge->createTable('userreadnews', true);
	}

	//--------------------------------------------------------------------

	public function down()
	{
		        // drop constraints first to prevent errors
				if ($this->db->DBDriver != 'SQLite3') {
					
					$this->forge->dropForeignKey('userreadnews', 'userreadnews_news_id_foreign');
					$this->forge->dropForeignKey('userreadnews', 'userreadnews_users_id_foreign');
		
				}
		
				$this->forge->dropTable('userreadnews', true);
				
	}
}
