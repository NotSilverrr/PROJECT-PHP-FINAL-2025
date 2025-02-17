<?php
namespace App\Controllers;

use App\Models\User;
use App\Services\ImageService;
use App\Services\Auth;

class ImageController
{
    private $allowedExtensions = ['jpg', 'jpeg', 'png'];
    private $maxFileSize = 5242880;
    private $uploadBaseDir;

    public function __construct() {
        $this->uploadBaseDir = dirname(dirname(__DIR__)) . '/uploads';
    }

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

    public function save($file, $options = []) {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            return null;
        }

        if ($file['size'] > $this->maxFileSize) {
            return null;
        }

        $defaultOptions = ['subdir' => '', 'filename' => null,'overwrite' => false,'group_id' => null];
        $options = array_merge($defaultOptions, $options);

        $uploadDir = $this->uploadBaseDir;
        if ($options['subdir']) {
            $uploadDir .= '/' . trim($options['subdir'], '/');
            if ($options['group_id']) {
                $uploadDir .= '/' . $options['group_id'];
            }
        }
        $uploadDir = rtrim($uploadDir, '/') . '/';

        if (!file_exists($uploadDir)) {
            if (!mkdir($uploadDir, 0777, true)) {
                return null;
            }
        }

        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($fileExtension, $this->allowedExtensions)) {
            return null;
        }

        if ($options['filename']) {
            $filename = $options['filename'] . '.' . $fileExtension;
        } else {
            $filename = uniqid(rand(), true) . '.' . $fileExtension;
        }
        $targetPath = $uploadDir . $filename;

        if (file_exists($targetPath) && !$options['overwrite']) {
            return null;
        }

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            return null;
        }

        return $targetPath;
    }

    public function delete($path) {
        if (file_exists($path)) {
            return unlink($path);
        }
        return false;
    }

    
    
      public function showUserPicture($id) {

        $user = User::findOneById($id);
        if (!$user) {
            return view('errors.404');
        }
        $path = $user->profile_picture;
        if (!file_exists(__DIR__ . "/../../".$path)) {
            return view('errors.404');
        }
        ImageService::serve($path);
      }
  }



