<?php namespace App\Controllers;


/**
 * Rooms is responsible for creation and management of (class)rooms.
 */
class Rooms extends BaseController
{    
    /**
     * index shows all avalible rooms
     *
     * @return void
     */
    public function index()
    {
        if (! (in_groups('admins'))) {
            return redirect()->to('/');
        }
        $roomModel = model('RoomModel');
        $data = [
            'rooms' => $roomModel->paginate(),
        ];

        return view('rooms/rooms', $data);
        
    }

    
    /**
     * addroom just redirects to the addroom view
     *
     * @return void
     */
    public function addroom() {
        if ( !( in_groups('admins') ) ) {
            return redirect()->to('/');
        }
        return view('rooms/addroom');
    }




    
    /**
     * insertroom inserts a new room into the room table with data from the addroom-form and validates before.
     *
     * @return void
     */
    public function insertroom(){
        if (! (in_groups('admins') )) {
            return redirect()->to('/');
        }
        $roomModel = model('RoomModel');

        // Validate here

        if (! $this->validate($roomModel->validationRules))
        {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $roomModel->save([
            'name' => $this->request->getVar('name'),
            'capacity'  => $this->request->getVar('capacity'),
            'installed_equipment'  => $this->request->getVar('installed_equipment'),
        ]);
        return view('/rooms/addroom');
    }

    
    /**
     * editroom redirects to the editroom-form after verification that user is an admin and that rommid exists.
     *
     * @param  mixed $roomid
     * @return void
     */
    public function editroom($roomid = null) {
        if (!in_groups('admins') || empty($roomid) || empty(model('RoomModel')->find($roomid)) ) {
            return redirect()->to('/');
        }
        $roomModel = model('RoomModel');
        $data = [
            'room' => $roomModel->find($roomid),
        ];

        return view('rooms/editroom', $data);
    }

    
    /**
     * updateroom writes the changes made to a room into the database.
     *
     * @return void
     */
    public function updateroom() {
        if (! (in_groups('admins'))) {
            return redirect()->to('/');
        }
        $roomModel = model('RoomModel');

        // return $this->request->getVar('installed_equipment');

        // Validate here
        $val = $this->validate(['name' => 'required|max_length[20]',
        'capacity' => 'permit_empty|less_than[30]',
        'installed_equipment' => 'permit_empty|max_length[255]',
    
    ]);

        if (! $val)
        {
            return redirect()->back()->withInput()->with('errors', service('validation')->getErrors());
        }
        $data= [
            'id' => $this->request->getVar('room_id'),
            'name' => $this->request->getVar('name'),
            'capacity'  => $this->request->getVar('capacity'),
            'installed_equipment'  => $this->request->getVar('installed_equipment'),
        ];

        $roomModel->save($data);


        return redirect()->back();

    }


    
    /**
     * deleteroom deletes a room.
     *
     * @return void
     */
    public function deleteroom() {
        if (! (in_groups('admins'))) {
            return redirect()->to('/');
        }
        $id= $this->request->getVar('room');
        $roomModel = model('RoomModel');
        $roomModel->delete($id);
        $roomModel->purgeDeleted();
        return redirect()->route('rooms');
    }


    
}
