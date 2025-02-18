<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ImageService;

class UserController {
    public function showProfilePicture($id) {
        $user = User::findOneById($id);

        if (!$user) {
            return view('errors.404');
        }
        $path = $user->profile_picture;
        

        // if (!file_exists($path)) {
        //     return view('errors.404');
        // }
        ImageService::serve($path);
    }
}