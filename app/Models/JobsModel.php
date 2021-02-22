<?php namespace App\Models;

use CodeIgniter\Model;

class JobsModel extends Model
{
    protected $table      = 'jobs';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['jobs', 'users_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    
    public function insertClassesJobs($classes_id, $jobs_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classesjobs');
        $builder->insert(['classes_id' => $classes_id,
                            'jobs_id' => $jobs_id,
                            ]
                        );

    }


    public function getJobsClasses($jobs_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('jobs');
        $builder->select('classes.id, classes.name');
        $builder->join('classesjobs', 'classesjobs.jobs_id = jobs.id');
        $builder->join('classes', 'classes.id = classesjobs.classes_id');
        $builder->where('jobs.id', $jobs_id);

        
        


        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];  

    }


}
