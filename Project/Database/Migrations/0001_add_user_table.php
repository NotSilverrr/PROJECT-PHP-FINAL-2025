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
                first_name VARCHAR(100),
                last_name VARCHAR(100),
                profile_picture VARCHAR(100),
                email VARCHAR(320) NOT NULL UNIQUE,
                password VARCHAR(64) NOT NULL,
                reset_token VARCHAR(64),
                reset_token_expiration TIMESTAMP,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP
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