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

  public function showGroupProfilePicture($id) {
    $imageService = new ImageService;
    $imageService->serveGroupProfilePicture($id, Auth::id());
  }

  public function showUserPicture($id) {
    $imageService = new ImageService;
    $imageService->serveUserPicture($id);
  }

}
