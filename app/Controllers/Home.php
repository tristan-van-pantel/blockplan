<?php namespace App\Controllers;

/**
 * Home manages everiything for displaying a students or lecturers timetable.
 */
class Home extends BaseController
{
    public function index()
    {
        if (!logged_in()) {
            return redirect()->to('login');
        }
        

        return view('welcome_message');
    }
    
    /**
     * timetable shows the students or lecturers timetable containing all courses which end-dates are at least greater than the current date or equal. For students it also diplays the vacations.
     *
     * @return void
     */
    public function timetable()
    { // First timetable for lecturers
        if ((!in_groups('students')) && in_groups('lecturers')) {
            $datesModel = model('DateOfCourseModel');
            $lecturers_id = user_id();
            $lecturers_courses = $datesModel->getDateAndCourseByLecturersUserId($lecturers_id);
            // if the lecturer has courses, get all the needed values and put them intu the format, that fullCalendar needs to display events
            if (!empty($lecturers_courses)) {
                foreach ($lecturers_courses as $course) {
                    $course->title = $course->courses_name;
                    $course->description = $course->rooms_name;
                    $course->startRecur = date('Y-m-d', strtotime($course->begin));
                    $course->endRecur = date('c', strtotime($course->end));
                    $course->allDay = true;
                    $course->displayEventEnd = true;
                    unset($course->begin);
                    unset($course->end);
                    unset($course->dates_id);
                    unset($course->courses_name);
                    unset($course->rooms_name);

                }$lecturers_courses = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($lecturers_courses), ENT_NOQUOTES));
            } else {
                $ifNoCoursesForLecturer = [
                    'title' => 'no events found',
                    'description' => '',
                    'allDay' => true,
                    'displayEventEnd' => true,
                ];
                $lecturers_courses = json_encode($ifNoCoursesForLecturer);
            }

