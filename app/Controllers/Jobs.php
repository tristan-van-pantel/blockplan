<?php namespace App\Controllers;

use CodeIgniter\I18n\Time;


/**
 * Jobs enables the admin to create new job-offers for certain classes and is also responsible for displaying those job-offers to the affected classes.
 */
class Jobs extends BaseController
{    
    /**
     * index gets all job-offers for the users class and display them.
     *
     * @return void
     */
    public function index()
    {
        $class = (!empty(model('UserModel')->getClassIdByUserId(user_id()))) ? (model('UserModel')->getClassIdByUserId(user_id())[0]) : (null);
        if (empty($class)) {
            return redirect()->back();
        }
        
        $jobs = model('ClassesModel')->select('jobs.id, jobs, jobs.users_id, jobs.created_at, jobs.updated_at')
            ->join('classesjobs', 'classesjobs.classes_id = classes.id')
            ->join('jobs', 'jobs.id = classesjobs.jobs_id')
            ->where('classes.id', $class->id)
            ->orderBy('created_at', 'DESC')->paginate(5);

            

        foreach ($jobs as $item) {
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
            'class' => $class,
            'user' => model('UserModel'),
            'jobs' => $jobs,
            'jobs_model' => model('JobsModel'),
            'user' => model('UserModel'),
            'pager' => model('ClassesModel')->pager,
        ];
        return view('jobs/showjobs', $data);
    }

    
    /**
     * create gives the admin a form to create a new job-offer for sselected classes and also displays all job-offers ever created.
     *
     * @return void
     */
    public function create()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $jobs = model('JobsModel')->orderBy('created_at', 'DESC')->paginate(5);
        foreach ($jobs as $item) {
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
            'jobs' => $jobs,
            'pager' => model('JobsModel')->pager,
            'user' => model('UserModel'),
            'jobs_model' => model('JobsModel'),
            // 'lecturers' => model('UserModel')->findLecturers(),
        ];
        return view('jobs/createjobs', $data);
    }
    
    /**
     * insertjobs inserts a new joboffer (table 'jobs') and also updates the classesjobs association table with all selected classes.
     *
     * @return void
     */
    public function insertjobs()
    {
        if (!logged_in() || !in_groups('admins')) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'jobs' => 'required',
        ]);
        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $jobs = $this->request->getVar('jobs');
        $classes = $this->request->getVar('classes');
        $old_jobs = $this->request->getVar('jobs_id');
        $user = user_id();
        // if there is no old-jobs, then insert, else update
        if (empty($old_jobs)) {
            $data = [
                'jobs' => $jobs,
                'users_id' => $user,
            ];
            $jobs_id = model('JobsModel')->insert($data, true);
            // insert into classesjobs relation table
            if (!empty($classes)) {
                foreach ($classes as $classes_id) {
                    // insertClassesJobs($classes_id, $jobs_id) : void
                    model('JobsModel')->insertClassesJobs($classes_id, $jobs_id);
                }
            }
        } else {
            $data = [
                'id' => $old_jobs,
                'jobs' => $jobs,
                'users_id' => $user,
            ];
            $jobs_id = model('JobsModel')->save($data);
            // insert into classesjobs relation table
            if (!empty($classes)) {
                foreach ($classes as $classes_id) {
                    // insertClassesJobs($classes_id, $jobs_id) : void
                    model('JobsModel')->insertClassesJobs($classes_id, $jobs_id);
                }
            }
        }

        return redirect()->back()->with('message', 'Job/Offer successfully published');
    }
    
    /**
     * deletejobs deletes a selected job-offer.
     *
     * @return void
     */
    public function deletejobs()
    {
        if (!logged_in() || !in_groups('admins')) {
            return redirect()->back();
        }
        $jobs_id = $this->request->getVar('jobs_id');
        // $classes = (!empty($jobs_id)) ? (model('JobsModel')->getJobsClasses($jobs_id)) : (null);
        // if (!empty($classes)) {
        //    foreach ($classes as $class) {
               
        //    }
        // }
        model('JobsModel')->delete($jobs_id);
        return redirect()->back();
    }
}
