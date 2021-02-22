<?php


namespace App\Controllers;

use App\Models\UserModel;


/**
 * Students is responsible for the management of the students.
 */
class Students extends BaseController
{    
    /**
     * index displays all active students.
     *
     * @return void
     */
    public function index() {
        if (!in_groups('admins')) {
            return redirect()->to('/admin');
        }
        $userModel = model('UserModel');
        $classModel = model('ClassesModel');
        $allStudentsInSchool = $userModel->findActiveStudents();
        // return var_dump($allStudentsInSchool);


        $data = [
            'students' => $allStudentsInSchool,
            'classModel' => $classModel,
        ];
        return view('students/showactivestudents', $data);
    }


    
    /**
     * editstudent gets all the information needet to edit a selected student. Like the personal info about the student, his current class and other avalible classes.
     *
     * @return void
     */
    public function editstudent() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }

        $userModel = model('UserModel');
        $classModel = model('ClassesModel');
        $classes = $classModel->findAllDesc();
       
        // get the students/users id
        $studentid =  $this->request->getVar('studentid');
        
        // get the students/users classes_id
        $student_classes_id =  $this->request->getVar('student_classes_id');

        //      Make sure, the user sent something and did not just refresh the page
        if (empty($studentid) || (empty($student_classes_id) && empty($studentid)) ){
            return redirect()->to('/students');
        }

        // get the classname by users.classses_id
        if (!empty($student_classes_id)) {
            $studentsClassname = ($classModel->find($student_classes_id)->name);
            $studentsClassId = ($classModel->find($student_classes_id)->id);
        } else {
            $studentsClassname = null;
            $studentsClassId = null;
        }
        

        $student = $userModel->find($studentid);

        // return var_dump($student->username);
        
        $data = [
            'student' => $student,
            'classes' => $classes,
            'studentsClassname' => $studentsClassname,
            'studentsClassId' => $studentsClassId,
        ];
        return view('students/editstudent', $data);

    }





    
    /**
     * updateStudent saves the changes made to a student into the databse.
     *
     * @return void
     */
    public function updateStudent() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classesModel = model('ClassesModel');
        $userModel = model('UserModel');

        

        // Validate here
        $val = $this->validate([
            'username' => 'required|max_length[50]',
            'firstname' => 'required|max_length[80]',
            'lastname' => 'required|max_length[80]',
            'email' => 'required|valid_email',
            'selectedClass' => 'required|max_length[90]',
        ]);


        if (! $val)
        {
            // return 'ach hier';
            // return redirect()->to('/students')->with('message', 'foo');
            return redirect()->to('/students')->withInput()->with('errors', service('validation')->getErrors());
        }



        // get data from form
        $id = $this->request->getVar('userid');
        $username = $this->request->getVar('username');
        $firstName = $this->request->getVar('firstname');
        $lastName = $this->request->getVar('lastname');
        $email = $this->request->getVar('email');
        $selectedClass = $this->request->getVar('selectedClass');
        $oldclass = $this->request->getVar('oldclass');
        


        


        $data = [
            'id' => $id,
            'username' => $username,
            'firstname' => $firstName,
            'lastname' => $lastName,
            'email' => $email,
            'classes_id' => $selectedClass,
        ];

        // update user-table
        $userModel->save($data);

        //get the number of enrolled_students by counting selected classes_id from users tabele
        $enrolled_students = count($userModel->findAllStudentsByID($selectedClass));

        // update the classes table with the new enrolled_students value
        $classesModel->update($selectedClass, [
            'enrolled_students' => $enrolled_students,
        ]);



        if (!empty($oldclass)) {
             //get the number of enrolled_students by counting selected classes_id from users tabele
            $enrolled_students_oldclass = count($userModel->findAllStudentsByID($oldclass));

             // update the classes table with the new enrolled_students value
            $classesModel->update($oldclass, [
            'enrolled_students' => $enrolled_students_oldclass,
        ]);
        }



        return redirect()->back()->with('message', 'Successfully made submitted changes');

        
        
        

    }



}