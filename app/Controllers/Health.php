<?php namespace App\Controllers;

use CodeIgniter\I18n\Time;
use \DateInterval;
use \DatePeriod;
use \Datetime;

/**
 * Health is resposible for enabling the students to call in sick (online) and also for allowing the admins to manage/verify the notifications of illness ("Krankmeldungen").
 */
class Health extends BaseController
{

    public function number_of_working_days($from, $to, $holidayDays = ['*-12-25', '*-01-01', '2020-04-10'])
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
     * index leads the students though the process of calling in sick. 
     *
     * @return void
     */
    public function index()
    {
        if (!in_groups('students')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $user_id = user_id();
        $step = 1;
        $notification_id = null;
        // if there are open notifications AND no uploaded health_certificates related to the open notification, then step equals 2
        if (!empty(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)) && empty(model('NotificationOfIllnessModel')->getNotificationsHealthCertificates(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id))) {
            $step = 2;
            $notification_id = model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id;
        }
        // if there are open notifications AND there are uploaded health_certificates related to the open notification, but not yet any related, uploaded illness_form, then step equals 3
        elseif (!empty(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)) && !empty(model('NotificationOfIllnessModel')->getNotificationsHealthCertificates(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id)) && empty(model('NotificationOfIllnessModel')->getNotificationsIllnessForms(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id))) {
            $step = 3;
            $notification_id = model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id;
        }
        // if there are open notifications AND there is an uploaded health_certificate related to the open notification AND there is a related, uploaded illness_form, then step equals 4
        elseif (!empty(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)) && !empty(model('NotificationOfIllnessModel')->getNotificationsHealthCertificates(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id)) && !empty(model('NotificationOfIllnessModel')->getNotificationsIllnessForms(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id))) {
            $step = 4;
            $notification_id = model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id)[0]->id;
        }
        $unexcused_notifications = model('NotificationOfIllnessModel')->getUsersUnexcusedNotifications($user_id);
        $completed_notifications = model('NotificationOfIllnessModel')->getUsersCompletedNotifications($user_id);

