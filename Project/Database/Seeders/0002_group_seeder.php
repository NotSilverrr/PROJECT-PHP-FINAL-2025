<?php

namespace Database\Seeders;

use Core\Database;

class GroupSeeder
{
    public function run(): void
    {
        echo "Seeding groups...\n";
        $pdo = Database::getConnection();
        
        $stmt = $pdo->prepare("
            INSERT INTO groups (name, profile_picture, owner)
            VALUES 
                ('Developers', 'devs.jpg', 1),
                ('Designers', 'designers.jpg', 2),
                ('Gamers', 'gamers.jpg', 3)
        ");
        $stmt->execute();

        echo "Groups seeded.\n";
    }
}
