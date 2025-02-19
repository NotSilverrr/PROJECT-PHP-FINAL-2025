<?php
namespace App\Services;

use Core\QueryBuilder;

class ImageService {
    private QueryBuilder $query;
    
    public function __construct() {
        $this->query = new QueryBuilder();
    }
    
    public static function serve($imagePath) {
        $imagePath = __DIR__ . "/../.." . $imagePath;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imagePath);
        finfo_close($finfo);
        
        header("Content-Type: " . $mimeType);
        header("Cache-Control: private, max-age=3600");
        readfile($imagePath);
        exit;
    }

    public function serveUserPicture(int $userId) {
        $query = new QueryBuilder;
        $response = $query->select()->from("users")->where("id","=", $userId)->fetch();
        $path = __DIR__ ."/../../" . $response["profile_picture"];
        if (!file_exists($path)) {
            throw new \Exception("Fichier non trouvé");
        }
        
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $path);

        finfo_close($finfo);
        
        header("Content-Type: " . $mimeType);
        header("Cache-Control: private, max-age=3600");
        readfile($path);
        exit;
    }

    public static function uploadPhoto($file, $uploadDir): ?string
    {
        $uploadDir = __DIR__ . "/../../" . $uploadDir. "/";
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . "_" . basename($file['name']);
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            $relativePath = str_replace(realpath(__DIR__ . "/../../"), '', realpath($uploadPath));
            return $relativePath;
        }

        throw new \Exception("an Error occured") ;
    }
    
    public static function delete($imagePath) {
        
        $imagePath = __DIR__ . "/../.." . $imagePath;
        if (file_exists($imagePath)) {
            unlink($imagePath);
        } else {
            throw new \Exception("Fichier non trouvé");
        }

    }
    
    public function getGroupImages($groupId, $userId) {
        return $this->query
            ->select(['images.*', 'users.username as uploaded_by_username'])
            ->from('images')
            ->join('users', 'users.id', '=', 'images.uploaded_by')
            ->join('group_members', 'group_members.group_id', '=', 'images.group_id')
            ->where('images.group_id', '=', $groupId)
            ->andWhere('group_members.user_id', '=', $userId)
            ->fetchAll();
    }
    
    public function getImage($imageId) {
        return $this->query
            ->select()
            ->from('images')
            ->where('id', '=', $imageId)
            ->fetch();
    }
}