<?php namespace App\Models;

use CodeIgniter\Model;

class ClassesModel extends Model
{
    protected $table      = 'classes';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['name', 'internal_id', 'begin', 'end', 'enrolled_students'];

    protected $useTimestamps = false;


    protected $validationRules    = ['name' => 'required',
                                    'begin' => 'required|valid_date',
                                    'end' => 'required|valid_date',
                                    'enrolled_students' => 'permit_empty|is_natural',

    ];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    
    /**
     * findAllDesc finds all classes. result is descending.
     *
     * @param  mixed $limit
     * @param  mixed $offset
     * @return void
     */
    public function findAllDesc(int $limit = 0, int $offset = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->limit($limit, $offset)->orderBy('end', 'DESC')
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row, 'limit' => $limit, 'offset' => $offset]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }

    
    /**
     * getClassesCurrentCourseAndDate takes the classes_id returns the classes current date of course. 
     *
     * @param  mixed $classes_id
     * @return [courses.id, courses.name, datesofcourse.begin, datesofcourse.end,  datesofcourse.id AS dates_id, datesofcourse.users_id, rooms.name AS roomname]
     */
    public function getClassesCurrentCourseAndDate($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('courses.id, courses.name, datesofcourse.begin, datesofcourse.end,  datesofcourse.id AS dates_id, datesofcourse.users_id, rooms.name AS roomname');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->join('classesdatesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->join('classes', 'classesdatesofcourse.classes_id = classes.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('datesofcourse.end >=', date('Y-m-d H:i:s'));
        $builder->Where('datesofcourse.begin <=', date('Y-m-d H:i:s'));
        
        


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


    public function getClassesCurrentCourseAndDateForVirtualClassroom($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('courses.id, courses.name, datesofcourse.begin, datesofcourse.end,  datesofcourse.id AS dates_id, datesofcourse.users_id, rooms.name AS roomname');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->join('classesdatesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->join('classes', 'classesdatesofcourse.classes_id = classes.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('datesofcourse.end >=', date('Y-m-d H:i:s', strtotime("today", time()) ));
        $builder->Where('datesofcourse.begin <=', date('Y-m-d H:i:s'));
        


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

    
    /**
     * getClassesFutureCourseAndDate takes the classes_id and returns all datesOdCourses ("Fachtermine/Kurstermine") which start after the current day.
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getClassesFutureCourseAndDate($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('courses.id, courses.name, datesofcourse.begin, datesofcourse.id AS dates_id, datesofcourse.end, datesofcourse.users_id, rooms.name AS roomname');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->join('classesdatesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->join('classes', 'classesdatesofcourse.classes_id = classes.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('datesofcourse.begin >=', date('Y-m-d H:i:s'));
        $builder->orderBy('datesofcourse.begin', 'ASC');
        
        


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

    
    /**
     * getClassesVacation takes the classes_id and returns all vacations associated with the class which begin at least today or in the future.
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getClassesVacation($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id AS classes_id, vacations.id AS vacations_id, vacations.name AS vacations_name, vacations.begin AS vacations_begin, vacations.end AS vacations_end');
        $builder->join('classesvacations', 'classesvacations.classes_id = classes.id');
        $builder->join('vacations', 'classesvacations.vacations_id = vacations.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('vacations.begin >=', date('Y-m-d H:i:s'));
        
        


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

    
    /**
     * getAllClassesVacation takes the classes_id and returns all vacations associated with the class.
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getAllClassesVacation($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id AS classes_id, vacations.id AS vacations_id, vacations.name AS vacations_name, vacations.begin AS vacations_begin, vacations.end AS vacations_end');
        $builder->join('classesvacations', 'classesvacations.classes_id = classes.id');
        $builder->join('vacations', 'classesvacations.vacations_id = vacations.id');
        $builder->where('classes.id', $classes_id);
        
        


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


    
    /**
     * getClassesVisitedCourses takes the classes_id and returns all PAST datesOfCourses.
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getClassesVisitedCourses($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('courses.id, courses.name, datesofcourse.begin, datesofcourse.id AS dates_id, datesofcourse.end, datesofcourse.users_id, rooms.name AS roomname');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->join('classesdatesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->join('classes', 'classesdatesofcourse.classes_id = classes.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('classes.id', $classes_id);
        $builder->where('datesofcourse.end <=', date('Y-m-d H:i:s'));
        
        


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


    
    
    /**
     * getClassesTodoCourses takes the classes_id and returns all its courses2do ("Pflichtfächer/Pflichtkurse").
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getClassesTodoCourses($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses2do');
        $builder->select('courses.id, courses.name');
        $builder->join('courses', 'courses.id = courses2do.courses_id');
        $builder->where('courses2do.classes_id', $classes_id);
        
        


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

    
    /**
     * insertClassesCourses2doCourses takes the classes_id and the courses_id and inserts them into the courses2do-table.
     *
     * @param  mixed $classes_id
     * @param  mixed $courses_id
     * @return void
     */
    public function insertClassesCourses2doCourses($classes_id, $courses_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses2do');
        $builder->insert(['classes_id' => $classes_id,
                            'courses_id' => $courses_id]
                        );

    }

    
    /**
     * deleteClassesCourses2do takes the classes_id(PK, FK) and the courses_id(PK, FK) and deltes the row from the courses2do table.
     *
     * @param  mixed $classes_id
     * @param  mixed $courses_id
     * @return void
     */
    public function deleteClassesCourses2do($classes_id, $courses_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('courses2do');
        $builder->where('classes_id', $classes_id);
        $builder->where('courses_id', $courses_id);
        $builder->delete();

    }
    
    /**
     * getClassesNews takes the classes_id and returns all news associated with the class.
     *
     * @param  mixed $classes_id
     * @return void
     */
    public function getClassesNews($classes_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('news.id, news, news.users_id, news.created_at, news.updated_at');
        $builder->join('classesnews', 'classesnews.classes_id = classes.id');
        $builder->join('news', 'news.id = classesnews.news_id');
        $builder->where('classes.id', $classes_id);

        
        


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
