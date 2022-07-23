-- MySQL database of Amazing Comics
CREATE DATABASE IF NOT EXISTS amazing_comics;

USE amazing_comics;

-- Users table
CREATE TABLE users(
    id INT(255) AUTO_INCREMENT NOT NULL,
    name VARCHAR(50) NOT NULL,
    surname VARCHAR(100),
    role VARCHAR(30),
    email VARCHAR(255) NOT NULL,
    password VARCHAR(255) NOT NULL,
    description TEXT,
    image VARCHAR 255,
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    remember_token VARCHAR(255),
    confirmed VARCHAR(255),
    CONSTRAINT pk_users PRIMARY KEY(id)
) ENGINE=InnoDb;

-- Categories table
CREATE TABLE categories (
    id INT(255) AUTO_INCREMENT NOT NULL,
    name VARCHAR(100),
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT pk_categories PRIMARY KEY(id)
) ENGINE=InnoDb;

-- Posts table
CREATE TABLE posts (
    id INT(255) AUTO_INCREMENT NOT NULL,
    user_id INT(255) NOT NULL,
    category_id INT(255) NOT NULL,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image VARCHAR(255),
    created_at DATETIME DEFAULT NULL,
    updated_at DATETIME DEFAULT NULL,
    CONSTRAINT pk_posts PRIMARY KEY(id),
    CONSTRAINT fk_post_user FOREIGN KEY(user_id) REFERENCES users(id),
    CONSTRAINT fk_post_category FOREIGN KEY(category_id) REFERENCES categories(id)
) ENGINE=InnoDb;