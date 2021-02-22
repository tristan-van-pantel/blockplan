<?php namespace App\Controllers;

/**
 * Courses Controller manages everything related to the creation and management of "datesOfCourses" (the combination of rooms, lecturers, time, plus the classes2datesofcourse association table) and also the creation of courses itself.
 */
class Courses extends BaseController
{    
    /**
     * index displays the overview of all courses ("Fächer/Kurse") and all datesOfCourses ("Termine der Kurse inkl. Raum, Zeit, Dozent, und Klassen")
     *
     * @return void
     */
    public function index()
    {
        if (!(in_groups('admins') || in_groups('lecturers'))) {
            return redirect()->to('/');
        }
        $coursesModel = model('CoursesModel');
        $datesModel = model('DateOfCourseModel');
        $classesModel = model('ClassesModel');

        $data = [
            'courses' => $coursesModel->paginate(null, 'group1'),
            'dates' => $datesModel->paginate(null, 'group2'),
            'datesModel' => $datesModel,
            'classes' => $classesModel,
            'pager' => $coursesModel->pager,
        ];

        return view('courses/courses', $data);
    }
    
    /**
     * addCourse redirects to the view, which enables to add new courses ("Fächer/Kurse").
     *
     * @return void
     */
    public function addCourse()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        return view('courses/addCourse');
    }
    
    /**
     * insertcourse inserts the new course into the database.
     *
     * @return void
     */
    public function insertcourse()
    {
        $coursesModel = model('CoursesModel');

        // Validate here

        if (!$this->validate($coursesModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }

        $coursesModel->save([
            'name' => $this->request->getVar('name'),
            'internal_id' => $this->request->getVar('internal_id'),

        ]);
        return view('/courses/addCourse');
    }
    
    /**
     * insertdateofcourse redirects to the view, which enables to combine [courses, lecturers, time and classes]. --> yout get datesOfCourses as a result.
     *
     * @return void
     */
    public function insertdateofcourse()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $coursesModel = model('CoursesModel');
        $userModel = model('UserModel');
        $classesModel = model('ClassesModel');
        $roomModel = model('RoomModel');
        // return var_dump($userModel->findLecturers());
        $data = [
            'courses' => $coursesModel->paginate(),
            'lecturers' => $userModel->findLecturers(),
            'classes' => $classesModel->paginate(),
            'rooms' => $roomModel->paginate(),
        ];

        return view('courses/adddateofcourse', $data);
    }
    
    /**
     * commitInsertDateOfCourse inserts the newly created dateOfCourse into the dateOfCourse table and also inserts all involved classes into the classesDatesOfCourse association table.
     *
     * @return void
     */
    public function commitInsertDateOfCourse()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $datesModel = model('DateOfCourseModel');

        $roomModel = model('RoomModel');

        $classesModel = model('ClassesModel');

        // Validate here

        if (!$this->validate($datesModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        // get all the variables needed for inserting into the datesofcourse-table
        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');
        $rooms_id = $this->request->getVar('rooms_id');
        $courses_id = $this->request->getVar('courses_id');
        $users_id = $this->request->getVar('users_id');

        // get an array with the id's of all selected classes the user wants to add
        $combinedClasses = $this->request->getVar('combinedClasses');

        $combinedCapacity = 0;
        // get the combined number of students of al selected classes
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $combinedCapacity += ($classesModel->find($i))->enrolled_students;
            }
        }
        // get the room-capicity
        $roomCapacity = ($roomModel->find($rooms_id))->capacity;

        if ($combinedCapacity > $roomCapacity) {
            return redirect()->back()->with('message', 'Der gewählte Klassenraum ist zu klein');
        }

        if ($begin > $end) {
            return redirect()->back()->with('message', 'Enddatum muss größer oder gleich dem Anfangsdatum sein');
        }

        // foreach selected class, check if classes end-date is greater than the planned datesofcourse end date, else error
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $classEnd = (($classesModel->find($i))->end);
                if ($classEnd < $end) {
                    return redirect()->back()->with('message', 'Eine der gewählten Klassen absolviert bereits vor dem geplanten Enddatum des Kurses');
                }

            }
        }

        // check if the selected room is in the array of occupied rooms during the selected timeslot, if so, then throw error-message
        foreach ($datesModel->getOccupiedRoomsByBeginEndDates($begin, $end) as $i) {
            if ($i->id == $rooms_id) {
                return redirect()->back()->with('message', 'Der gewählte Raum ist im gewählten Zeitraum leider belegt. Bitte wählen Sie einen anderen Raum, oder passen Sie den Zeitraum an.');
            }
        }

        // check if the selected lecturer is in the array of occupied lecturers during the selected timeslot, if so, then throw error-message
        foreach ($datesModel->getOccupiedLecturersByBeginEndDates($begin, $end) as $i) {
            if ($i->id == $users_id) {
                return redirect()->back()->with('message', 'Der gewählte Dozent ist im gewählten Zeitraum leider schon verplant. Bitte wählen Sie einen anderen Dozenten oder einen anderen Zeitraum.');
            }
        }

        // check if one or more of the selected classes are in the array of occupied lecturers during the selected timeslot, if so, then throw error-message
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                foreach ($datesModel->getOccupiedClassesByBeginEndDates($begin, $end) as $j) {
                    if ($j->id == ($classesModel->find($i))->id) {
                        return redirect()->back()->with('message', 'Mindestens eine der gewählten Klassen ist im geplanten Zeitraum leider schon andersweitig verplant, bitte passen Sie Ihre Klassenauswahl oder den Zeitraum an.');
                    }
                }

            }
        }

        $data = [
            'begin' => $begin,
            'end' => $end,
            'rooms_id' => $rooms_id,
            'courses_id' => $courses_id,
            'users_id' => $users_id,
        ];

        // insert all nedded values ino the datesofcourse-table and get the id of this new entry, because this is needed as FK for classesdatesofcourse table
        $datesId = $datesModel->insert($data, true);

        // now take the datesofcourseId ($datesId) and eeach classes_id ($i) and insert the two FKs into the ClassesDateOfCourse relation-table (classes <--> classesdatesofourse <--> datesofcourse )
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $datesModel->insertclassesdatesofcourse($i, $datesId);
            }
        }

        return redirect()->back()->with('success', 'Kurstermin wurde erfolgreich erstellt.');

    }

    public function deleteDateOfCourse()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $datesModel = model('DateOfCourseModel');
        $id = $this->request->getVar('deleteDateId');
        if (!empty($id)) {
            $datesModel->delete($id);
            $datesModel->purgeDeleted();
            return redirect()->back();
        }
    }


    public function deletecourse()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $coursemodel = model('CoursesModel');
        $id = $this->request->getVar('course_id');
        // return d($id);
        if (!empty($id)) {
            $coursemodel->delete($id);
            $coursemodel->purgeDeleted();
            return redirect()->back();
        }
    }
    
    /**
     * editdateofcourse retruns the view fpr editing a dateOfCourse.
     *
     * @param  mixed $id
     * @return void
     */
    public function editdateofcourse($id = null)
    {
        // make sure, that only admins can edit
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        // get variables for all the models needed in the process
        $datesModel = model('DateOfCourseModel');
        $coursesModel = model('CoursesModel');
        $userModel = model('UserModel');
        $classesModel = model('ClassesModel');
        $roomModel = model('RoomModel');
        // if no id given, return back to admin-panel-main
        if (empty($id)) {
            return redirect()->to('/admin');
        }
        // if an id is given, but it is no valid id, also redirect back
        $allowedIds = [];
        foreach ($datesModel->findAll() as $i) {
            $allowedIds[] = $i->id; // contains the ids of all avaible datesOfCourses
        }
        if (!in_array($id, $allowedIds)) {
            return redirect()->back();
        }
        // find all the classes related to the dateOfCourse the user wants to edit
        $oldClasses = $datesModel->findClassNameByDatesId($id);

        $date = $datesModel->find($id);
        $data = [
            'date' => $date,
            'courses' => $coursesModel->paginate(),
            'lecturers' => $userModel->findLecturers(),
            'classes' => $classesModel->paginate(),
            'rooms' => $roomModel->paginate(),
            'oldClasses' => $oldClasses,
        ];
        echo view('courses/editdate', $data);
    }
    
    /**
     * submitDateEdit inserst all changes made to a dateOfCourse and also updates the classesDatesOfCourse association table with all classes selected.
     *
     * @return void
     */
    public function submitDateEdit()
    {
        // make sure, that only admins can edit
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }

        //get variables for all the models involved in the process
        $datesModel = model('DateOfCourseModel');

        $roomModel = model('RoomModel');

        $classesModel = model('ClassesModel');

        // Validate here, use the datesOfCourseModel for that

        if (!$this->validate($datesModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        // get all the variables needed for inserting into the datesofcourse-table
        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');
        $rooms_id = $this->request->getVar('rooms_id');
        $courses_id = $this->request->getVar('courses_id');
        $users_id = $this->request->getVar('users_id');

        $oldroom = $this->request->getVar('oldroom');
        $oldcourse = $this->request->getVar('oldcourse');
        $oldend = $this->request->getVar('oldend');
        $oldbegin = $this->request->getVar('oldbegin');
        $oldlecturer = $this->request->getVar('oldlecturer');
        $dateId = $this->request->getVar('dateId');
        $oldClasses = $datesModel->findClassNameByDatesId($dateId);

        // get an array with the id's of all selected classes the user wants to add
        $combinedClasses = $this->request->getVar('combinedClasses');
        if (empty($combinedClasses)) {
            $combinedClasses = [];
        }
        // get an array that only includes the ids of the old classes
        $intOldClasses = [];
        foreach ($oldClasses as $i) {
            $intOldClasses[] = $i->id;
        }
        $result = array_diff($combinedClasses, $intOldClasses);
        $result2 = array_diff($intOldClasses, $combinedClasses);
        $sameClasses = (count($result) + count($result2) == 0) ? (true) : (false); // is true, if exactly the same classes are selected as before.

        //check if the user really made any changes, if the user did NOT, then redirect back with message
        if (($oldroom == $rooms_id) && ($oldlecturer == $users_id) && ($sameClasses) && ($oldcourse == $courses_id) && (date('Y-m-d', strtotime($oldbegin)) == $begin) && (date('Y-m-d', strtotime($oldend)) == $end)) {
            return redirect()->back()->with('success', 'No changes made');
        }

        $combinedCapacity = 0;
        // get the combined number of students of all selected classes
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $combinedCapacity += ($classesModel->find($i))->enrolled_students; // gets the number of enrolled students for each class and adds them to the combine capacity
            }
        }
        // get the room-capicity
        $roomCapacity = ($roomModel->find($rooms_id))->capacity;

        if ($combinedCapacity > $roomCapacity) {
            return redirect()->back()->with('message', 'The classroom ist too small');
        }

        if ($begin >= $end) {
            return redirect()->back()->with('message', 'end-date must be greater than begin-date');
        }

        // foreach selected class, check if classes end-date is greater than the planned datesofcourse end date, else error
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $classEnd = (($classesModel->find($i))->end);
                if ($classEnd < $end) {
                    return redirect()->back()->with('message', 'One of the selected classes already graduates before the seleced end-date of the course');
                }

            }
        }

        // check if the selected room is in the array of occupied rooms during the selected timeslot, if so, then throw error-message
        foreach ($datesModel->getOccupiedRoomsByBeginEndDatesExceptOld($begin, $end, $dateId) as $i) {
            if ($i->id == $rooms_id) {
                return redirect()->back()->with('message', 'The selected room is occupied during the selected timeslot, please select another room, or a differnt timeslot');
            }
        }

        // check if the selected lecturer is in the array of occupied lecturers during the selected timeslot, if so, then throw error-message
        foreach ($datesModel->getOccupiedLecturersByBeginEndDatesExceptOld($begin, $end, $dateId) as $i) {
            if ($i->id == $users_id) {
                return redirect()->back()->with('message', 'The selected lecturer is occupied during the selected timeslot, please select another lecturer, or a differnt timeslot');
            }
        }

        // find out, which of the former selected classes dit not make it into the new selection
        $deletedClasses = [];

        foreach ($oldClasses as $oldClass) {
            if (!in_array($oldClass->id, $combinedClasses)) {
                $deletedClasses[] = $oldClass->id; // adds deleted class to the array of deleted classes
            }
        }

        // loop through $combinedClasses (the classes the user selected) and get rid of the classes that were selected before edit
        if (!empty($combinedClasses)) {
            foreach ($oldClasses as $oldClass) {
                if (($key = array_search($oldClass->id, $combinedClasses)) !== false) {
                    unset($combinedClasses[$key]);
                }

            }
        }

        // check if one or more of the selected classes are in the array of occupied classes during the selected timeslot, if so, then throw error-message
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                foreach ($datesModel->getOccupiedClassesByBeginEndDatesExceptOld($begin, $end, $dateId) as $j) {
                    //  return d(($datesModel->getOccupiedClassesByBeginEndDatesExceptOld($begin, $end, $dateId)[0]== ($classesModel->find($i))->id) ? ('gleich') : ( $datesModel->getOccupiedClassesByBeginEndDatesExceptOld($begin, $end, $dateId)[0]->id. ' ungleich '. ($classesModel->find($i))->id));
                    if ($j->id == ($classesModel->find($i))->id) {

                        return redirect()->back()->with('message', 'One of the selected classes is occupied during the selected timeslot, please select other classes, or a differnt timeslot');
                    }
                }

            }
        }

        $data = [
            'begin' => $begin,
            'end' => $end,
            'rooms_id' => $rooms_id,
            'courses_id' => $courses_id,
            'users_id' => $users_id,
        ];



        // update the new values into the datesofcourse-table
        $datesModel->update($dateId, $data);

        // now take the datesofcourseId ($datesId) and eeach classes_id ($i) and insert the two FKs into the ClassesDateOfCourse table
        if (!empty($combinedClasses)) {
            foreach ($combinedClasses as $i) {
                $datesModel->insertclassesdatesofcourse($i, $dateId);
            }
        }
        // if classes were tdeleted from the dateOfCourse, get rid of them
        if (!empty($deletedClasses)) {
            foreach ($deletedClasses as $deletedClass) {
                $datesModel->deleteClassesDates($deletedClass, $dateId);
            }
        }

        return redirect()->back()->with('success', 'properties were edited successfully!');

    }

}
