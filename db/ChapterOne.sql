CREATE DATABASE IF NOT EXISTS chapter_one;
USE chapter_one;

CREATE TABLE USER  (
    ID INT AUTO_INCREMENT NOT NULL,
    first_name VARCHAR(100) NOT NULL,
    last_name VARCHAR(100) NOT NULL,
    email VARCHAR(100) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    constraint ID_USER primary key (ID)
);

CREATE TABLE CUSTOMER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    customer_id INT UNIQUE NOT NULL,
    address VARCHAR(255) NOT NULL,
    phone VARCHAR(20) NOT NULL,
    FOREIGN KEY (customer_id) REFERENCES USER(ID)
);

CREATE TABLE ADMIN (
    id INT AUTO_INCREMENT PRIMARY KEY,
    admin_id INT UNIQUE NOT NULL,
    FOREIGN KEY (admin_id) REFERENCES USER(ID)
);

CREATE TABLE AUTHOR (
    id INT AUTO_INCREMENT PRIMARY KEY,
    author_id INT UNIQUE NOT NULL,
    FOREIGN KEY (author_id) REFERENCES USER(ID)
);

CREATE TABLE CATEGORY (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL,
);

CREATE TABLE PUBLISHER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    name VARCHAR(100) NOT NULL
);

CREATE TABLE BOOK (
    id INT AUTO_INCREMENT PRIMARY KEY,
    title VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10, 2) NOT NULL,
    cover VARCHAR(255)NOT NULL,
    category_id INT NOT NULL,
    publisher_id INT NOT NULL,
    author_id INT NOT NULL
);



alter table BOOK add constraint FK_CATEGORY_BOOK 
    foreign key (category_id) 
    references CATEGORY(id);

alter table BOOK add constraint FK_AUTHOR_BOOK 
    foreign key (author_id) 
    references AUTHOR(id);

alter table BOOK add constraint FK_PUBLISHER_BOOK 
    foreign key (publisher_id) 
    references PUBLISHER(id);



CREATE TABLE POST (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    publication_date DATETIME NOT NULL,
    author_id INT,
    book_id INT
);

alter table POST add constraint FK_AUTHOR_POST
    foreign key (author_id)
    references (AUTHOR(id));

alter table POST add constraint FK_BOOK_POST
    foreign key (book_id)
    references (BOOK(id));

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
