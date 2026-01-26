PRAGMA foreign_keys = ON;

CREATE TABLE IF NOT EXISTS category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS streaming (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    logo_image_id INTEGER,
    FOREIGN KEY (logo_image_id) REFERENCES media(id)
);

CREATE TABLE IF NOT EXISTS person (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    name VARCHAR(255) NOT NULL,
    type INTEGER
);

CREATE TABLE IF NOT EXISTS media (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    src VARCHAR(255) NOT NULL,
    alt VARCHAR(255)
);


CREATE TABLE IF NOT EXISTS show (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    type INTEGER,
    production_date DATE,
    number_of_episodes INTEGER,
    cover_image_id INTEGER,
    background_image_id INTEGER,
    director_id INTEGER,
    FOREIGN KEY (cover_image_id) REFERENCES media(id),
    FOREIGN KEY (background_image_id) REFERENCES media(id),
    FOREIGN KEY (director_id) REFERENCES person(id)
);

CREATE TABLE IF NOT EXISTS show_streaming (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    show_id INTEGER,
    streaming_id INTEGER,
    link_to_show VARCHAR(255),
    FOREIGN KEY (show_id) REFERENCES show(id) ON DELETE CASCADE,
    FOREIGN KEY (streaming_id) REFERENCES streaming(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS show_category (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    show_id INTEGER,
    category_id INTEGER,
    FOREIGN KEY (show_id) REFERENCES show(id) ON DELETE CASCADE,
    FOREIGN KEY (category_id) REFERENCES category(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS show_actor (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    show_id INTEGER,
    person_id INTEGER,
    FOREIGN KEY (show_id) REFERENCES show(id) ON DELETE CASCADE,
    FOREIGN KEY (person_id) REFERENCES person(id) ON DELETE CASCADE
);

CREATE TABLE IF NOT EXISTS rating (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    value INTEGER,
    show_id INTEGER,
    FOREIGN KEY (show_id) REFERENCES show(id) ON DELETE CASCADE
);