<?php
namespace App\Services;

use Core\QueryBuilder;

class ImageService {
    private QueryBuilder $query;
    
    public function __construct() {
        $this->query = new QueryBuilder();
    }
    
    public static function serve($imagePath) {
        $imagePath = __DIR__ . "/../../" . $imagePath;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mimeType = finfo_file($finfo, $imagePath);
        finfo_close($finfo);
        
        header("Content-Type: " . $mimeType);
        header("Cache-Control: private, max-age=3600");
        readfile($imagePath);
        exit;
    }

    public function serveGroupProfilePicture(int $groupId, int $userId) {
        $query = new QueryBuilder;
        
        $result = $query->select()->from("user_group")->where("user_id", "=", $userId)->andWhere("group_id", "=", $groupId)->fetch();
        
        if (!$result) {
            throw new \Exception("Vous ne faites pas parti de ce groupe");
        }
        $query = new QueryBuilder;
        $response = $query->select()->from("groups")->where("id","=", $groupId)->fetch();

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

    public static function uploadPhoto($file, $groupId): ?string
    {
        $uploadDir = __DIR__ . "/../../uploads/groups/{$groupId}/";
        
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true);
        }

        $filename = uniqid() . "_" . basename($file['name']);
        $uploadPath = $uploadDir . $filename;

        if (move_uploaded_file($file['tmp_name'], $uploadPath)) {
            return "uploads/groups/{$groupId}/" . $filename; // Chemin stocké en BDD
        }

        return null;
    }
    
    public function delete($imageId, $userId) {
        // Vérifier que l'utilisateur est propriétaire de l'image
        $image = $this->query
            ->select()
            ->from('photos')
            ->where('id', '=', $imageId)
            ->andWhere('uploaded_by', '=', $userId)
            ->fetch();
            
        if (!$image) {
            throw new \Exception("Image non trouvée ou non autorisée");
        }
        
        // Supprimer le fichier physique
        $path = '/uploads/groups/' . $image['group_id'] . '/' . $image['file_path'];
        if (file_exists($path)) {
            unlink($path);
        }
        
        // Supprimer l'enregistrement
        return $this->query
            ->delete()
            ->from('images')
            ->where('id', '=', $imageId)
            ->execute();
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