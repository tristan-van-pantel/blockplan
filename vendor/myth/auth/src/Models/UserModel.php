<?php namespace Myth\Auth\Models;

use CodeIgniter\Model;
use Myth\Auth\Authorization\GroupModel;
use Myth\Auth\Entities\User;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';

    protected $returnType = User::class;
    protected $useSoftDeletes = true;

    protected $allowedFields = [
        'email', 'username', 'password_hash', 'reset_hash', 'reset_at', 'reset_expires', 'activate_hash',
        'status', 'status_message', 'active', 'force_pass_reset', 'permissions', 'deleted_at', 'classes_id',
        'firstname', 'lastname'
    ];

    protected $useTimestamps = true;

    protected $validationRules = [
        'email'         => 'required|valid_email|is_unique[users.email,id,{id}]',
        'username'      => 'required|alpha_numeric_punct|min_length[3]|is_unique[users.username,id,{id}]',
        'password_hash' => 'required',
    ];
    protected $validationMessages = [];
    protected $skipValidation = false;

    protected $afterInsert = ['addToGroup'];

    /**
     * The id of a group to assign.
     * Set internally by withGroup.
     * @var int
     */
    protected $assignGroup;

    /**
     * Logs a password reset attempt for posterity sake.
     *
     * @param string      $email
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    public function logResetAttempt(string $email, string $token = null, string $ipAddress = null, string $userAgent = null)
    {
        $this->db->table('auth_reset_attempts')->insert([
            'email' => $email,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Logs an activation attempt for posterity sake.
     *
     * @param string|null $token
     * @param string|null $ipAddress
     * @param string|null $userAgent
     */
    public function logActivationAttempt(string $token = null, string $ipAddress = null, string $userAgent = null)
    {
        $this->db->table('auth_activation_attempts')->insert([
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'token' => $token,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Sets the group to assign any users created.
     *
     * @param string $groupName
     *
     * @return $this
     */
    public function withGroup(string $groupName)
    {
        $group = $this->db->table('auth_groups')->where('name', $groupName)->get()->getFirstRow();

        $this->assignGroup = $group->id;

        return $this;
    }

    /**
     * Clears the group to assign to newly created users.
     *
     * @return $this
     */
    public function clearGroup()
    {
        $this->assignGroup = null;

        return $this;
    }

    /**
     * If a default role is assigned in Config\Auth, will
     * add this user to that group. Will do nothing
     * if the group cannot be found.
     *
     * @param $data
     *
     * @return mixed
     */
    protected function addToGroup($data)
    {
        if (is_numeric($this->assignGroup))
        {
            $groupModel = model(GroupModel::class);
            $groupModel->addUserToGroup($data['id'], $this->assignGroup);
        }

        return $data;
    }


    public function findAllStudentsByID(int $classesid = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->where('classes_id', $classesid)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }


    public function findAllActiveStudentsByClassId(int $classesid = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->where('classes_id', $classesid)->where('active', 1)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }

    public function findAllInactiveStudentsByClassId(int $classesid = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->where('classes_id', $classesid)->where('active', 0)
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }





    public function findClasslessStudents()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('*');
        $builder->join('auth_groups_users', 'users.id = auth_groups_users.user_id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->whereIn('auth_groups.name', ['students']);
        $builder->where('users.active', 1);
        $builder->where('classes_id', null);

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



    public function findActiveStudents()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, email, firstname, lastname, username, classes_id');
        $builder->join('auth_groups_users', 'users.id = auth_groups_users.user_id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->whereIn('auth_groups.name', ['students']);
        $builder->where('users.active', 1);

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

    public function findActiveLecturers()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, email, firstname, lastname, username, classes_id');
        $builder->join('auth_groups_users', 'users.id = auth_groups_users.user_id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->whereIn('auth_groups.name', ['lecturers']);
        $builder->where('users.active', 1);

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


    public function updateClassStudents($userarray, $classid) {
        $data = [
            'classes_id' => $classid,
        ];
//        $builder = $this->builder();
        if (!empty($userarray)) {
            $db = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->whereIn('id', $userarray);
            $builder->update($data);
            return $userarray;
        }
    }

    public function updateClasslessStudents($classless) {
        $data = [
            'classes_id' => null,
        ];
        if (!empty($classless)) {
            //        $builder = $this->builder();
            $db      = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->whereIn('id', $classless);
            $builder->update($data);
//        return $classless;

        }

    }

    public function updateClassesActiveStudents($user_id_array = null) {
        $data = [
            'active' => 1,
        ];
        if (!empty($user_id_array )) {
            //        $builder = $this->builder();
            $db      = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->whereIn('id', $user_id_array);
            $builder->where('id !=', 1);
            $builder->update($data);
//        return $classless;

        }

    }


    public function updateClassesInactiveStudents($user_id_array = null) {
        $data = [
            'active' => 0,
        ];
        if (!empty($user_id_array )) {
            //        $builder = $this->builder();
            $db      = \Config\Database::connect();
            $builder = $db->table('users');
            $builder->whereIn('id', $user_id_array);
            $builder->where('id !=', 1);
            $builder->update($data);
//        return $classless;

        }

    }





    public function findClassname($classes_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('name');
        $builder->join('users', 'users.classes_id = classes.id');
        $builder->where('classes_id', $classes_id);
        $builder->limit(1);

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



    public function findLecturers()
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('users.id, email, firstname, lastname, username, classes_id');
        $builder->join('auth_groups_users', 'users.id = auth_groups_users.user_id');
        $builder->join('auth_groups', 'auth_groups.id = auth_groups_users.group_id');
        $builder->whereIn('auth_groups.name', ['lecturers']);

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



    public function getClassIdByUserId($user_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('classes');
        $builder->select('classes.id, classes.name');
        $builder->join('users', 'users.classes_id = classes.id');
        $builder->where('users.id', $user_id);


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


    public function getUsername($user_id)
    {
        $db      = \Config\Database::connect();
        $builder = $db->table('users');
        $builder->select('username, firstname, lastname');
        $builder->where('id', $user_id);


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


    public function getLecturersCurrentCourse($classes_id) {
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





}
