<?php
use App\Core\View;

function view($view, $data = []) {
    return View::make($view, $data);
}