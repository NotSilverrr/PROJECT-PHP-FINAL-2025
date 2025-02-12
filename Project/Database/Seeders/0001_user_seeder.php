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
                'first_name' => 'Admin',
                'last_name' => 'User',
                'email' => 'admin@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'admin123'
            ],
            [
                'is_admin' => 0,
                'first_name' => 'Regular',
                'last_name' => 'User',
                'email' => 'user@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'user123'
            ],
            [
                'is_admin' => 0,
                'first_name' => 'Regular2',
                'last_name' => 'User2',
                'email' => 'user2@example.com',
                'profile_picture' => '/profile/image.png',
                'password' => 'user123'
            ]
        ];

        $stmt = $pdo->prepare("
            INSERT INTO users (is_admin, profile_picture, first_name, last_name, email, password, created_at, updated_at)
            VALUES (:is_admin, :profile_picture, :first_name, :last_name, :email, :password, NOW(), NOW())
        ");

        foreach ($users as $user) {
            $stmt->execute([
                'is_admin' => $user['is_admin'],
                'email' => $user['email'],
                'first_name' => $user['first_name'],
                'last_name' => $user['last_name'],
                'profile_picture' => $user['profile_picture'],
                'password' => password_hash($user['password'], PASSWORD_DEFAULT)
            ]);
        }

        echo "Users seeded.\n";
    }
}