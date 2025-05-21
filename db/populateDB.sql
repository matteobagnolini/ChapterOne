USE Chapter_one;

DELETE FROM BOOK_IN_CART;
DELETE FROM CART;
DELETE FROM REVIEW;
DELETE FROM ORDER_DETAIL;
DELETE FROM `ORDER`;
DELETE FROM DISCOUNT_CODE_USAGE;
DELETE FROM DISCOUNT_CODE;
DELETE FROM ORDER_NOTIFICATION;
DELETE FROM ADMIN_ORDER_NOTIFICATION;
DELETE FROM BEST_SELLER;
DELETE FROM POST;
DELETE FROM BOOK;
DELETE FROM CATEGORY;
DELETE FROM AUTHOR;
DELETE FROM PUBLISHER;
DELETE FROM CUSTOMER;
DELETE FROM ADMIN;

ALTER TABLE BOOK_IN_CART AUTO_INCREMENT = 1;
ALTER TABLE CART AUTO_INCREMENT = 1;
ALTER TABLE REVIEW AUTO_INCREMENT = 1;
ALTER TABLE `ORDER` AUTO_INCREMENT = 1;
ALTER TABLE ORDER_DETAIL AUTO_INCREMENT = 1;
ALTER TABLE DISCOUNT_CODE_USAGE AUTO_INCREMENT = 1;
ALTER TABLE DISCOUNT_CODE AUTO_INCREMENT = 1;
ALTER TABLE ORDER_NOTIFICATION AUTO_INCREMENT = 1;
ALTER TABLE ADMIN_ORDER_NOTIFICATION AUTO_INCREMENT = 1;
ALTER TABLE BEST_SELLER AUTO_INCREMENT = 1;
ALTER TABLE POST AUTO_INCREMENT = 1;
ALTER TABLE BOOK AUTO_INCREMENT = 1;
ALTER TABLE CATEGORY AUTO_INCREMENT = 1;
ALTER TABLE AUTHOR AUTO_INCREMENT = 1;
ALTER TABLE PUBLISHER AUTO_INCREMENT = 1;
ALTER TABLE CUSTOMER AUTO_INCREMENT = 1;
ALTER TABLE ADMIN AUTO_INCREMENT = 1;

INSERT INTO CUSTOMER (First_name, Last_name, Email, Password, Address, Phone) VALUES
('Mario', 'Rossi', 'prova@example.com', 'password123', 'Via Roma 1', '1234567890'),
('Luigi', 'Verdi', 'luigi.verdi@example.com', 'password123', 'Via Milano 2', '0987654321'),
('Anna', 'Bianchi', 'anna.bianchi@example.com', 'password123', 'Via Napoli 3', '3456789012');

INSERT INTO ADMIN (First_name, Last_name, Email, Password) VALUES
('Admin', 'User', 'admin@example.com', 'admin123');

INSERT INTO AUTHOR (First_name, Last_name) VALUES
('Giovanni', 'Bianchi'),
('Anna', 'Neri'),
('Marco', 'Verdi'),
('Laura', 'Rossi'),
('Paolo', 'Gialli');

INSERT INTO CATEGORY (Name) VALUES
('Romanzo'),
('Fantascienza'),
('Giallo'),
('Fantasy'),
('Biografia');

INSERT INTO PUBLISHER (Name, Address) VALUES
('Mondadori', 'Via della Libertà 10'),
('Feltrinelli', 'Corso Italia 20'),
('Einaudi', 'Piazza della Repubblica 30'),
('Rizzoli', 'Via Roma 40');

INSERT INTO BOOK (Title, Description, Price, Cover, Exceptr, Category_id, Publisher_id, Author_id) VALUES
('Il Grande Romanzo', 'Un romanzo epico che racconta la storia di una famiglia attraverso tre generazioni', 19.99, 'images/deepwork.jpg', 'exceptr/text.txt', 1, 1, 1),
('Viaggio nello Spazio', 'Un racconto di fantascienza ambientato nel 2150', 15.99, 'images/shining.jpg', 'exceptr/text1.txt', 2, 2, 2),
('Il Mistero del Lago', 'Un giallo avvincente ambientato in un piccolo villaggio di montagna', 18.50, 'images/stevejobs.jpg', 'exceptr/text2.txt', 3, 3, 3),
('La Terra di Mezzo', 'Un fantasy epico con draghi, elfi e antiche magie', 22.00, 'images/shining.jpg', '', 4, 1, 4),
('Vita di Einstein', 'La biografia del famoso scienziato', 24.99, 'images/5.jpg', 'exceptr/text4.txt', 5, 4, 5),
('Il Ritorno', 'Sequel del Grande Romanzo, continua la saga familiare', 21.50, 'images/stevejobs.jpg', 'exceptr/text5.txt', 1, 1, 1),
('Mondi Paralleli', 'Un viaggio tra dimensioni alternative', 17.99, 'images/deepwork.jpg', 'exceptr/text6.txt', 2, 2, 2),
('Il Codice Segreto', 'Un mistero da risolvere in una corsa contro il tempo', 16.50, 'images/8.jpg', 'exceptr/text7.txt', 3, 3, 3),
('Le Cronache del Regno', 'Una saga fantasy di avventura e magia', 23.99, 'images/deepwork.jpg', 'exceptr/text8.txt', 4, 4, 4);

INSERT INTO BOOK_IN_CART (Cart_id, Book_id, Quantity) VALUES
(1, 1, 1),
(1, 4, 2),
(2, 2, 1),
(2, 5, 1),
(3, 3, 3);

INSERT INTO DISCOUNT_CODE (Code, Type, Value, Start_date, End_date, Single_use, Active) VALUES
('WELCOME10', 'percentage', 10.00, '2024-01-01', '2025-12-31', FALSE, TRUE),
('SUMMER25', 'percentage', 25.00, '2025-06-01', '2025-08-31', FALSE, TRUE),
('FIXED15', 'fixed', 15.00, '2024-01-01', '2025-12-31', FALSE, TRUE);

INSERT INTO `ORDER` (Date, Total, Customer_id, Discount_code_id, Status) VALUES
('2025-03-15 10:30:00', 19.99, 1, NULL, 'pending'),
('2025-03-20 14:45:00', 12.00, 2, 1, 'pending'),
('2025-03-25 09:15:00', 55.50, 3, NULL, 'pending');

INSERT INTO REVIEW (Text, Rating, Book_id, Customer_id) VALUES
('Libro fantastico, lo consiglio vivamente!', 5, 1, 1),
('Una buona lettura ma la trama è prevedibile.', 3, 2, 2),
('Mi ha tenuto sveglio tutta la notte. Eccellente!', 5, 3, 3);

INSERT INTO POST (Text, Publication_date, Author_id, Book_id) VALUES
('Nuovo romanzo di Giovanni Bianchi disponibile ora!', '2025-03-01 12:00:00', 1, 1),
('Incontro con l\'autore Anna Neri presso la libreria centrale', '2025-03-10 12:00:00', 2, 2);

INSERT INTO ORDER_NOTIFICATION (Order_id, Preview, Message, Status, Seen) VALUES
(1, 'Ordine spedito', 'Il tuo ordine è stato spedito', 'sent', FALSE),
(2, 'Ordine in elaborazione', 'Il tuo ordine è in elaborazione', 'pending', FALSE),
(3, 'Ordine consegnato', 'Il tuo ordine è stato consegnato', 'arrived', FALSE);

SELECT "Database popolato con successo con 9 libri e relativi dati!" AS message;