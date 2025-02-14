<?php

namespace Database\Migrations;

use Core\Database;

class CreateGroupsTable
{
    public function up(): void
    {
        echo "Creating groups table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("
            CREATE TABLE groups (
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                name VARCHAR(50) NOT NULL,
                profile_picture VARCHAR(100),
                owner INTEGER NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP,
                FOREIGN KEY (owner) REFERENCES users(id) ON DELETE CASCADE
            );
        ");

        echo "Groups table created.\n";
    }

    public function down(): void
    {
        echo "Dropping groups table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("DROP TABLE IF EXISTS groups");

        echo "Groups table dropped.\n";
    }
}