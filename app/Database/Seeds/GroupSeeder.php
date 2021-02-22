<?php


namespace App\Database\Seeds;


class GroupSeeder extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = [
            'name' => 'lecturers',

        ];

        // Simple Queries
        $this->db->query("INSERT INTO auth_groups (name) VALUES(:name:), (:name:), (:name:)",
            $data
        );

        // Using Query Builder
        $this->db->table('users')->insert($data);




    }
}