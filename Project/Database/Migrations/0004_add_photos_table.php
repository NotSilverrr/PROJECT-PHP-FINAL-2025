<?php

namespace Database\Migrations;

use Core\Database;

class CreatePhotosTable
{
    public function up(): void
    {
        echo "Creating photos table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("
            CREATE TABLE photos (
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                file VARCHAR(100) NOT NULL,
                group_id INTEGER NOT NULL,
                user_id INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP,
                share_token VARCHAR(64),
                share_token_expiration TIMESTAMP,
                FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            );
        ");

        echo "photos table created.\n";
    }

    public function down(): void
    {
        echo "Dropping photos table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("DROP TABLE IF EXISTS photos");

        echo "photos table dropped.\n";
    }
}