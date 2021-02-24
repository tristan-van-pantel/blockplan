<?php namespace App\Controllers;

use CodeIgniter\I18n\Time;

/**
 * News enables the admins to publish news for selected classes. It also enables students to see the news related to their class.
 */
class News extends BaseController
{    
    /**
     * index shows the all news related to a students class.
     *
     * @return void
     */
    public function index()
    {
        // if (!in_groups('students')) {
        //     return redirect()->back();
        // }
        //verify that user has got a class, else redirect
        $class = (!empty(model('UserModel')->getClassIdByUserId(user_id()))) ? (model('UserModel')->getClassIdByUserId(user_id())[0]) : (null);
        if (empty($class)) {
            return redirect()->back();
        }
        // $news = model('ClassesModel')->getClassesNews($class->id);
        // get all news related to users schoolclass
        $news = model('ClassesModel')->select('news.id, news, news.users_id, news.created_at, news.updated_at')
            ->join('classesnews', 'classesnews.classes_id = classes.id')
            ->join('news', 'news.id = classesnews.news_id')
            ->where('classes.id', $class->id)
            ->orderBy('created_at', 'DESC')->paginate(5);
        // get all time values for the each news, like when created, updated and get these values i a human readable format
        foreach ($news as $item) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($item->created_at));
            $month = date('n', strtotime($item->created_at));
            $day = date('d', strtotime($item->created_at));
            $hour = date('G', strtotime($item->created_at));
            $minutes = date('i', strtotime($item->created_at));
            $seconds = date('s', strtotime($item->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $item->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $item->time_ago = Time::now()->difference($item->time_ago)->humanize();

            $year = date('Y', strtotime($item->updated_at));
            $month = date('n', strtotime($item->updated_at));
            $day = date('d', strtotime($item->updated_at));
            $hour = date('G', strtotime($item->updated_at));
            $minutes = date('i', strtotime($item->updated_at));
            $seconds = date('s', strtotime($item->updated_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $item->time_ago_updated = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $item->time_ago_updated = Time::now()->difference($item->time_ago_updated)->humanize();
            $item->read = (!empty(model('UserReadNewsModel')->getNewsReadStatus(user_id(), $item->id)) ) ? (model('UserReadNewsModel')->getNewsReadStatus(user_id(), $item->id)[0]->read) : (NULL);  //getNewsReadStatus($users_id, $news_id) 



            
        }
        $data = [
            'class' => $class,
            'user' => model('UserModel'),
            'news' => $news,
            'news_model' => model('NewsModel'),
            'user' => model('UserModel'),
            'pager' => model('ClassesModel')->pager,
            // 'read' => count(model('UserReadNewsModel')->getUsersUnreadNews(user_id() ) ),
        ];
        
        return view('news/shownews', $data);
    }
    
    /**
     * create gives the admin a form to create a new news for sselected classes and also displays all news ever created.
     *
     * @return void
     */
    public function create()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $news = model('NewsModel')->orderBy('created_at', 'DESC')->paginate(5);
        foreach ($news as $item) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($item->created_at));
            $month = date('n', strtotime($item->created_at));
            $day = date('d', strtotime($item->created_at));
            $hour = date('G', strtotime($item->created_at));
            $minutes = date('i', strtotime($item->created_at));
            $seconds = date('s', strtotime($item->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $item->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $item->time_ago = Time::now()->difference($item->time_ago)->humanize();

            $year = date('Y', strtotime($item->updated_at));
            $month = date('n', strtotime($item->updated_at));
            $day = date('d', strtotime($item->updated_at));
            $hour = date('G', strtotime($item->updated_at));
            $minutes = date('i', strtotime($item->updated_at));
            $seconds = date('s', strtotime($item->updated_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $item->time_ago_updated = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);
            $item->time_ago_updated = Time::now()->difference($item->time_ago_updated)->humanize();
        }
        $data = [
            'classes' => model('ClassesModel')->findAll(),
            'news' => $news,
            'pager' => model('NewsModel')->pager,
            'user' => model('UserModel'),
            'news_model' => model('NewsModel'),
            // 'lecturers' => model('UserModel')->findLecturers(),
        ];
        return view('news/createnews', $data);
    }
    
    /**
     * insertnews inserts a new news into the database (table "news) and also inserts into the classes2news assiciation table.
     *
     * @return void
     */
    public function insertnews()
    {
        if (!logged_in() || !in_groups('admins')) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'news' => 'required',
        ]);
        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $news = $this->request->getVar('news');
        $classes = $this->request->getVar('classes');
        $old_news = $this->request->getVar('news_id');
        $user = user_id();
        // if there is no old-news, then insert, else update
        if (empty($old_news)) {
            $data = [
                'news' => $news,
                'users_id' => $user,
            ];
            $news_id = model('NewsModel')->insert($data, true);
            // insert into classesnews relation table
            if (!empty($classes)) {
                foreach ($classes as $classes_id) {
                    // insertClassesNews($classes_id, $news_id) : void
                    model('NewsModel')->insertClassesNews($classes_id, $news_id);

                    
                    foreach (model('UserModel')->findAllActiveStudentsByClassId($classes_id) as $student_in_class) {
                        // $student_in_class->id gives the user_id of each individual student
                        $data = [
                            'news_id' => $news_id,
                            'users_id' => $student_in_class->id,
                            'read' => 0,
                        ];
                        model('UserReadNewsModel')->insert($data);
                    }
                    
                }
            }
        } else {
            $data = [
                'id' => $old_news,
                'news' => $news,
                'users_id' => $user,
            ];
            $news_id = model('NewsModel')->save($data);
            // insert into classesnews relation table
            if (!empty($classes)) {
                foreach ($classes as $classes_id) {
                    // insertClassesNews($classes_id, $news_id) : void
                    model('NewsModel')->insertClassesNews($classes_id, $news_id);
                    foreach (model('UserModel')->findAllActiveStudentsByClassId($classes_id) as $student_in_class) {
                        d('userreadnews');
                    }
                }
            }
        }

        return redirect()->back()->with('message', 'News successfully published');
    }
    
    /**
     * deletenews deletes a selected news.
     *
     * @return void
     */
    public function deletenews()
    {
        if (!logged_in() || !in_groups('admins')) {
            return redirect()->back();
        }
        $news_id = $this->request->getVar('news_id');
        // $classes = (!empty($news_id)) ? (model('NewsModel')->getNewsClasses($news_id)) : (null);
        // if (!empty($classes)) {
        //    foreach ($classes as $class) {
               
        //    }
        // }
        model('NewsModel')->delete($news_id);
        return redirect()->back();
    }

    public function getUsersUnreadNews() {
        if (!logged_in() || !in_groups('students')) {
            return;
        }
        $count =count(model('UserReadNewsModel')->getUsersUnreadNews(user_id() ) );
        

        
        return '' . $count;

    }

    public function markNewsAsRead() {

      $request = service('request');
      $postData = $request->getPost();
      $news_id = $postData['username'];
      $hash = $postData['[csrfName]'];
      $user_id = user_id();

      //update readstatus
      model('UserReadNewsModel')->updateusersNewsReadStatus($user_id, $news_id);


        $data = array();
        
        // Return data to view
        $data['token'] = csrf_hash();
        $data['news_id'] = $news_id;
        $data['success'] = 1;

    

    return $this->response->setJSON($data);




    }
    
}
