<?php
namespace App\Controllers;

use App\Services\ImageService;
use App\Services\Auth;

class ImageController
{
  public function index($slug) {}

  public function show($id) {
    try {

      $imageService = new ImageService;
      $imageService->serve($id, Auth::id());
    } catch (\Exception $e) {
      header('HTTP/1.0 403 Forbidden');
      echo $e->getMessage();
    }
}

}
