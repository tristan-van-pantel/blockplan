<?php namespace App\Models;

use CodeIgniter\Model;

class VacationModel extends Model
{
    protected $table      = 'vacations';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['begin', 'end', 'name'];

    protected $useTimestamps = false;


    protected $validationRules    = [
                                    'begin' => 'required|valid_date',
                                    'end' => 'required|valid_date',
                                    'name' => 'required|max_length[255]',

    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function insertclassesvacations($classes_id, $vacations_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classesvacations');
        $builder->insert(['classes_id' => $classes_id,
                            'vacations_id' => $vacations_id]
                        );

    }



    public function findClassByVacationId($vacations_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id, classes.name');
        $builder->join('classesvacations', 'classes.id = classesvacations.classes_id');
        $builder->join('vacations', 'vacations.id = classesvacations.vacations_id');
        $builder->where('classesvacations.vacations_id', $vacations_id);


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


    public function getClassesVacation($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id AS classes_id, vacations.id AS vacations_id, vacations.name AS vacations_name, classesvacations.begin AS vacations_begin, classesvacations.end AS vacations_end');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->join('classesvacations', 'classesvacations.classes_id = classes.id');
        $builder->join('vacations', 'classesvacations.vacations_id = vacations.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('classesvacations.begin >=', date('Y-m-d H:i:s'));
        
        


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


    public function deleteclassesvacations($classes_id, $vacations_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classesvacations');
        $builder->where('classes_id', $classes_id);
        $builder->where('vacations_id', $vacations_id);
        $builder->delete();

    }






}
