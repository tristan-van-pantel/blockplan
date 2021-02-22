<?php namespace App\Models;

use CodeIgniter\Model;

class RoomModel extends Model
{
    protected $table      = 'rooms';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name', 'capacity', 'installed_equipment'];

    protected $useTimestamps = false;


    protected $validationRules    = ['name' => 'required|max_length[20]|is_unique[rooms.name]',
    'capacity' => 'permit_empty|less_than[30]',
    'installed_equipment' => 'permit_empty|max_length[255]',

];
    protected $validationMessages = [];
    protected $skipValidation     = true;
}
