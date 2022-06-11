<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\ResponseAPI;
use App\Interfaces\Admin\AdminUsersInterface;

class AdminUsersController extends Controller
{
    use ResponseAPI;

    protected $adminUsersInterface;

    public function __construct(AdminUsersInterface $adminUsersInterface)
    {
        $this->adminUsersInterface = $adminUsersInterface;
    }

    public function loadAllUsers()
    {
        $response = $this->adminUsersInterface->loadAllUsers();

        return $this->success($response);
    }
}