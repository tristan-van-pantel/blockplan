<?php namespace App\Models;

use CodeIgniter\Model;

class DateOfCourseModel extends Model
{
    protected $table = 'datesofcourse';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['begin', 'end', 'rooms_id', 'courses_id', 'users_id'];

    protected $useTimestamps = false;

    protected $validationRules = [
        'begin' => 'required|valid_date',
        'end' => 'required|valid_date',
        'rooms_id' => 'required|is_natural_no_zero',
        'courses_id' => 'required|is_natural_no_zero',
        'users_id' => 'required|is_natural_no_zero',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    public function insertclassesdatesofcourse($classes_id, $datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classesdatesofcourse');
        $builder->insert(['classes_id' => $classes_id,
            'datesofcourse_id' => $datesofcourse_id]
        );

    }

    public function findClassIdByDatesId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classesdatesofcourse');
        $builder->select('classes_id');
        $builder->where('datesofcourse_id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function findClassNameByDatesId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('name, classes.id, enrolled_students, datesofcourse.begin, datesofcourse.end');
        $builder->join('classesdatesofcourse', 'classes.id = classesdatesofcourse.classes_id');
        $builder->join('datesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->where('datesofcourse_id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function sumClassesSizeByDateId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->selectSum('enrolled_students');
        $builder->join('classesdatesofcourse', 'classes.id = classesdatesofcourse.classes_id');
        $builder->join('datesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->where('datesofcourse_id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function findCourseNameByDatesId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('courses');
        $builder->select('name, courses.id');
        $builder->join('datesofcourse', 'courses.id = datesofcourse.courses_id');
        $builder->where('datesofcourse.id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getRoomByDatesId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rooms');
        $builder->select('name, capacity, installed_equipment');
        $builder->join('datesofcourse', 'rooms.id = datesofcourse.rooms_id');
        $builder->where('datesofcourse.id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getLecturerByDatesId($datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, username, firstname, users.lastname');
        $builder->join('datesofcourse', 'users.id = datesofcourse.users_id');
        $builder->where('datesofcourse.id', $datesofcourse_id);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedRoomsByBeginEndDates($begin, $end)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rooms');
        $builder->select('rooms.id, name');
        $builder->join('datesofcourse', 'rooms.id = datesofcourse.rooms_id');
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedRoomsByBeginEndDatesExceptOld($begin, $end, $olddate)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('rooms');
        $builder->select('rooms.id, name');
        $builder->join('datesofcourse', 'rooms.id = datesofcourse.rooms_id');
        $builder->where('datesofcourse.id !=', $olddate);
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);
        $builder->where('datesofcourse.id !=', $olddate);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedLecturersByBeginEndDates($begin, $end)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, username, users.firstname, users.lastname, internal_id');
        $builder->join('datesofcourse', 'users.id = datesofcourse.users_id');
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedClassesByBeginEndDates($begin, $end)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id, classes.name, classes.internal_id, classes.begin, classes.end');
        $builder->join('classesdatesofcourse', 'classes.id = classesdatesofcourse.classes_id');
        $builder->join('datesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedClassesByBeginEndDatesExceptOld($begin, $end, $olddate)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id, classes.name, classes.internal_id, classes.begin AS classbegin, classes.end AS classend, datesofcourse.id AS dateId');
        $builder->join('classesdatesofcourse', 'classes.id = classesdatesofcourse.classes_id');
        $builder->join('datesofcourse', 'classesdatesofcourse.datesofcourse_id = datesofcourse.id');
        $builder->where('datesofcourse.id !=', $olddate);
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);
        $builder->where('datesofcourse.id !=', $olddate);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

//         $sql = $builder->getCompiledSelect();
        //        $query = $db->query($sql);
        //        foreach ($query->getResult() as $row)
        // {
        // d($row);
        // }
        //   return d($sql);

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getOccupiedLecturersByBeginEndDatesExceptOld($begin, $end, $olddate)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, username, users.firstname, users.lastname, internal_id');
        $builder->join('datesofcourse', 'users.id = datesofcourse.users_id');
        $builder->where('datesofcourse.id !=', $olddate);
        $builder->where('datesofcourse.begin <=', $begin);
        $builder->where('datesofcourse.end >=', $begin);
        $builder->orWhere('datesofcourse.begin <=', $end);
        $builder->where('datesofcourse.end >=', $end);
        $builder->where('datesofcourse.id !=', $olddate);

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getDateAndCourseByLecturersUserId($lecturers_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('datesofcourse');
        $builder->select('datesofcourse.begin, datesofcourse.end,  datesofcourse.id AS dates_id, courses.name AS courses_name, rooms.name AS rooms_name');
        $builder->join('courses', 'datesofcourse.courses_id = courses.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('datesofcourse.users_id', $lecturers_id);
        $builder->where('datesofcourse.end >=', date('Y-m-d H:i:s'));

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function getDateAndCourseByLecturersUserIdCurrent($lecturers_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('datesofcourse');
        $builder->select('datesofcourse.begin, datesofcourse.end,  datesofcourse.id AS dates_id, courses.name AS courses_name, rooms.name AS rooms_name');
        $builder->join('courses', 'datesofcourse.courses_id = courses.id');
        $builder->join('rooms', 'datesofcourse.rooms_id = rooms.id');
        $builder->where('datesofcourse.users_id', $lecturers_id);
        $builder->where('datesofcourse.end >=', date('Y-m-d H:i:s'));
        $builder->where('datesofcourse.begin <=', date('Y-m-d H:i:s'));

        if ($this->tempUseSoftDeletes === true) {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];

    }

    public function deleteClassesDates($classes_id, $datesofcourse_id)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('classesdatesofcourse');
        $builder->where('classes_id', $classes_id);
        $builder->where('datesofcourse_id', $datesofcourse_id);
        $builder->delete();

    }

    public function saveDateNotice($id = null, $dates_id = null, $notice = null)
    {
        $db = \Config\Database::connect();
        $builder = $db->table('datenotice');
        $data = [];
        if (empty($id)) {
            $data = [
                'dates_id' => $dates_id,
                'notice' => $notice,
            ];
            $builder->insert($data);
        } else {
            $data = [
                'id' => $id,
                'dates_id' => $dates_id,
                'notice' => $notice,
            ];
            $builder->update($data);
        }

    }

}
