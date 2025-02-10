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
        
        $users = [
            [
                'is_admin' => 1,
                'email' => 'admin@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'admin123'
            ],
            [
                'is_admin' => 0,
                'email' => 'user@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'user123'
            ],
            [
                'is_admin' => 0,
                'email' => 'user2@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'user123'
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO users (is_admin, profile_picture, email, password, created_at, updated_at)
            VALUES (:is_admin, :profile_picture, :email, :password, NOW(), NOW())
        ");

        foreach ($users as $user) {
            $stmt->execute([
                'is_admin' => $user['is_admin'],
                'email' => $user['email'],
                'profile_picture' => $user['profile_picture'],
                'password' => password_hash($user['password'], PASSWORD_DEFAULT)
            ]);
        }

        echo "Users seeded.\n";
    }
}