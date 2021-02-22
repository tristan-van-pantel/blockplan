<?php namespace App\Controllers;

use \DateInterval;
use \DatePeriod;
use \Datetime;


/**
 * Dashboard is resposible for giving the students an overiew about their personal data, their courses, thier courses2do and their abcences ("Fehlzeiten"). 
 */
class Dashboard extends BaseController
{    
    /**
     * number_of_working_days returns the number of working days between a bein and an end-date, excludin g holidayDays involved as an optional third parameter.
     *
     * @param  mixed $from
     * @param  mixed $to
     * @param  mixed $holidayDays
     * @return int
     */
    public function number_of_working_days($from, $to, $holidayDays = ['*-12-25', '*-01-01', '2013-12-23'])
    {
        $workingDays = [1, 2, 3, 4, 5]; # date format = N (1 = Monday, ...)

        $from = new DateTime($from);
        $to = new DateTime($to);
        $to->modify('+1 day');
        $interval = new DateInterval('P1D');
        $periods = new DatePeriod($from, $interval, $to);

        $days = 0;
        foreach ($periods as $period) {
            if (!in_array($period->format('N'), $workingDays)) {
                continue;
            }

            if (in_array($period->format('Y-m-d'), $holidayDays)) {
                continue;
            }

            if (in_array($period->format('*-m-d'), $holidayDays)) {
                continue;
            }

            $days++;
        }
        return $days;
    }
    
    /**
     * index gets all the users information and returns a view which displays the information.
     *
     * @return void
     */
    public function index()
    {
        if (!in_groups('students')) {
            return redirect()->to('/');
        }
        $user = User();
        $classModel = model('ClassesModel');
        // $datesModel = model('DateOfCourseModel');
        $usersClass = $classModel->find($user->classes_id);
        $visitedIds = [];
        foreach ($classModel->getClassesVisitedCourses($user->classes_id) as $i) {
            $visitedIds[] = $i->id;
        }
        $current_id = null;
        if (!empty($classModel->getClassesCurrentCourseAndDate($user->classes_id))) {
            $current_id = $classModel->getClassesCurrentCourseAndDate($user->classes_id)[0];
        }
        /* -------------------------------------------------------------------------- */
/*                                 begin test                                 */
/* -------------------------------------------------------------------------- */

        $classesModel = model('ClassesModel');
        $userModel = model('UserModel');
        $user_id = user_id();
        $completed_notifications = model('NotificationOfIllnessModel')->getUsersCompletedNotifications($user_id);
        $user = $userModel->find($user_id);
        $class_id = $user->classes_id;
        $vacations = $classesModel->getClassesVacation($class_id);
        $holidayDays = [];
        foreach ($vacations as $vacation) {
            $end = new DateTime($vacation->vacations_end);
            $end->add(new DateInterval('P10D'));
            $period = new DatePeriod(
                new DateTime($vacation->vacations_begin),
                new DateInterval('P1D'),
                date_add(new DateTime($vacation->vacations_end), date_interval_create_from_date_string('1 days'))
            );
            foreach ($period as $key => $value) {
                // $value->format('Y-m-d');
                $holidayDays[] = $value->format('Y-m-d');
            }

        }
        $days = 0;
        foreach ($completed_notifications as $item) {
            $item->days = $this->number_of_working_days($item->begin, $item->end, $holidayDays);
            $days += $item->days;
        }
        

/* -------------------------------------------------------------------------- */
/*                                  end test                                  */
/* -------------------------------------------------------------------------- */

        if (!empty($usersClass->id)) {
            $user->class = $usersClass->name;
            $user->begin = $usersClass->begin;
            $user->end = $usersClass->end;
            $user->visited_courses = $classModel->getClassesVisitedCourses($user->classes_id);
            $user->visited_ids = $visitedIds;
            $user->courses2do = model('ClassesModel')->getClassesTodoCourses($user->classes_id);
            $user->current_id = $current_id;
            $user->days = $days;
        } else {
            $user->class = 'no class';
            $user->begin = null;
            $user->end = null;
        }

        $data = [
            'user' => $user,
        ];
        return view('dashboard', $data);
    }
}
