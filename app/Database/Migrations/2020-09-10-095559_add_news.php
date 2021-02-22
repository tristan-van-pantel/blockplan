<?php namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddNews extends Migration
{
    public function up()
    {

/* -------------------------------------------------------------------------- */
/*                              create news table                             */
/* -------------------------------------------------------------------------- */

        $this->forge->addField([
            'id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'news' => ['type' => 'TEXT'],
            'created_at' => ['type' => 'datetime', 'null' => true],
            'updated_at' => ['type' => 'datetime', 'null' => true],
            'deleted_at' => ['type' => 'datetime', 'null' => true],
            'users_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('users_id', 'users', 'id', false, 'CASCADE');

        $this->forge->createTable('news', true);

/* -------------------------------------------------------------------------- */
/*                      create classesnews relation table                     */
/* -------------------------------------------------------------------------- */
        $this->forge->addField([
            'classes_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'news_id' => ['type' => 'int', 'constraint' => 11, 'unsigned' => true, 'null' => true],
        ]);


        $this->forge->addForeignKey('classes_id', 'classes', 'id', false, 'CASCADE');
        $this->forge->addForeignKey('news_id', 'news', 'id', false, 'CASCADE');

        $this->forge->createTable('classesnews', true);
    }

    //--------------------------------------------------------------------

    public function down()
    {
        // drop constraints first to prevent errors
        if ($this->db->DBDriver != 'SQLite3') {
            $this->forge->dropForeignKey('news', 'news_users_id_foreign');
            $this->forge->dropForeignKey('classesnews', 'classesnews_classes_id_foreign');
            $this->forge->dropForeignKey('classesnews', 'classesnews_news_id_foreign');

        }

		$this->forge->dropTable('news', true);
        $this->forge->dropTable('classesnews', true);
		
    }
}
