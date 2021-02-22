<?php namespace App\Controllers;

use CodeIgniter\I18n\Time;

/**
 * VirtualClassroom allows students and lecturers to exchange information (text and images) for their current dateOfCourse ("Fachtermin/Kurstermin") if there is one.
 */
class VirtualClassroom extends BaseController
{    
    /**
     * index finds out the students or lecturers current dateOfCourse, finds all students and displays all posts made.
     *
     * @return void
     */
    public function index()
    {
        if (!in_groups('lecturers') && !in_groups(('students'))) {
            return redirect()->to('/');
        }

/* -------------------------------------------------------------------------- */
/*                               Lecturers Part                               */
/* -------------------------------------------------------------------------- */

        if ((!in_groups('students')) && in_groups('lecturers')) {
            $datesModel = model('DateOfCourseModel');
            $lecturers_id = user_id();
            // get lecturers current course or next course (if there is no current), if there is neither nor, then redirect back
            $lecturers_courses = $datesModel->getDateAndCourseByLecturersUserId($lecturers_id);
            if (empty($lecturers_courses)) {
                return redirect()->back();
            }
            $current_course = $lecturers_courses[0];
            // find all classes related to the date (of course), then, for each class, iterate though the students and put them all into an array
            $classes = $datesModel->findClassNameByDatesId($current_course->dates_id);
            if (!empty($classes)) {
                $students = [];
                foreach ($classes as $class) {
                    foreach (model('UserModel')->findAllStudentsByID($class->id) as $student) {
                        $students[] = $student;
                    }

                }

                $current_course->students = $students;
                $current_course->size = count($current_course->students);
                $current_course->lecturer = user();
                $current_course->roomname = $current_course->rooms_name;
                $current_course->name = $current_course->courses_name;
                // d($current_course->lecturer);

            }
            $userModel = model('UserModel');
            // get all posts that belong to the current date (of course) and order them descending plus pa<ginate them (5 per page)
            $datepostspages = model('DatepostModel')->where('dates_id', ($current_course)->dates_id)->orderBy('created_at', 'DESC')->paginate(5);
            foreach ($datepostspages as $post) {
                // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
                // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
                // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
                $year = date('Y', strtotime($post->created_at));
                $month = date('n', strtotime($post->created_at));
                $day = date('d', strtotime($post->created_at));
                $hour = date('G', strtotime($post->created_at));
                $minutes = date('i', strtotime($post->created_at));
                $seconds = date('s', strtotime($post->created_at));
                $timezone = 'Europe/Berlin';
                // $timezone = 'America/Chicago';
                $locale = 'de_DE';

                // add seven hours to get german time
                $post->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
                $post->time_ago = Time::now()->difference($post->time_ago)->humanize();
                $post->images = model('DatepostModel')->getImagesByPostId($post->id);

                $year = date('Y', strtotime($post->updated_at));
                $month = date('n', strtotime($post->updated_at));
                $day = date('d', strtotime($post->updated_at));
                $hour = date('G', strtotime($post->updated_at));
                $minutes = date('i', strtotime($post->updated_at));
                $seconds = date('s', strtotime($post->updated_at));
                $timezone = 'Europe/Berlin';
                // $timezone = 'America/Chicago';
                $locale = 'de_DE';

                $post->time_ago_updated = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
                $post->time_ago_updated = Time::now()->difference($post->time_ago_updated)->humanize();

            }
            $data = [
                'current_course' => $current_course,
                'classesModel' => model('ClassesModel'),
                // 'dateposts' => $dateposts,
                'datepostspages' => $datepostspages,
                'pager' => model('DatepostModel')->pager,
                'user_model' => model('UserModel'),
                'user_id' => $lecturers_id,
                'notice' => model('DateNoticeModel')->where('dates_id', $current_course->dates_id)->first(),
            ];
            // return d($data);
            return view('virtualclassroom/show', $data);
            // return d($lecturers_courses[0]);
        }

/* -------------------------------------------------------------------------- */
/*                                Students Part                               */
/* -------------------------------------------------------------------------- */

        //  now get the same information like above, but for the students of the dates (of course ) classes

        $classesModel = model('ClassesModel');
        $user_id = user_id();
        $userModel = model('UserModel');
        $user = model('UserModel')->find($user_id);
        $class_id = $user->classes_id;
        if (empty($classesModel->getClassesCurrentCourseAndDateForVirtualClassroom($class_id))) {
            return redirect()->back();
        }
        $current_course = $classesModel->getClassesCurrentCourseAndDateForVirtualClassroom($class_id)[0];
        $current_course->classes_in_room = model('DateOfCourseModel')->findClassNameByDatesId($current_course->dates_id);
        $class_ids = [];
        foreach ($current_course->classes_in_room as $class) {
            $class_ids[] = $class->id;
        }
        $students = [];
        if (!empty($class_ids)) {
            foreach ($class_ids as $classid) {
                foreach ($userModel->findAllStudentsByID($classid) as $student) {
                    $students[] = $student;
                }
                // $students[] = $userModel->findAllStudentsByID($classid);
            }
        }
        $current_course->students = $students;
        $current_course->size = count($current_course->students);
        $current_course->lecturer = $userModel->find($current_course->users_id);

        $datepostspages = model('DatepostModel')->where('dates_id', ($current_course)->dates_id)->orderBy('created_at', 'DESC')->paginate(5);
        foreach ($datepostspages as $post) {
            $year = date('Y', strtotime($post->created_at));
            $month = date('n', strtotime($post->created_at));
            $day = date('d', strtotime($post->created_at));
            $hour = date('G', strtotime($post->created_at));
            $minutes = date('i', strtotime($post->created_at));
            $seconds = date('s', strtotime($post->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $post->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $post->time_ago = Time::now()->difference($post->time_ago)->humanize();
            $post->images = model('DatepostModel')->getImagesByPostId($post->id);

            $year = date('Y', strtotime($post->updated_at));
            $month = date('n', strtotime($post->updated_at));
            $day = date('d', strtotime($post->updated_at));
            $hour = date('G', strtotime($post->updated_at));
            $minutes = date('i', strtotime($post->updated_at));
            $seconds = date('s', strtotime($post->updated_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $post->time_ago_updated = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $post->time_ago_updated = Time::now()->difference($post->time_ago_updated)->humanize();

        }
        $data = [
            'current_course' => $current_course,
            'classesModel' => $classesModel,
            // 'dateposts' => $dateposts,
            'datepostspages' => $datepostspages,
            'pager' => model('DatepostModel')->pager,
            'user_model' => model('UserModel'),
            'user_id' => $user_id,
            'notice' => model('DateNoticeModel')->where('dates_id', $current_course->dates_id)->first(),
        ];

        return view('virtualclassroom/show', $data);
    }
    
    /**
     * addclassroompost gets data from the form and wirtes the posts text into the database and saves the image(s) (if there are some) related to a post.
     *
     * @return void
     */
    public function addclassroompost()
    {
        if (!logged_in()) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'post' => 'required',
            'file' => [
                'mime_in[file,image/jpg,image/jpeg,image/gif,image/png]',
                'max_size[file,4096]',
                'is_image[file]',
            ],
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $dates_id = $this->request->getVar('date_id');
        $user_id = user_id();
        $post = $this->request->getVar('post');
        $post_id = model('DatepostModel')->insert(['post' => $post, 'user_id' => $user_id, 'dates_id' => $dates_id]);
        $images = $this->request->getFiles('file');
        // return d($images);
        foreach ($images['file'] as $image) {
            if ($image->isValid() && !$image->hasMoved()) {
                $newName = $image->getRandomName();
                $image->move(ROOTPATH . 'public/assets/uploads', $newName);
                $data = [
                    'name' => $image->getName(),
                    'type' => $image->getClientMimeType(),
                    'dates_id' => $dates_id,
                    'user_id' => $user_id,
                ];
                // insertDatepostsImages($dateposts_id, $filename, $filetype) : void
                model('DatepostModel')->insertDatepostsImages($post_id, $image->getName(), $image->getClientMimeType());
            }
        }

        return redirect()->back();

    }
    
    /**
     * deletedatepost deletes a seöected post.
     *
     * @return void
     */
    public function deletedatepost()
    {
        if (!logged_in()) {
            return redirect()->back();
        }
        $post_id = $this->request->getVar('post_id');
        $post_images = model('DatepostModel')->getImagesByPostId($post_id);
        // first delete the images in the public direcory..
        if (!empty($post_images)) {
            foreach ($post_images as $image) {
                // d(ROOTPATH.'public/assets/uploads/'.$image->filename);
                unlink(ROOTPATH . 'public/assets/uploads/' . $image->filename);
            }
        }
        //.. and then the database containing the images information
        model('DatepostModel')->delete($post_id);

        return redirect()->back();

    }
    
    /**
     * editdatepost gets all data of a selected post and redirects to the edit-post form.
     *
     * @param  mixed $post_id
     * @return void
     */
    public function editdatepost($post_id = null)
    {
        if (!logged_in() || empty($post_id)) {
            return redirect()->back();
        }
        // ensure there is a valid post_id and that only the creator can edit
        if (empty(model('DatepostModel')->find($post_id)) || (model('DatepostModel')->find($post_id))->user_id !== user_id()) {
            return redirect()->back();
        }
        $post = model('DatepostModel')->find($post_id);
        $post->images = model('DatepostModel')->getImagesByPostId($post->id);
        $data = [
            'post' => $post,
        ];
        return view('virtualclassroom/editdatepost', $data);
    }
    
    /**
     * insertdatepostedit updates the database with all changes made to a selected post.
     *
     * @return void
     */
    public function insertdatepostedit()
    {
        if (!logged_in()) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'post' => 'required',
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $post_id = $this->request->getVar('post_id');
        $post = $this->request->getVar('post');
        $dates_id = model('DatepostModel')->find($post_id)->dates_id;

        $data = [
            'post' => $post,
            'dates_id' => $dates_id,
        ];

        model('DatepostModel')->update($post_id, $data);
        return redirect()->back();
        return d($post_id,
            $post,
            $dates_id);

    }
    
    /**
     * datenotice allows lecturers to publish a "datenotice" in the virtual classrom which will be displayed above all posts. usefull for important information like homework.
     *
     * @return void
     */
    public function datenotice()
    {
        if (!logged_in() || !in_groups('lecturers')) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'notice' => 'required',
        ]);
        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $dates_id = $this->request->getVar('date_id');
        $notice = $this->request->getVar('notice');
        $notice_id = $this->request->getVar('notice_id');
        $data = [];
        // if there already is a notice, then update, else insert
        if (empty($notice_id)) {
            $data = [
                'dates_id' => $dates_id,
                'notice' => $notice,
            ];
        } else {
            $data = [
                'id' => $notice_id,
                'dates_id' => $dates_id,
                'notice' => $notice,
            ];
        }
        model('DateNoticeModel')->save($data);
        return redirect()->back();
    }

}
