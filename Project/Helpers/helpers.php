<?php
use Core\View;

function view($view, $data = []) {
    return View::make($view, $data);
}