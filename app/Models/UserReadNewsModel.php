<?php namespace App\Models;

use CodeIgniter\Model;

class UserReadNewsModel extends Model
{
    protected $table      = 'userreadnews';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['news_id', 'users_id', 'read'];

    protected $useTimestamps = false;


    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    public function insertNotificationsIllnessForm($news_id, $users_id, $read) {
        $db      = \Config\Database::connect();
        // helper('date');
        $builder = $db->table('userreadtable');
        $builder->insert(['news_id' => $news_id,
                            'users_id' => $filename,
                            'read' => $read,
                            ]
                        );

    }

    public function getUsersUnreadNews($users_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('userreadnews');
        $builder->select('*');
        $builder->where('users_id', $users_id);
        $builder->where('read', false);


        
        


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

    public function getNewsReadStatus($users_id, $news_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('userreadnews');
        $builder->select('read');
        $builder->where('users_id', $users_id);
        $builder->where('news_id', $news_id);


        
        


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