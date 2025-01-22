CREATE TABLE Groups (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    name VARCHAR(50) NOT NULL,
    profile_picture VARCHAR(100),
    owner INTEGER NOT NULL,
    created_at DATE,
    updated_at DATE,
    FOREIGN KEY (owner) REFERENCES User(id) ON DELETE CASCADE
);

CREATE TABLE User (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    is_admin BOOLEAN DEFAULT FALSE,
    profile_picture VARCHAR(100),
    email VARCHAR(320) NOT NULL UNIQUE,
    password VARCHAR(64) NOT NULL,
    created_at DATE,
    updated_at DATE
);

CREATE TABLE UserGroup (
    user_id INTEGER NOT NULL,
    group_id INTEGER,
    created_at DATE,
    PRIMARY KEY (user_id, group_id),
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE,
    FOREIGN KEY (group_id) REFERENCES Groups(id) ON DELETE CASCADE
);

CREATE TABLE Photos (
    id INTEGER PRIMARY KEY AUTO_INCREMENT,
    file VARCHAR(100) NOT NULL,
    group_id INTEGER NOT NULL,
    user_id INTEGER NOT NULL,
    created_at DATE,
    updated_at DATE,
    FOREIGN KEY (group_id) REFERENCES Groups(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES User(id) ON DELETE CASCADE
);