<?php namespace App\Models;

use CodeIgniter\Model;

class NotificationOfIllnessModel extends Model
{
    protected $table      = 'noticication_of_illness';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['begin', 'open', 'users_id', 'intime', 'end'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function getUsersOpenNotificationOfIllness($users_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, open, noticication_of_illness.created_at');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('noticication_of_illness.users_id', $users_id);
        $builder->where('open', 1);
        
        


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

    public function getUsersUnexcusedNotifications($users_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, end');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('users.id', $users_id);
        $builder->where('open', false);
        $builder->where('intime', false);


        
        


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



    public function getUsersCompletedNotifications($users_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, end, intime');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('users.id', $users_id);
        $builder->where('open', false);;


        
        


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


    public function insertNotificationsHealthCertificate($notifications_id, $filename, $filetype) {
        $db      = \Config\Database::connect();
        helper('date');
        $builder = $db->table('health_certificates');
        $builder->insert(['noticication_of_illness_id' => $notifications_id,
                            'filename' => $filename,
                            'filetype' => $filetype,
                            'created_at' => date("Y-m-d H:i:s"),
                            ]
                        );

    }

    public function insertNotificationsIllnessForm($notifications_id, $filename, $filetype) {
        $db      = \Config\Database::connect();
        helper('date');
        $builder = $db->table('illness_form');
        $builder->insert(['noticication_of_illness_id' => $notifications_id,
                            'filename' => $filename,
                            'filetype' => $filetype,
                            'created_at' => date("Y-m-d H:i:s"),
                            ]
                        );

    }


    public function getNotificationsHealthCertificates($notification_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('health_certificates.id, filetype, filename, health_certificates.created_at');
        $builder->join('health_certificates', 'noticication_of_illness.id = health_certificates.noticication_of_illness_id');
        $builder->where('health_certificates.noticication_of_illness_id', $notification_id);
        
        


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

    public function getNotificationsIllnessForms($notification_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('illness_form.id, filetype, filename, illness_form.created_at');
        $builder->join('illness_form', 'noticication_of_illness.id = illness_form.noticication_of_illness_id');
        $builder->where('illness_form.noticication_of_illness_id', $notification_id);
        
        


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

    public function getOpenNotificationsOfIllness() {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, open, noticication_of_illness.created_at, username, firstname, lastname, users.id AS user_id');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('open', 1);
        
        


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


    public function getExcusedNotificationsOfIllness() {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, end, open, noticication_of_illness.created_at, username, firstname, lastname, users.id AS user_id');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('open', 0);
        $builder->where('intime', 1);
        
        


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


    public function getUnexcusedNotificationsOfIllness() {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('noticication_of_illness.id, begin, end, open, noticication_of_illness.created_at, username, firstname, lastname, users.id AS user_id');
        $builder->join('users', 'noticication_of_illness.users_id = users.id');
        $builder->where('open', 0);
        $builder->where('intime', 0);
        
        


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




    public function isOpen($notification_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('noticication_of_illness');
        $builder->select('open');
        $builder->where('id', $notification_id);
        
        


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
