<?php namespace App\Models;

use CodeIgniter\Model;

class CoursesModel extends Model
{
    protected $table      = 'courses';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name', 'internal_id'];

    protected $useTimestamps = false;

    protected $validationRules    = ['name' => 'required',
    'internal_id' => 'required',

];
    protected $validationMessages = [];
    protected $skipValidation     = false;
}
