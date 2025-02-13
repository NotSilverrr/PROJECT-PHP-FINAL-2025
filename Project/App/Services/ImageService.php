<?php
namespace App\Services;

use Core\QueryBuilder;

class ImageService {
    private QueryBuilder $query;
    
    public function __construct() {
        $this->query = new QueryBuilder();
    }
    
    public function serve($imageId, $userId) {
        // Vérifier l'accès à l'image et son groupe
        $image = $this->query
            ->select()
            ->from('photos')
            ->join('user_group', 'user_group.group_id', '=', 'photos.group_id')
            ->where('photos.id', '=', $imageId)
            ->andWhere('user_group.user_id', '=', $userId)
            ->fetch();

        // print_r($image);
            
        if (!$image) {
            throw new \Exception("Image non trouvée ou accès non autorisé");
        }
        
        $path = __DIR__ .'/../../uploads/groups/' . $image['group_id'] . '/' . $image['file'];

        // print_r($path);
        
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
    
    public function save($groupId, $userId, $file) {
        // Vérifier l'appartenance au groupe
        $member = $this->query
            ->select()
            ->from('group_members')
            ->where('user_id', '=', $userId)
            ->andWhere('group_id', '=', $groupId)
            ->fetch();
            
        if (!$member) {
            throw new \Exception("Non autorisé");
        }
        
        // Gérer l'upload du fichier
        $uploadDir = '/uploads/groups/' . $groupId . '/';
        if (!file_exists($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        $filename = uniqid() . '_' . basename($file['name']);
        $targetPath = $uploadDir . $filename;
        
        if (move_uploaded_file($file['tmp_name'], $targetPath)) {
            // Insérer dans la base de données
            return $this->query
                ->insert()
                ->into('photos', ['group_id', 'file_path', 'uploaded_by', 'created_at'])
                ->values([
                    $groupId,
                    $filename,
                    $userId,
                    date('Y-m-d H:i:s')
                ])
                ->execute();
        }
        
        throw new \Exception("Erreur lors de l'upload");
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