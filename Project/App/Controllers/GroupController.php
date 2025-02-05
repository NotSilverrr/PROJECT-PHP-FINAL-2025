<?php
namespace App\Controllers;

class GroupController {
    public function show(int $id) {
        return view('group.index', ['id' => $id]);
    }
}