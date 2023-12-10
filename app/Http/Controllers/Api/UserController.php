<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use GuzzleHttp\Psr7\Request;

class UserController extends Controller
{
    public function index()
    {
        try {
            $dataUser = User::all();

            return response()->json([
                'success' => true,
                'message' => 'list user successfully',
                'users' => $dataUser
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'failed to list user',
                'error message' => $e->getMessage()
            ]);
        }
    }
}
