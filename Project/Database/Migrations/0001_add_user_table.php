<?php

namespace Database\Migrations;

use Core\Database;

class CreateUsersTable
{
    public function up(): void
    {
        echo "Creating users table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("
            CREATE TABLE users (
                id INTEGER PRIMARY KEY AUTO_INCREMENT,
                is_admin BOOLEAN DEFAULT FALSE,
                profile_picture VARCHAR(100),
                email VARCHAR(320) NOT NULL UNIQUE,
                password VARCHAR(64) NOT NULL,
                created_at DATE,
                updated_at DATE
            );
        ");

        echo "Users table created.\n";
    }

    public function down(): void
    {
        echo "Dropping users table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("DROP TABLE IF EXISTS users");

        echo "Users table dropped.\n";
    }
}