<?php

namespace App\Http\Controllers\Main;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display current user data.
     */
    public function me(Request $request)
    {
        return $request->user();
    }
}