            $data = [
                'events' => $lecturers_courses,
            ];
            return view('timetablelecturer', $data);
        }
        if (!in_groups('students')) {
            return redirect()->to('/');
        }

        // Second: Timetable for students
        $coursesModel = model('CoursesModel');
        $datesModel = model('DateOfCourseModel');
        $classesModel = model('ClassesModel');
        $userModel = model('UserModel');
        $user_id = user_id();

        $user = $userModel->find($user_id);
        $class_id = $user->classes_id;
        $current_course = $classesModel->getClassesCurrentCourseAndDate($class_id);
        $vacations = $classesModel->getClassesVacation($class_id);
        // d($user);

        $future_courses = $classesModel->getClassesFutureCourseAndDate($class_id);

        $ifCourseisEmpty = [
            'title' => 'no current event found',
            'description' => 'hello',
            'startRecur' => date('c'),
            'endRecur' => (!empty($future_courses)) ? (date('c', strtotime('-1 day', strtotime($future_courses[0]->begin)))) : (''),
            'allDay' => true,
            'displayEventEnd' => true,
        ];
        $currenCourseCopy = json_encode($ifCourseisEmpty);
        if (!empty($current_course[0])) {
            // return 'hier';
            $currenCourseCopy = [];
            $currenCourseCopy1 = clone $current_course[0];
            $currenCourseCopy2 = clone $current_course[0];
            $currenCourseCopy3 = clone $current_course[0];
            $currenCourseCopy4 = clone $current_course[0];
            

            // part one = before first break
            $currenCourseCopy1->title = $currenCourseCopy1->name;
            $currenCourseCopy1->description = ($currenCourseCopy1->roomname);
            $currenCourseCopy1->description .= "<br>";
            $currenCourseCopy1->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy1->dates_id))[0]->firstname;
            $currenCourseCopy1->description .= " ";
            $currenCourseCopy1->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy1->dates_id))[0]->lastname;
            $currenCourseCopy1->startTime = '08:00';
            $currenCourseCopy1->endTime = '09:30';
            $currenCourseCopy1->startRecur = date('Y-m-d', strtotime($currenCourseCopy1->begin));
            $currenCourseCopy1->endRecur = date('c', strtotime($currenCourseCopy1->end));
            // $currenCourseCopy1->allDay = true;
            $currenCourseCopy1->displayEventEnd = true;
            unset($currenCourseCopy1->id);
            unset($currenCourseCopy1->name);
            unset($currenCourseCopy1->begin);
            unset($currenCourseCopy1->end);
            unset($currenCourseCopy1->dates_id);
            unset($currenCourseCopy1->users_id);
            unset($currenCourseCopy1->roomname);



            // part two = before lunch break
            $currenCourseCopy2->title = $currenCourseCopy2->name;
            $currenCourseCopy2->description = ($currenCourseCopy2->roomname);
            $currenCourseCopy2->description .= "<br>";
            $currenCourseCopy2->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy2->dates_id))[0]->firstname;
            $currenCourseCopy2->description .= " ";
            $currenCourseCopy2->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy2->dates_id))[0]->lastname;
            $currenCourseCopy2->startTime = '09:45';
            $currenCourseCopy2->endTime = '11:15';
            $currenCourseCopy2->startRecur = date('Y-m-d', strtotime($currenCourseCopy2->begin));
            $currenCourseCopy2->endRecur = date('c', strtotime($currenCourseCopy2->end));
            // $currenCourseCopy2->allDay = true;
            $currenCourseCopy2->displayEventEnd = true;
            unset($currenCourseCopy2->id);
            unset($currenCourseCopy2->name);
            unset($currenCourseCopy2->begin);
            unset($currenCourseCopy2->end);
            unset($currenCourseCopy2->dates_id);
            unset($currenCourseCopy2->users_id);
            unset($currenCourseCopy2->roomname);

            // part three = before third break
            $currenCourseCopy3->title = $currenCourseCopy3->name;
            $currenCourseCopy3->description = ($currenCourseCopy3->roomname);
            $currenCourseCopy3->description .= "<br>";
            $currenCourseCopy3->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy3->dates_id))[0]->firstname;
            $currenCourseCopy3->description .= " ";
            $currenCourseCopy3->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy3->dates_id))[0]->lastname;
            $currenCourseCopy3->startTime = '12:00';
            $currenCourseCopy3->endTime = '13:30';
            $currenCourseCopy3->startRecur = date('Y-m-d', strtotime($currenCourseCopy3->begin));
            $currenCourseCopy3->endRecur = date('c', strtotime($currenCourseCopy3->end));
            // $currenCourseCopy3->allDay = true;
            $currenCourseCopy3->displayEventEnd = true;
            unset($currenCourseCopy3->id);
            unset($currenCourseCopy3->name);
            unset($currenCourseCopy3->begin);
            unset($currenCourseCopy3->end);
            unset($currenCourseCopy3->dates_id);
            unset($currenCourseCopy3->users_id);
            unset($currenCourseCopy3->roomname);

            // part four = before end of schoolday
            $currenCourseCopy4->title = $currenCourseCopy4->name;
            $currenCourseCopy4->description = ($currenCourseCopy4->roomname);
            $currenCourseCopy4->description .= "<br>";
            $currenCourseCopy4->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy4->dates_id))[0]->firstname;
            $currenCourseCopy4->description .= " ";
            $currenCourseCopy4->description .= ($datesModel->getLecturerByDatesId($currenCourseCopy4->dates_id))[0]->lastname;
            $currenCourseCopy4->startTime = '13:45';
            $currenCourseCopy4->endTime = '15:15';
            $currenCourseCopy4->startRecur = date('Y-m-d', strtotime($currenCourseCopy4->begin));
            $currenCourseCopy4->endRecur = date('c', strtotime($currenCourseCopy4->end));
            // $currenCourseCopy4->allDay = true;
            $currenCourseCopy4->displayEventEnd = true;
            unset($currenCourseCopy4->id);
            unset($currenCourseCopy4->name);
            unset($currenCourseCopy4->begin);
            unset($currenCourseCopy4->end);
            unset($currenCourseCopy4->dates_id);
            unset($currenCourseCopy4->users_id);
            unset($currenCourseCopy4->roomname);

            // $currenCourseCopy[] = json_encode($currenCourseCopy1);
            $currenCourseCopy[] = $currenCourseCopy1;
            $currenCourseCopy[] = $currenCourseCopy2;
            $currenCourseCopy[] = $currenCourseCopy3;
            $currenCourseCopy[] = $currenCourseCopy4;
            $currenCourseCopy = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($currenCourseCopy), ENT_NOQUOTES));

        }

        $ifFutureCourseisEmpty = [
            'title' => 'keine Veranstaltung eingetragen',
            'description' => '',
            'startRecur' => (!empty($current_course)) ? (date('c', strtotime('+1 day', strtotime($current_course[0]->end)))) : (''),
            'endRecur' => '',
            'allDay' => true,
            'displayEventEnd' => true,
        ];
        $futureCourseCopy = json_encode($ifFutureCourseisEmpty);
        $futureCourses = json_encode($ifFutureCourseisEmpty);
        if (!empty($future_courses)) {
            $futureCourseCopy = [];
            $futureCourses = [];
            foreach ($future_courses as $futureItem) {
                $futureCourseCopy1 = clone $futureItem;
                $futureCourseCopy2 = clone $futureItem;
                $futureCourseCopy3 = clone $futureItem;
                $futureCourseCopy4 = clone $futureItem;

                // part 1 = before the first break
                $futureCourseCopy1->title = $futureCourseCopy1->name;
                $futureCourseCopy1->description = ($futureCourseCopy1->roomname);
                $futureCourseCopy1->description .= "<br>";
                $futureCourseCopy1->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy1->dates_id))[0]->firstname;
                $futureCourseCopy1->description .= " ";
                $futureCourseCopy1->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy1->dates_id))[0]->lastname;
                $futureCourseCopy1->startTime = '08:00';
                $futureCourseCopy1->endTime = '09:30';
                $futureCourseCopy1->startRecur = date('Y-m-d', strtotime($futureCourseCopy1->begin));
                $futureCourseCopy1->endRecur = date('c', strtotime($futureCourseCopy1->end));
               // $futureCourseCopy->allDay = true;
                $futureCourseCopy1->displayEventEnd = true;
                unset($futureCourseCopy1->id);
                unset($futureCourseCopy1->name);
                unset($futureCourseCopy1->begin);
                unset($futureCourseCopy1->end);
                unset($futureCourseCopy1->dates_id);
                unset($futureCourseCopy1->users_id);
                unset($futureCourseCopy1->roomname);

                // part 2 = before lunch break
                $futureCourseCopy2->title = $futureCourseCopy2->name;
                $futureCourseCopy2->description = ($futureCourseCopy2->roomname);
                $futureCourseCopy2->description .= "<br>";
                $futureCourseCopy2->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy2->dates_id))[0]->firstname;
                $futureCourseCopy2->description .= " ";
                $futureCourseCopy2->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy2->dates_id))[0]->lastname;
                $futureCourseCopy2->startTime = '09:45';
                $futureCourseCopy2->endTime = '11:15';
                $futureCourseCopy2->startRecur = date('Y-m-d', strtotime($futureCourseCopy2->begin));
                $futureCourseCopy2->endRecur = date('c', strtotime($futureCourseCopy2->end));
               // $futureCourseCopy->allDay = true;
                $futureCourseCopy2->displayEventEnd = true;
                unset($futureCourseCopy2->id);
                unset($futureCourseCopy2->name);
                unset($futureCourseCopy2->begin);
                unset($futureCourseCopy2->end);
                unset($futureCourseCopy2->dates_id);
                unset($futureCourseCopy2->users_id);
                unset($futureCourseCopy2->roomname);

                // part 3 = before the third break
                $futureCourseCopy3->title = $futureCourseCopy3->name;
                $futureCourseCopy3->description = ($futureCourseCopy3->roomname);
                $futureCourseCopy3->description .= "<br>";
                $futureCourseCopy3->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy3->dates_id))[0]->firstname;
                $futureCourseCopy3->description .= " ";
                $futureCourseCopy3->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy3->dates_id))[0]->lastname;
                $futureCourseCopy3->startTime = '12:00';
                $futureCourseCopy3->endTime = '13:30';
                $futureCourseCopy3->startRecur = date('Y-m-d', strtotime($futureCourseCopy3->begin));
                $futureCourseCopy3->endRecur = date('c', strtotime($futureCourseCopy3->end));
               // $futureCourseCopy->allDay = true;
                $futureCourseCopy3->displayEventEnd = true;
                unset($futureCourseCopy3->id);
                unset($futureCourseCopy3->name);
                unset($futureCourseCopy3->begin);
                unset($futureCourseCopy3->end);
                unset($futureCourseCopy3->dates_id);
                unset($futureCourseCopy3->users_id);
                unset($futureCourseCopy3->roomname);


                // part 4 = from lunch till end of schoolday
                $futureCourseCopy4->title = $futureCourseCopy4->name;
                $futureCourseCopy4->description = ($futureCourseCopy4->roomname);
                $futureCourseCopy4->description .= "<br>";
                $futureCourseCopy4->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy4->dates_id))[0]->firstname;
                $futureCourseCopy4->description .= " ";
                $futureCourseCopy4->description .= ($datesModel->getLecturerByDatesId($futureCourseCopy4->dates_id))[0]->lastname;
                $futureCourseCopy4->startTime = '13:45';
                $futureCourseCopy4->endTime = '15:15';
                $futureCourseCopy4->startRecur = date('Y-m-d', strtotime($futureCourseCopy4->begin));
                $futureCourseCopy4->endRecur = date('c', strtotime($futureCourseCopy4->end));
               // $futureCourseCopy->allDay = true;
                $futureCourseCopy4->displayEventEnd = true;
                unset($futureCourseCopy4->id);
                unset($futureCourseCopy4->name);
                unset($futureCourseCopy4->begin);
                unset($futureCourseCopy4->end);
                unset($futureCourseCopy4->dates_id);
                unset($futureCourseCopy4->users_id);
                unset($futureCourseCopy4->roomname);

                $futureCourses[] = $futureCourseCopy1;
                $futureCourses[] = $futureCourseCopy2;
                $futureCourses[] = $futureCourseCopy3;
                $futureCourses[] = $futureCourseCopy4;
            }
            
            $futureCourses = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($futureCourses), ENT_NOQUOTES));

        }

        $ifNoVacations = [
            'title' => 'no vacations event found',
            'description' => '',
            'allDay' => true,
            'displayEventEnd' => true,
        ];
        $vacationArray = json_encode($ifNoVacations);

        if (!empty($vacations)) {
            $vacationCopy = [];
            $vacationArray = [];
            foreach ($vacations as $vacation) {
                $vacationCopy = $vacation;
                $vacationCopy->title = $vacationCopy->vacations_name;
                $vacationCopy->startRecur = date('Y-m-d', strtotime($vacationCopy->vacations_begin));
                $vacationCopy->endRecur = date('c', strtotime($vacationCopy->vacations_end));
                $vacationCopy->allDay = true;
                $vacationCopy->displayEventEnd = true;
                unset($vacationCopy->classes_id);
                unset($vacationCopy->vacations_id);
                unset($vacationCopy->vacations_begin);
                unset($vacationCopy->vacations_end);
                unset($vacationCopy->vacations_id);
                unset($vacationCopy->vacations_name);
                $vacationArray[] = $vacationCopy;
            }
            $vacationArray = str_replace(array('[', ']'), '', htmlspecialchars(json_encode($vacationArray), ENT_NOQUOTES));
            // d($vacationArray);

        }

        $data = [
            'current_course' => $current_course,
            'future_courses' => $future_courses,
            'datesModel' => $datesModel,
            'currenCourseCopy' => $currenCourseCopy,
            'futureCourses' => $futureCourses,
            'vacations' => $vacationArray,
        ];

        return view('timetable', $data);
    }

    public function test()
    {
        return view('test');
    }

}
