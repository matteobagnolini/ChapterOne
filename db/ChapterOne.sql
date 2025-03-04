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
    references CATEGORY;

alter table BOOK add constraint FK_AUTHOR_BOOK 
    foreign key (author_id) 
    references AUTHOR;

alter table BOOK add constraint FK_PUBLISHER_BOOK 
    foreign key (publisher_id) 
    references PUBLISHER;



CREATE TABLE POST (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT NOT NULL,
    publication_date DATETIME NOT NULL,
    author_id INT,
    book_id INT
);

alter table POST add constraint FK_AUTHOR_POST
    foreign key (author_id)
    references AUTHOR;

alter table POST add constraint FK_BOOK_POST
    foreign key (book_id)
    references BOOK;



CREATE TABLE REVIEW (
    id INT AUTO_INCREMENT PRIMARY KEY,
    text TEXT,
    rating INT CHECK (rating BETWEEN 1 AND 5),
    book_id INT NOT NULL,
    user_id INT NOT NULL
);

alter table REVIEW add constraint FK_BOOK_REVIEW
    foreign key (book_id)
    references BOOK;

alter table REVIEW add constraint FK_USER_REVIEW
    foreign key (user_id)
    references USER;



CREATE TABLE CART (
    id INT AUTO_INCREMENT PRIMARY KEY,
    subtotal DECIMAL(10, 2) DEFAULT 0,
    last_modified DATETIME DEFAULT CURRENT_TIMESTAMP,
    item_count INT DEFAULT 0,
    user_id INT NOT NULL
);

alter table CART add constraint FK_USER_CART
    foreign key (user_id)
    references CUSTOMER;



CREATE TABLE BOOK_IN_CART (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cart_id INT NOT NULL,
    book_id INT NOT NULL,
    quantity INT DEFAULT 1
);

ALTER TABLE BOOK_IN_CART ADD CONSTRAINT FK_CART_BOOK_IN_CART
    FOREIGN KEY (cart_id)
    REFERENCES CART;

ALTER TABLE BOOK_IN_CART ADD CONSTRAINT FK_BOOK_BOOK_IN_CART
    FOREIGN KEY (book_id)
    REFERENCES BOOK;



CREATE TABLE ORDER (
    id INT AUTO_INCREMENT PRIMARY KEY,
    date DATETIME DEFAULT CURRENT_TIMESTAMP,
    total DECIMAL(10, 2) NOT NULL,
    user_id INT NOT NULL,
    discount_code_id INT NULL
);


ALTER TABLE ORDER ADD CONSTRAINT FK_USER_ORDER
    FOREIGN KEY (user_id)
    REFERENCES CUSTOMER;

ALTER TABLE ORDER ADD CONSTRAINT FK_DISCOUNT_CODE_ORDER
    FOREIGN KEY (discount_code_id)
    REFERENCES DISCOUNT_CODE;




CREATE TABLE ORDER_DETAIL (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quantity INT NOT NULL,
    subtotal DECIMAL(10, 2) NOT NULL,
    order_id INT NOT NULL,
    book_id INT NOT NULL
);


ALTER TABLE ORDER_DETAIL ADD CONSTRAINT FK_ORDER_ORDER_DETAIL
    FOREIGN KEY (order_id)
    REFERENCES ORDER_TABLE;

ALTER TABLE ORDER_DETAIL ADD CONSTRAINT FK_BOOK_ORDER_DETAIL
    FOREIGN KEY (book_id)
    REFERENCES BOOK;



CREATE TABLE DISCOUNT_CODE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    code VARCHAR(50) UNIQUE NOT NULL,
    type ENUM('percentage', 'fixed') NOT NULL,
    value DECIMAL(10, 2) NOT NULL,
    start_date DATE NOT NULL,
    end_date DATE NOT NULL,
    single_use BOOLEAN DEFAULT FALSE,
    active BOOLEAN DEFAULT TRUE
);

CREATE TABLE DISCOUNT_CODE_USAGE (
    id INT AUTO_INCREMENT PRIMARY KEY,
    usage_date DATETIME DEFAULT CURRENT_TIMESTAMP,
    discount_code_id INT,
    user_id INT,
    order_id INT,
);

alter table DISCOUNT_CODE_USAGE add constraint FK_DISCOUNT_CODE_USAGE
    foreign key (discount_code_id)
    references DISCOUNT_CODE;

alter table DISCOUNT_CODE_USAGE add constraint FK_USER_DISCOUNT_CODE_USAGE
    foreign key (user_id)
    references CUSTOMER;


alter table DISCOUNT_CODE_USAGE add constraint FK_ORDER_DISCOUNT_CODE_USAGE
    foreign key (order_id)
    references ORDER_TABLE;



-- TRIGGER -- 


CREATE TRIGGER after_insert_book_in_cart
AFTER INSERT ON BOOK_IN_CART
FOR EACH ROW
BEGIN
    UPDATE CART 
    SET item_count = item_count + NEW.quantity, 
        last_modified = CURRENT_TIMESTAMP
    WHERE id = NEW.cart_id;
END;



CREATE TRIGGER after_delete_book_in_cart
AFTER DELETE ON BOOK_IN_CART
FOR EACH ROW
BEGIN
    UPDATE CART 
    SET item_count = item_count - OLD.quantity, 
        last_modified = CURRENT_TIMESTAMP
    WHERE id = OLD.cart_id;
END;


CREATE TRIGGER after_insert_order
AFTER INSERT ON ORDERS
FOR EACH ROW
BEGIN
    -- Eliminare tutti i libri nel carrello dell'utente
    DELETE FROM BOOK_IN_CART 
    WHERE cart_id = (SELECT id FROM CART WHERE user_id = NEW.user_id);

    -- Azzerare il carrello dell'utente
    UPDATE CART 
    SET subtotal = 0, item_count = 0, last_modified = CURRENT_TIMESTAMP
    WHERE user_id = NEW.user_id;
END;