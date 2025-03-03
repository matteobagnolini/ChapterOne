CREATE DATABASE IF NOT EXISTS chapter_one;
USE chapter_one;

CREATE TABLE person (
    id INT AUTO_INCREMENT PRIMARY KEY,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL
);

CREATE TABLE user (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE author (
    id INT AUTO_INCREMENT PRIMARY KEY,
    person_id INT
);

CREATE TABLE category (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
    description TEXT
);

CREATE TABLE publisher (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE book (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    cover VARCHAR(255),
    category_id INT,
    publisher_id INT
);

CREATE TABLE author_book (
    author_id INT,
    book_id INT,
    PRIMARY KEY (author_id, book_id)
);

CREATE TABLE post (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    publication_date DATETIME NOT NULL,
    category_id INT,
    book_id INT
);

CREATE TABLE cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subtotal DECIMAL(10, 2) DEFAULT 0,
    last_modified DATETIME DEFAULT CURRENT_TIMESTAMP,
    item_count INT DEFAULT 0,
    user_id INT UNIQUE,
    discount_code_id INT NULL
);

CREATE TABLE book_in_cart (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT,
    book_id INT,
    quantity INT DEFAULT 1
);

CREATE TABLE review (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    book_id INT
);

CREATE TABLE order_table (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    applied_discount DECIMAL(10, 2) DEFAULT 0,
    user_id INT,
    discount_code_id INT NULL
);

CREATE TABLE order_detail (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    order_id INT,
    book_id INT
);

CREATE TABLE discount_code_usage (
    id INT AUTO_INCREMENT PRIMARY KEY,
    discount_code_id INT,
    user_id INT,
    order_id INT,
    usage_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    applied_discount DECIMAL(10, 2) NOT NULL
);

CREATE TABLE discount_code (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    description TEXT,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    single_use BOOLEAN DEFAULT FALSE,
    min_order DECIMAL(10, 2) DEFAULT 0,
    max_discount DECIMAL(10, 2) DEFAULT NULL,
    valid_categories VARCHAR(255) DEFAULT NULL,
    active BOOLEAN DEFAULT TRUE
);
