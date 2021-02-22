<?php

namespace App\Controllers;

use App\Models\ClassesModel;


/**
 * Manages erverything related to the school classes.
 * 
 * Creation of classes and their data (name, begin, end..).
 * Management of class-members (students).
 * Management of the classes "must-do-courses / courses2do "
 * Deactivation of whole school-classes after their graduation.
 * Delete classes (like classes created by accident).
 */
class Classes extends BaseController
{

/**
 * Shows an overview of all the schools classes.
 */
    public function index()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classes = new ClassesModel();
        $classModel = model('ClassesModel');

        $data = [
            'classes' => $classes->paginate(),
            'classModel' => $classModel,
        ];
//        var_dump($data);

        return view('/classes/showclasses', $data);

    }


/**
 * Verifies that the user is an admin, if so, then redirects to the adddclass-view
 */
    public function addclass()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }

        return view('/classes/addclass');

    }

    /**
     * inserts the new class after passing al the validation rules
     */
    public function insertclass()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classesModel = model('ClassesModel');

        // Validate here

        if (!$this->validate($classesModel->validationRules)) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');

        if ($begin > $end) {
            return redirect()->back()->with('error', 'Anfangsdatum darf nich kleiner Enddatum seien.');
        }
        $classesModel->save([
            'name' => $this->request->getVar('name'),
            'begin' => $this->request->getVar('begin'),
            'end' => $this->request->getVar('end'),
            'internal_id' => $this->request->getVar('internal_id'),
//            'enrolled_students'  => $this->request->getVar('enrolled_students'),

        ]);
        return view('/classes/addclass');

    }

    /**
     * If class exists and user is an admin, get all the classes data and redirect to the edit-class view.
     */
    public function editclass($classid = 0)
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classModel = model('ClassesModel');
        $userModel = model('UserModel');
        $class = $classModel->find($classid);
        $students = $userModel->findAllStudentsByID($classid);
        $allStudentsInSchool = $userModel->findClasslessStudents();

        $data = [
            'students' => $students,
            'allStudentsInSchool' => $allStudentsInSchool,
            'class' => $class,
        ];
        if (empty($class)) {
            return redirect()->to('/');
        }

        return view('/classes/editclass', $data);

    }


    /**
     * Updates all the classes data after validation.
     */
    public function commitedit()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classesModel = model('ClassesModel');
        $userModel = model('UserModel');

        // Validate here
        $val = $this->validate([
            'name' => 'required',
            'begin' => 'required|valid_date',
            'end' => 'required|valid_date',
        ]);

        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }

        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');

        if ($begin > $end) {
            return redirect()->back()->with('error', 'Anfangsdatum darf nich kleiner Enddatum seien.');
        }

        $classless = $this->request->getPost("school");
        $classmates = $this->request->getVar("classmates");

        $classid = $this->request->getVar('classid');

        // ensure that the $enrolled_students variable is set to 0, if so students are seleced for class
        $enrolled_students = (!empty($classmates)) ? count($classmates) : (0);
        // update alle the new data in the classes_table
        $classesModel->update($this->request->getVar('classid'), [
            'name' => $this->request->getVar('name'),
            'begin' => $this->request->getVar('begin'),
            'end' => $this->request->getVar('end'),
            'enrolled_students' => $enrolled_students,
        ]);
        // the classes-table is updated now, but the users tabe (containing the users classes_id (tells us which student is in which class)) still needs to be updated
        // Take the array with all the students selected for class ($classmates) and  get them into the class (set their classes_id = $class_id)
        $userModel->updateClassStudents($classmates, $classid);
        // Takes the array with all the students seleced to be without class and NULL thier Classes_id's
        $userModel->updateClasslessStudents($classless);
        // redirect back with message abou success
        return redirect()->back()->withInput()->with('message', 'Änderungen erfolgreich eingetragen');

    }

    public function classactivation($class_id = null)
    {
        if (!in_groups('admins') || empty($class_id) || empty(model('ClassesModel')->find($class_id))) {
            // Go back to the previous page
            return redirect()->back();
        }
        $class = model('ClassesModel')->find($class_id);
        $active_students = model('UserModel')->findAllActiveStudentsByClassId($class_id);
        $inactive_students = model('UserModel')->findAllInactiveStudentsByClassId($class_id);
        $data = [
            'class' => $class,
            'active_students' => $active_students,
            'inactive_students' => $inactive_students,
        ];
        return view('classes' . DIRECTORY_SEPARATOR . 'classactivation', $data );
    }

    public function updateclassesactivationstatus()
    {
        if (!in_groups('admins')) {
            return redirect()->back();
        }
        $active_students = $this->request->getVar('active_students');
        $inactive_students = $this->request->getVar('inactive_students');
        model('UserModel')->updateClassesActiveStudents($active_students);
        model('UserModel')->updateClassesInactiveStudents($inactive_students);
        return redirect()->back();
    }

    public function deleteClass()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/admin');
        }
        $classId = $this->request->getVar('class');
        $userModel = model('UserModel');
        $classesModel = model('ClassesModel');

        // find all students in class to be deleted
        $classmates = $userModel->findAllStudentsByID($classId);
        $classmatesnames[] = null;
        foreach ($classmates as $classmate) {
            $classmatesnames[] = $classmate->username;
        }

        // set thier users.classses_id to null
        $userModel->updateClasslessStudents($classmatesnames);

        // delete the class
        $classesModel->delete($classId);
        $classesModel->purgeDeleted();

        return redirect()->to('/classes');

    }

    public function classescoursestodo($class_id = null)
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        if (empty($class_id)) {
            return redirect()->to('/classes');
        }
        $classIds = [];
        foreach (model('ClassesModel')->findAll() as $class) {
            $classIds[] = $class->id;
        }
        if (!in_array($class_id, $classIds)) {
            return redirect()->back();
        }
        $todoIds = [];
        foreach (model('ClassesModel')->getClassesTodoCourses($class_id) as $todoClass) {
            $todoIds[] = $todoClass->id;
        }
        $data = [
            'class' => model('ClassesModel')->find($class_id),
            'courses' => model('CoursesModel')->findAll(),
            'todoIds' => $todoIds,

        ];
        return view('classes/coursestodo', $data);
    }

    public function classescoursestodosave()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $selectedCourses = $this->request->getVar('selectedCourses');
        $class_id = $this->request->getVar('class_id');
        // find the classes which were on the Todo-List befrore and did not make it into the new selection
        $deletedCoursesTodo = [];
        if (!empty($selectedCourses)) {
            foreach (model('ClassesModel')->getClassesTodoCourses($class_id) as $todoClass) {
                if (!in_array($todoClass->id, $selectedCourses)) {
                    $deletedCoursesTodo[] = $todoClass->id;
                }
            }
        }
        // Now get rid of the classes courses2do courses, that were already in the selection before, so that theay are not double saved. Then save them.
        $old_classes_courses2do = model('ClassesModel')->getClassesTodoCourses($class_id);
        if (!empty($selectedCourses)) {
            foreach ($old_classes_courses2do as $old_todo_item) {
                if (($key = array_search($old_todo_item->id, $selectedCourses)) !== false) {
                    unset($selectedCourses[$key]);
                }
            }
            foreach ($selectedCourses as $courses_id) {
                model('ClassesModel')->insertClassesCourses2doCourses($class_id, $courses_id);
            }
        }

        //if there were deleted items, delete them
        if (!empty($deletedCoursesTodo)) {
            foreach ($deletedCoursesTodo as $courses_id) {
                model('ClassesModel')->deleteClassesCourses2do($class_id, $courses_id);
            }

        }
        return redirect()->back()->with('message', 'properties were edited successfully!');

    }

}
