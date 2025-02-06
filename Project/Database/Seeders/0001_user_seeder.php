<?php

namespace Database\Seeders;

use Core\Database;
use PDO;

class UserSeeder
{
    public function run(): void
    {
        echo "Seeding users...\n";
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("
            INSERT INTO users (is_admin, profile_picture, email, password, created_at, updated_at)
            VALUES 
                (1, NULL, 'admin@example.com', 'password_hash', NOW(), NOW()),
                (0, NULL, 'user@example.com', 'password_hash', NOW(), NOW()),
                (0, NULL, 'user2@example.com', 'password_hash', NOW(), NOW())
        ");
        $stmt->execute();
        echo "Users seeded.\n";
    }
}