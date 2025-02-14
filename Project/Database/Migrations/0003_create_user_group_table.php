<?php

namespace Database\Migrations;

use Core\Database;

class CreateUserGroupTable
{
    public function up(): void
    {
        echo "Creating user_group table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("
            CREATE TABLE user_group (
                user_id INTEGER NOT NULL,
                group_id INTEGER,
                read_only BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                PRIMARY KEY (user_id, group_id),
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (group_id) REFERENCES groups(id) ON DELETE CASCADE
            );
        ");

        echo "user_group table created.\n";
    }

    public function down(): void
    {
        echo "Dropping user_group table...\n";

        $pdo = Database::getConnection();
        $pdo->exec("DROP TABLE IF EXISTS user_group");

        echo "user_group table dropped.\n";
    }
}