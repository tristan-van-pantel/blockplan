<?php

namespace App\Controllers;

use Myth\Auth\Authorization\GroupModel;

/**
 * The Admin Controller manages the users groups (roles), their status (active, inactive) and allows the admins to delete users
 */

class Admin extends BaseController
{
 /**
  * Admins can see all the applications users
  @return App\Views
  */
    public function index()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $data = [
            'users' => $users = new \Myth\Auth\Models\UserModel(),
        ];
        return view('/admin/users', $data);

    }
/**
 * Redirects the admin to the selected users edit view. (After validation)
 */
    public function edit($id = null)
    {
        if (!in_groups('admins') || empty($id) || empty(model('UserModel')->find($id))) {
            return redirect()->to('/');
        }
        $users = model('UserModel');
        $roleMoldel = new GroupModel();
        $roles = $roleMoldel->findAll();

        $user = $users->find($id);
        $data = [
            'id' => $id,
            'user' => $user,
            'roles' => $roles,
            'userroles' => $user->getRoles(),
        ];
        return view('/admin/edit', $data);
    }
/**
 * Updates the users groups (roles). Allows only admins do do so, and dows not allow other admins, to de-admin the superuser.
 */
    public function update()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }

//        Find the user we want to update with the help of the UserModel
        $users = new \Myth\Auth\Models\UserModel();
        $id = $this->request->getVar('userid');
        $user = $users->find($id);

//        Find all groups (roles) that exist
        $roleMoldel = new GroupModel();
        $roles = $roleMoldel->findAll();

//        Get the groups the user selected or deselected
        $selectedRoles = $this->request->getVar('roles[]');

//      Make sure, the user sent something and did not just refresh the page
        if (empty($id) || empty($selectedRoles)) {
            return redirect()->to('/admin');
        }


        // if the current user, who cant not-not be an admin at this point, trie to edits herself/himself, or any admin tries to edit user number one, do not allow to de-admin
        if ((user_id() == $id) || ($id == 1)) {
            if (in_array('1', $selectedRoles)) {
                $roleMoldel->removeUserFromAllGroups($id);
                foreach ($selectedRoles as $groupid) {
                    $roleMoldel->addUserToGroup($id, $groupid);

                }
            } else {
                return redirect()->back()->with('error', 'Sie können sich nicht selbst die Admin-Rolle nehmen');
            }
        }
        // the user is not editing /herself/himself ans is also not editing the user with id == 1 (superadmin, root-user), so de-admin is allowed
        $roleMoldel->removeUserFromAllGroups($id);
        foreach ($selectedRoles as $groupid) {
            $roleMoldel->addUserToGroup($id, $groupid);

        }

        return redirect()->back()->with('success', 'Rollen erfolgreich aktualisiert');

    }

    /**
     * Deletes the selected user.
     */
    public function destroy()
    {
        if (!in_groups('admins')) {
            return redirect()->to('/admin');
        }
        $userId = $this->request->getVar('user');

        if (user_id() == $userId || $userId == 1) {
            // return 'hallo';
            return redirect()->to('/admin')->with('error', 'Sie können sich selbst, oder den Root-Nutzer nicht löschen.');
        }
       


        $userModel = new \Myth\Auth\Models\UserModel();
        $userModel->delete($userId);
        $userModel->purgeDeleted();
        return redirect()->to('/admin');

    }

/**
 * Deactivates the seleced user. This shoud always be prefered over deytroy (delete). Because the user will still be in the database for archivation resons, but will nomore be able to use the application.
 */
    public function deactivate() {
        if (!in_groups('admins')) {
            return redirect()->to('/');
        }
        $id = $this->request->getVar('user');
        $active = $this->request->getVar('active');

        if (user_id() == $id || $id == 1 ) {
            return redirect()->to('/admin')->with('error', 'Sie können sich selbst, oder den Root-Nutzer nicht deaktivieren.');
        }

        $data = [
            'active' => !$active
        ];

        if (!empty($id)) {
            model('UserModel')->update($id, $data);
            return redirect()->to('/admin')->with('success', 'Benutzer wurde deaktiviert.');
        }
        
        
    }
}
