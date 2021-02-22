<?php namespace App\Models;

use CodeIgniter\Model;

class DatepostModel extends Model
{
    protected $table      = 'dateposts';
    protected $primaryKey = 'id';

    protected $returnType = 'object';
    protected $useSoftDeletes = false;

    protected $allowedFields = ['post', 'user_id', 'dates_id'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    protected $validationRules    = [];
    protected $validationMessages = [];
    protected $skipValidation     = false;


    public function findDatesPosts(int $dates_id = 0)
    {
        $builder = $this->builder();

        if ($this->tempUseSoftDeletes === true)
        {
            $builder->where($this->table . '.' . $this->deletedField, null);
        }

        $row = $builder->where('dates_id', $dates_id)->orderBy('created_at', 'DESC')
            ->get();

        $row = $row->getResult($this->tempReturnType);

        $eventData = $this->trigger('afterFind', ['data' => $row]);

        $this->tempReturnType     = $this->returnType;
        $this->tempUseSoftDeletes = $this->useSoftDeletes;

        return $eventData['data'];
    }



    public function insertDatepostsImages($dateposts_id, $filename, $filetype) {
        $db      = \Config\Database::connect();
        $builder = $db->table('postsimages');
        $builder->insert(['dateposts_id' => $dateposts_id,
                            'filename' => $filename,
                            'filetype' => $filetype,
                            ]
                        );

    }



    public function getImagesByPostId($datepost_id) {
        $db      = \Config\Database::connect();
        $builder = $db->table('postsimages');
        $builder->select('filename, filetype');
        $builder->join('dateposts', 'dateposts.id = postsimages.dateposts_id');
        $builder->where('postsimages.dateposts_id', $datepost_id);


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

    // public function deletePostsImages($classes_id, $datesofcourse_id) {
    //     $db      = \Config\Database::connect();
    //     $builder = $db->table('classesdatesofcourse');
    //     $builder->where('classes_id', $classes_id);
    //     $builder->where('datesofcourse_id', $datesofcourse_id);
    //     $builder->delete();

    // }

}
