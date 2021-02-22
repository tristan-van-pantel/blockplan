<?php namespace App\Models;

use CodeIgniter\Model;

class NewsModel extends Model
{
    protected $table      = 'news';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['news', 'users_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;

    
    public function insertClassesNews($classes_id, $news_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('classesnews');
        $builder->insert(['classes_id' => $classes_id,
                            'news_id' => $news_id,
                            ]
                        );

    }


    public function getNewsClasses($news_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('news');
        $builder->select('classes.id, classes.name');
        $builder->join('classesnews', 'classesnews.news_id = news.id');
        $builder->join('classes', 'classes.id = classesnews.classes_id');
        $builder->where('news.id', $news_id);

        
        


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
