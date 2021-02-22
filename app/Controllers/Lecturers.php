<?php namespace App\Controllers;

/**
 * Lecturers is responsible for the management of the lecturers.
 */
class Lecturers extends BaseController
{    
    /**
     * index shows an overview of all active lecturers and their current dateOfCourse, or thier next dateOfcourse, if there is nor current dateOfCourse.
     *
     * @return void
     */
    public function index()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/admin');
        }
        $userModel = model('UserModel');
        $classModel = model('ClassesModel');
        $lecturers = $userModel->findActiveLecturers();

        $datesModel = model('DateOfCourseModel');
        foreach ($lecturers as $lecturer) {
            $lecturer->courses = $datesModel->getDateAndCourseByLecturersUserId($lecturer->id);

        }

        $data = [
            'lecturers' => $lecturers,
            'classModel' => $classModel,
        ];
        return view('lecturers/show', $data);
    }
    
    /**
     * editlecturer gets all the editable information of a selected lecturer and anables to edit those in the lecturers/edit view.
     *
     * @param  mixed $lecturer_id
     * @return void
     */
    public function editlecturer($lecturer_id = null)
    {
        if (!in_groups('admins') || empty($lecturer_id)) {
            return redirect()->to('/');
        }
        $lecturers = model('UserModel')->findActiveLecturers();
        $lecturer_ids = [];
        foreach ($lecturers as $lecturer) {
            $lecturer_ids[] = $lecturer->id;
        }
        if (!in_array($lecturer_id, $lecturer_ids)) {
            return redirect()->back();
        }

        $data = [
            'lecturer' => model('UserModel')->find($lecturer_id),
        ];
        return view('lecturers/edit', $data);

    }
    
    /**
     * save updates the changes made (to a lecturer) in the database.
     *
     * @return void
     */
    public function save()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        // Validate here
        $val = $this->validate([
            'username' => 'required|max_length[255]',
            'firstname' => 'required|max_length[255]',
            'lastname' => 'required|max_length[255]',
            'email' => 'required|max_length[255]|valid_email',
        ]);
        if (!$val) {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $user_id = $this->request->getVar('lecturers_id');
        $username = $this->request->getVar('username');
        $firstname = $this->request->getVar('firstname');
        $lastname = $this->request->getVar('lastname');
        $email = $this->request->getVar('email');
        $data = [
            'id' => $user_id,
            'username' => $username,
            'firstname' => $firstname,
            'lastname' => $lastname,
            'email' => $email,
        ];
        model('UserModel')->save($data);

        return redirect()->back()->with('success', 'Update erfolgreich');
    }
}
