<?php

namespace App\Interfaces\Admin;

interface AdminUsersInterface
{
    /**
     * Load all users using pagination
     * 
     * @method  POST    api/admin/users/load/all
     * @access  public
     */
    public function loadAllUsers();
}