/* -------------------------------------------------------------------------- */
/*                                 begin test                                 */
/* -------------------------------------------------------------------------- */
        $classesModel = model('ClassesModel');
        $userModel = model('UserModel');
        $user_id = user_id();

        $user = $userModel->find($user_id);
        $class_id = $user->classes_id;
        $vacations = $classesModel->getAllClassesVacation($class_id);
        $holidayDays = [];
        foreach ($vacations as $vacation) {
            // $end = new DateTime($vacation->vacations_end);
            // $end->add(new DateInterval('P10D'));
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
        // d($completed_notifications[0]->days);

/* -------------------------------------------------------------------------- */
/*                                  end test                                  */
/* -------------------------------------------------------------------------- */

        $data = [
            'step' => $step,
            'notification_id' => $notification_id,
            // 'unexcused_notifications' => $unexcused_notifications,
            'completed_notifications' => $completed_notifications,
            'days' => $days,
        ];
        return view('health/callinsick', $data);
    }


    
    
    /**
     * callinsick manages the first step of a student calling in sick. Comparable to a student calling and explaining he/she will not be able to come to school today/tomorrow because he/she is sick.
     *
     * @return void
     */
    public function callinsick()
    {
        if (!in_groups('students')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $user_id = user_id();
        // if there still is an open notification of illness, redirect back
        if (!empty(model('NotificationOfIllnessModel')->getUsersOpenNotificationOfIllness($user_id))) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'begin' => 'required|valid_date',
            'checkbox' => 'required',

        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $begin = $this->request->getVar('begin');
        if ($begin <  date('Y-m-d')) {
            return redirect()->back()->with('error', 'Sie können sich nur ab heute oder ab morgen krankmelden.');
        }
        $data = [
            'users_id' => $user_id,
            'begin' => $begin,
            'open' => true,
        ];
        $notification_of_illness_id = model('NotificationOfIllnessModel')->insert($data, true);

        // return view('health/callinsick', $data);
        return redirect()->back();

    }
    
    /**
     * uploadhealtcertificate enables the user to upload the health-certificate.
     *
     * @return void
     */
    public function uploadhealtcertificate()
    {
        if (!logged_in() || !in_groups('students')) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png,application/pdf,application/x-download]',
                'max_size[file,4096]',
                // 'is_image[file]',
            ],
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }

        $health_certificate = $this->request->getFile('file');
        $notification_of_illness_id = $this->request->getVar('notification_id');
        if ($health_certificate->isValid() && !$health_certificate->hasMoved()) {
            $newName = $health_certificate->getRandomName();
            $health_certificate->move(ROOTPATH . 'public/assets/uploads/health_certificates', $newName);

            // insertNotificationsHealthCertificate($notifications_id, $filename, $filetype)  : void
            model('NotificationOfIllnessModel')->insertNotificationsHealthCertificate($notification_of_illness_id, $health_certificate->getName(), $health_certificate->getClientMimeType());

            // return 'Upload health certificate';
            return redirect()->route('health');

        }
    }
    
    /**
     * uploadillnessform enables the user to update the illness-form.
     *
     * @return void
     */
    public function uploadillnessform()
    {
        if (!logged_in() || !in_groups('students')) {
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'file' => [
                'uploaded[file]',
                'mime_in[file,image/jpg,image/jpeg,image/png,application/pdf,application/x-download]',
                'max_size[file,4096]',
                // 'is_image[file]',
            ],
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }

        $illness_form = $this->request->getFile('file');
        $notification_of_illness_id = $this->request->getVar('notification_id');
        if ($illness_form->isValid() && !$illness_form->hasMoved()) {
            $newName = $illness_form->getRandomName();
            $illness_form->move(ROOTPATH . 'public/assets/uploads/illness_forms', $newName);

            // insertNotificationsHealthCertificate($notifications_id, $filename, $filetype)  : void
            model('NotificationOfIllnessModel')->insertNotificationsIllnessForm($notification_of_illness_id, $illness_form->getName(), $illness_form->getClientMimeType());

            // return 'Upload illness_form';
            return redirect()->route('health');
        }

    }
    
    /**
     * illnessmanagement gives the admins an overview over all open, excuses and uneccused notifications of illness.
     *
     * @return void
     */
    public function illnessmanagement()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $open_notifications = model('NotificationOfIllnessModel')->getOpenNotificationsOfIllness();
        $excused_notifications = model('NotificationOfIllnessModel')->getExcusedNotificationsOfIllness();
        $unexcused_notifications = model('NotificationOfIllnessModel')->getUnexcusedNotificationsOfIllness();
        $data = [
            'open_notifications' => $open_notifications,
            'excused_notifications' => $excused_notifications,
            'unexcused_notifications' => $unexcused_notifications,
        ];
        return view('health/manage', $data);
    }
    
    /**
     * viewnotification gives the admin an overview containing all the information of a certain notification of illness.
     *
     * @param  mixed $notification_id
     * @return void
     */
    public function viewnotification($notification_id = null)
    {
        if (!in_groups('admins') || empty($notification_id) || empty(model('NotificationOfIllnessModel')->find($notification_id)) || (model('NotificationOfIllnessModel')->find($notification_id)->open == 0)) {
            // Go back to the previous page
            return redirect()->back();
        }
        $notification = model('NotificationOfIllnessModel')->find($notification_id);
        // return d($notification);
        $illness_form = (!empty(model('NotificationOfIllnessModel')->getNotificationsIllnessForms($notification_id))) ? model('NotificationOfIllnessModel')->getNotificationsIllnessForms($notification_id) : (null);
        $health_certificate = (!empty(model('NotificationOfIllnessModel')->getNotificationsHealthCertificates($notification_id))) ? (model('NotificationOfIllnessModel')->getNotificationsHealthCertificates($notification_id)) : (null);
        if (!empty($health_certificate)) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($health_certificate[0]->created_at));
            $month = date('n', strtotime($health_certificate[0]->created_at));
            $day = date('d', strtotime($health_certificate[0]->created_at));
            $hour = date('G', strtotime($health_certificate[0]->created_at));
            $minutes = date('i', strtotime($health_certificate[0]->created_at));
            $seconds = date('s', strtotime($health_certificate[0]->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $health_certificate[0]->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            $year = date('Y', strtotime($notification->begin));
            $month = date('n', strtotime($notification->begin));
            $day = date('d', strtotime($notification->begin));
            $hour = date('G', strtotime($notification->begin));
            $minutes = date('i', strtotime($notification->begin));
            $seconds = date('s', strtotime($notification->begin));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $notification_time = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            // get the difference detween the intitial notification and the upload of the illness_form
            $health_certificate[0]->time_ago = $health_certificate[0]->time_ago->difference($notification_time)->getDays();
        }
        if (!empty($illness_form)) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($illness_form[0]->created_at));
            $month = date('n', strtotime($illness_form[0]->created_at));
            $day = date('d', strtotime($illness_form[0]->created_at));
            $hour = date('G', strtotime($illness_form[0]->created_at));
            $minutes = date('i', strtotime($illness_form[0]->created_at));
            $seconds = date('s', strtotime($illness_form[0]->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $illness_form[0]->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            $year = date('Y', strtotime($notification->begin));
            $month = date('n', strtotime($notification->begin));
            $day = date('d', strtotime($notification->begin));
            $hour = date('G', strtotime($notification->begin));
            $minutes = date('i', strtotime($notification->begin));
            $seconds = date('s', strtotime($notification->begin));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $notification_time = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            // get the difference detween the intitial notification and the upload of the illness_form
            $illness_form[0]->time_ago = $illness_form[0]->time_ago->difference($notification_time)->getDays();
        }

        $data = [
            'notification' => $notification,
            'illness_form' => $illness_form,
            'health_certificate' => $health_certificate,
            'user' => model('UserModel')->find($notification->users_id),
        ];
        return view('health/viewnotification', $data);
    }
    
    /**
     * completenotification enables the admin to complete/close a students notification of illness as 'excused'.
     *
     * @return void
     */
    public function completenotification()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $id = $this->request->getVar('notification_id');
        $data = [
            'open' => false,
            'intime' => true,
        ];
        model('NotificationOfIllnessModel')->update($id, $data);
        return redirect()->route('illnessmanagement');

    }
    
    /**
     * completenotificationunexcused enables the admin to complete/close a students notification of illness as 'unexcused'.
     *
     * @return void
     */
    public function completenotificationunexcused()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        $id = $this->request->getVar('notification_id');
        $data = [
            'open' => false,
            'intime' => false,
        ];
        model('NotificationOfIllnessModel')->update($id, $data);
        return redirect()->route('illnessmanagement');

    }
    
    /**
     * notificationenddate enables the admin to enter an end-date for a certain notification of illness (the last day of a student being sick). The has to read the date from the health cerificate ("gelber Schein"). A notification can only be completed, if it has an end-date.
     *
     * @return void
     */
    public function notificationenddate()
    {
        if (!in_groups('admins')) {
            // Go back to the previous page
            return redirect()->back();
        }
        // Validate here
        $val = $this->validate([
            'end' => 'required|valid_date',
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $end = $this->request->getVar('end');
        $id = $this->request->getVar('notification_id');
        $begin = model('NotificationOfIllnessModel')->find($id)->begin;
        if ($begin > $end) {
            // Go back to the previous page
            return redirect()->back()->with('error', 'Enddatum muss größer/gleich Anfangsdatum seien');
        }
        $data = [
            'id' => $id,
            'end' => $end,
        ];
        model('NotificationOfIllnessModel')->save($data);
        return redirect()->back()->with('success', 'Enddatum erfolgreich eingetragen');
    }
    
    /**
     * editnotification enables the admin to edit a notification of illness.
     *
     * @param  mixed $notification_id
     * @return void
     */
    public function editnotification($notification_id = null)
    {
        if (!in_groups('admins') || empty($notification_id) || empty(model('NotificationOfIllnessModel')->find($notification_id))) {
            // Go back to the previous page
            return redirect()->back();
        }
        $notification = model('NotificationOfIllnessModel')->find($notification_id);
        // return d($notification);
        $illness_form = (!empty(model('NotificationOfIllnessModel')->getNotificationsIllnessForms($notification_id))) ? model('NotificationOfIllnessModel')->getNotificationsIllnessForms($notification_id) : (null);
        $health_certificate = (!empty(model('NotificationOfIllnessModel')->getNotificationsHealthCertificates($notification_id))) ? (model('NotificationOfIllnessModel')->getNotificationsHealthCertificates($notification_id)) : (null);
        if (!empty($health_certificate)) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($health_certificate[0]->created_at));
            $month = date('n', strtotime($health_certificate[0]->created_at));
            $day = date('d', strtotime($health_certificate[0]->created_at));
            $hour = date('G', strtotime($health_certificate[0]->created_at));
            $minutes = date('i', strtotime($health_certificate[0]->created_at));
            $seconds = date('s', strtotime($health_certificate[0]->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $health_certificate[0]->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            $year = date('Y', strtotime($notification->begin));
            $month = date('n', strtotime($notification->begin));
            $day = date('d', strtotime($notification->begin));
            $hour = date('G', strtotime($notification->begin));
            $minutes = date('i', strtotime($notification->begin));
            $seconds = date('s', strtotime($notification->begin));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $notification_time = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            // get the difference detween the intitial notification and the upload of the illness_form
            $health_certificate[0]->time_ago = $health_certificate[0]->time_ago->difference($notification_time)->getDays();
        }
        if (!empty($illness_form)) {
            // make use of Codeigniters fully-localized, immutable, date/time class that is built on PHP’s DateTime object the get a humen readable sting
            // that displays the difference between the current date/time and the instance in a human readable format that is geared towards being easily understood.
            // It can create strings like ‘3 hours ago’, ‘in 1 month’, etc:
            $year = date('Y', strtotime($illness_form[0]->created_at));
            $month = date('n', strtotime($illness_form[0]->created_at));
            $day = date('d', strtotime($illness_form[0]->created_at));
            $hour = date('G', strtotime($illness_form[0]->created_at));
            $minutes = date('i', strtotime($illness_form[0]->created_at));
            $seconds = date('s', strtotime($illness_form[0]->created_at));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            // add seven hours to get german time
            $illness_form[0]->time_ago = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            $year = date('Y', strtotime($notification->begin));
            $month = date('n', strtotime($notification->begin));
            $day = date('d', strtotime($notification->begin));
            $hour = date('G', strtotime($notification->begin));
            $minutes = date('i', strtotime($notification->begin));
            $seconds = date('s', strtotime($notification->begin));
            $timezone = 'Europe/Berlin';
            // $timezone = 'America/Chicago';
            $locale = 'de_DE';

            $notification_time = Time::create($year, $month, $day, $hour, $minutes, $seconds, $timezone, $locale)->addHours(7);

            // get the difference detween the intitial notification and the upload of the illness_form
            $illness_form[0]->time_ago = $illness_form[0]->time_ago->difference($notification_time)->getDays();
        }

        $data = [
            'notification' => $notification,
            'illness_form' => $illness_form,
            'health_certificate' => $health_certificate,
            'user' => model('UserModel')->find($notification->users_id),
        ];
        return view('health/viewnotification', $data);
    }



}
