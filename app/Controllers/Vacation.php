<?php namespace App\Controllers;

use App\Models\VacationModel;

/**
 * Vacation is responsible for the management of vacations and holidays.
 */
class Vacation extends BaseController
{    
    /**
     * index gives an overview over all vacations
     *
     * @return void
     */
    public function index()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $vacationModel = model('VacationModel');
        $vacations = $vacationModel->findAll();
        // return d($vacations);

        $data = [
            'vacationModel' => $vacationModel,
            'vacations' => $vacations,
        ];
        return view('vacation/showvacation', $data);
    }

    
    /**
     * addVacation redirects to the form, that allows to connect vacations, time an classes.
     *
     * @return void
     */
    public function addVacation() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $classes = model('ClassesModel')->findAll();

        $data = [
            'classes' => $classes,
        ];
        return view('vacation/addvacation', $data);

    }

    
    /**
     * insertvacation inserts a new vacation into the vacation table and then inserts all associated classes into the classesvacation assiciation table.
     *
     * @return void
     */
    public function insertvacation() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
             // Validate here
            $val = $this->validate([
            'name' => 'required|max_length[255]',
            'begin' => 'required|valid_date',
            'end' => 'required|valid_date',
            ]);
        
        
            if (! $val)
                {
                    return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
                }
        $name = $this->request->getVar('name');
        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');
        $affected_classes = $this->request->getVar('affected_classes');

        if ($begin > $end) {
            return redirect()->back()->with('error', 'end-date must be greater or equal begin-date');
        }

        $vacationModel = model('VacationModel');

       $vacations_id = $vacationModel->insert([
            'name' => $name,
            'begin'  => $begin,
            'end' => $end,
       ], true);

       if (!empty($affected_classes) && !empty($vacations_id)) {
          foreach ($affected_classes as $classes_id) {
              // insertclassesvacations($classes_id, $vacations_id) : void
              $vacationModel->insertclassesvacations($classes_id, $vacations_id);
          }
       }
       return redirect()->back()->with('message', 'inserted successfully!');

        
    }

    
    /**
     * deletevacation deletes a selected vacation from the database.
     *
     * @return void
     */
    public function deletevacation() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $vacationModel = model('VacationModel');
        $id = $this->request->getVar('vacation_id');
        if (!empty($id)) {
            $vacationModel->delete($id);
            $vacationModel->purgeDeleted();
            return redirect()->back();
        }
    }
    
    /**
     * editVacation gets all the needed information for editing vacations (vacation name, time, associated classes) and redirects to the edit form.
     *
     * @param  mixed $vacation_id
     * @return void
     */
    public function editVacation($vacation_id = null) {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        if (empty($vacation_id)) {
            return redirect()->back();
        }
        $vacations = model('VacationModel')->findAll();
        $vacactionIds = [];
        foreach ($vacations as $vacation) {
            $vacactionIds[] = $vacation->id;
        }
        if (!in_array($vacation_id, $vacactionIds)) {
            return redirect()->back();
        }
        $vacation = model('VacationModel')->find($vacation_id);
        $classes = model('ClassesModel')->findAll();
        $oldClasses = model('VacationModel')->findClassByVacationId($vacation_id);
        $oldClassIds = [];
        if (!empty($oldClasses)) {
            foreach ($oldClasses as $oldclass) {
                $oldClassIds[] = $oldclass->id;
            }
        }


        $data = [
            'vacation' => $vacation,
            'classes' => $classes,
            'oldClassIds' => $oldClassIds,
        ];
        return view('vacation/editvacation', $data);

    }
    
    /**
     * insertVacationEdit inserts the changes made to a vacation into the database.
     *
     * @return void
     */
    public function insertVacationEdit() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $vacation_id = $this->request->getVar('vacation_id');
        $oldClasses = model('VacationModel')->findClassByVacationId($vacation_id);
        $oldClassIds = [];
        if (!empty($oldClasses)) {
            foreach ($oldClasses as $oldclass) {
                $oldClassIds[] = $oldclass->id;
            }
        }

         // Validate here
        $val = $this->validate([
            'name' => 'required|max_length[255]',
            'begin' => 'required|valid_date',
            'end' => 'required|valid_date',
            ]);
            
            
        if (! $val)
            {
                return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
            }
        $name = $this->request->getVar('name');
        $begin = $this->request->getVar('begin');
        $end = $this->request->getVar('end');
        $affected_classes = $this->request->getVar('affected_classes');

        if ($begin > $end) {
            return redirect()->back()->with('error', 'end-date must be greater or equal begin-date');
        }

    $vacationModel = model('VacationModel');

    $data = [
    'name' => $name,
    'begin'  => $begin,
    'end' => $end,
    ];

    $id = $vacation_id;

        $vacationModel->update($id, $data);

        // find out, which of the former selected classes dit not make it into the new selection
        $deletedClasses = [];

        foreach ($oldClassIds as $oldClass) {
                 if (!in_array($oldClass, $affected_classes) ) {
                    $deletedClasses[] = $oldClass;
                }
            }

        // loop through  $affected_classes (the classes the user selected) and get rid of the classes that were selected before edit
        if (!empty($affected_classes) && !empty($oldClassIds)) {
            foreach ($oldClassIds as $oldClass) {            
                if (($key = array_search($oldClass, $affected_classes)) !== false) {
                     unset($affected_classes[$key]);
                }
            }
        }

        // insert the newly added classes (if they exist) into the classesvacations relation table
       if (!empty($affected_classes)) {
        foreach ($affected_classes as $class) {
            //  insertclassesvacations($classes_id, $vacations_id) : void
            model('VacationModel')->insertclassesvacations($class, $vacation_id);
            }
       }

       if (!empty($deletedClasses)) {
        foreach ($deletedClasses as $deletedClass) {
            // deleteclassesvacations($classes_id, $vacations_id) : void 
            model('VacationModel')->deleteclassesvacations($deletedClass, $vacation_id);
        }
    }
    return redirect()->back()->with('message', 'inserted successfully!');





    }
}