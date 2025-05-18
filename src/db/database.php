<?php
require_once __DIR__ . '/../interfaces/DatabaseInterfaces.php';

class MySqlDatabase implements 
    CustomerManager, 
    BookManager, 
    AdminManager, 
    AuthorManager, 
    CategoryManager, 
    PublisherManager, 
    PostManager, 
    ReviewManager, 
    CartManager, 
    BookInCartManager, 
    DiscountCodeManager, 
    OrderManager, 
    OrderDetailManager, 
    DiscountCodeUsageManager, 
    OrderNotificationManager,
    BusinessLogic,
    AdminNotificationManager
{
    public $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    // CUSTOMER methods
    public function getCustomers() {
        $stmt = $this->db->prepare("SELECT * FROM CUSTOMER");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getCustomerById($id) {
        $stmt = $this->db->prepare("SELECT * FROM CUSTOMER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function getCustomerByUsername($username) {
        $stmt = $this->db->prepare("SELECT * FROM CUSTOMER WHERE Email = ?");
        $stmt->bind_param('s', $username);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertCustomer($firstName, $lastName, $email, $password, $address, $phone) {
        // Controlla se l'email esiste già
        $stmt = $this->db->prepare("SELECT Id FROM CUSTOMER WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return false;
        }
        $stmt->close();

        $stmt = $this->db->prepare("INSERT INTO CUSTOMER (First_name, Last_name, Email, Password, Address, Phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $firstName, $lastName, $email, $password, $address, $phone);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false; 
        }
    }

    public function updateCustomer($id, $firstName, $lastName, $email, $password, $address, $phone) {
        $stmt = $this->db->prepare("SELECT Id FROM CUSTOMER WHERE Email = ? AND Id != ?");
        $stmt->bind_param('si', $email, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            $stmt->close();
            return false; 
        }
        $stmt->close();

        $stmt = $this->db->prepare("UPDATE CUSTOMER SET First_name = ?, Last_name = ?, Email = ?, Password = ?, Address = ?, Phone = ? WHERE Id = ?");
        $stmt->bind_param('ssssssi', $firstName, $lastName, $email, $password, $address, $phone, $id);
        return $stmt->execute();
    }

    public function deleteCustomer($id) {
        $stmt = $this->db->prepare("DELETE FROM CUSTOMER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // BOOK methods
    public function getBooks() {
        $stmt = $this->db->prepare("
            SELECT b.*, a.First_name AS Author_First_name, 
                   a.Last_name AS Author_Last_name,
                   CONCAT(a.First_name, ' ', a.Last_name) AS Author_name
            FROM BOOK AS b, AUTHOR AS a
            WHERE b.Author_id = a.Id
        ");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookById($id) {
        $stmt = $this->db->prepare("SELECT * FROM BOOK WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertBook($title, $description, $price, $cover, $categoryId, $publisherId, $authorId) {
        $stmt = $this->db->prepare("INSERT INTO BOOK (Title, Description, Price, Cover, Category_id, Publisher_id, Author_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdsiii', $title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
        $stmt->execute();
        return $stmt->insert_id;
    }


    public function insertBookWithExceptr($title, $description, $price, $cover, $exceptr, $categoryId, $publisherId, $authorId) {
        $stmt = $this->db->prepare("INSERT INTO BOOK (Title, Description, Price, Cover,  Exceptr, Category_id, Publisher_id, Author_id) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdssiii', $title, $description, $price, $cover, $exceptr, $categoryId, $publisherId, $authorId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId) {
        // Inizia transazione
        $this->db->begin_transaction();
        
        try {
            // Ottieni il prezzo attuale del libro prima dell'aggiornamento
            $stmt = $this->db->prepare("SELECT Price FROM BOOK WHERE Id = ?");
            $stmt->bind_param('i', $id);
            $stmt->execute();
            $oldPriceResult = $stmt->get_result()->fetch_assoc();
            $oldPrice = $oldPriceResult['Price'] ?? 0;
            
            // Aggiorna il libro
            $stmt = $this->db->prepare("UPDATE BOOK SET Title = ?, Description = ?, Price = ?, Cover = ?, Category_id = ?, Publisher_id = ?, Author_id = ? WHERE Id = ?");
            $stmt->bind_param('ssdsiiii', $title, $description, $price, $cover, $categoryId, $publisherId, $authorId, $id);
            $stmt->execute();
            
            // Se il prezzo è cambiato, aggiorna tutti i carrelli che contengono questo libro
            if ($oldPrice != $price) {
                // Trova tutti i carrelli che contengono questo libro
                $stmt = $this->db->prepare("
                    SELECT bic.Cart_id, bic.Quantity
                    FROM BOOK_IN_CART bic
                    WHERE bic.Book_id = ?
                ");
                $stmt->bind_param('i', $id);
                $stmt->execute();
                $result = $stmt->get_result();
                
                // Aggiorna ogni carrello
                while ($row = $result->fetch_assoc()) {
                    $cartId = $row['Cart_id'];
                    $quantity = $row['Quantity'];
                    $priceDifference = $price - $oldPrice;
                    
                    // Aggiorna il subtotale del carrello
                    $updateStmt = $this->db->prepare("
                        UPDATE CART 
                        SET Subtotal = Subtotal + (? * ?),
                            Last_modified = CURRENT_TIMESTAMP
                        WHERE Id = ?
                    ");
                    $updateStmt->bind_param('ddi', $priceDifference, $quantity, $cartId);
                    $updateStmt->execute();
                }
            }
            
            // Commit della transazione
            $this->db->commit();
            return true;
        } catch (Exception $e) {
            // In caso di errore, annulla le modifiche
            $this->db->rollback();
            error_log("Errore durante l'aggiornamento del libro e dei carrelli: " . $e->getMessage());
            return false;
        }
    }

     public function updateBookQuantity($bookId, $quantity) {
        $stmt = $this->db->prepare("UPDATE BOOK SET Product_count = ? WHERE id = ?");
        $stmt->bind_param('ii', $quantity, $bookId);
        return $stmt->execute();
    }

    public function deleteBook($id) {
        // Ottieni tutte le informazioni necessarie sui libri nei carrelli prima di eliminarli
        $stmt = $this->db->prepare("
            SELECT c.Id as cart_id, bic.Quantity as quantity, b.Price as price
            FROM BOOK b
            JOIN BOOK_IN_CART bic ON b.Id = bic.Book_id
            JOIN CART c ON bic.Cart_id = c.Id
            WHERE b.Id = ?
        ");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        // Aggiorna manualmente i carrelli
        while ($row = $result->fetch_assoc()) {
            $cartId = $row['cart_id'];
            $quantity = $row['quantity'];
            $price = $row['price'];
            
            $updateStmt = $this->db->prepare("
                UPDATE CART 
                SET Item_count = Item_count - ?, 
                    Subtotal = Subtotal - (? * ?),
                    Last_modified = CURRENT_TIMESTAMP
                WHERE Id = ?
            ");
            $updateStmt->bind_param('iddi', $quantity, $quantity, $price, $cartId);
            $updateStmt->execute();
        }
        
        // Ora elimina il libro (le righe in BOOK_IN_CART verranno eliminate automaticamente)
        $stmt = $this->db->prepare("DELETE FROM BOOK WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ADMIN methods
    public function getAdmins() {
        $stmt = $this->db->prepare("SELECT * FROM ADMIN");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ADMIN WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertAdmin($firstName, $lastName, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO ADMIN (First_name, Last_name, Email, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $firstName, $lastName, $email, $password);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateAdmin($id, $firstName, $lastName, $email, $password) {
        $stmt = $this->db->prepare("UPDATE ADMIN SET First_name = ?, Last_name = ?, Email = ?, Password = ? WHERE Id = ?");
        $stmt->bind_param('ssssi', $firstName, $lastName, $email, $password, $id);
        return $stmt->execute();
    }

    public function deleteAdmin($id) {
        $stmt = $this->db->prepare("DELETE FROM ADMIN WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // AUTHOR methods
    public function getAuthors() {
        $stmt = $this->db->prepare("SELECT * FROM AUTHOR");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getAuthorById($id) {
        $stmt = $this->db->prepare("SELECT * FROM AUTHOR WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertAuthor($firstName, $lastName) {
        $stmt = $this->db->prepare("INSERT INTO AUTHOR (First_name, Last_name) VALUES (?, ?)");
        $stmt->bind_param('ss', $firstName, $lastName);
        $stmt->execute();
        return $stmt->insert_id;
    }

   public function updateAuthor($id, $firstName, $lastName) {
        $stmt = $this->db->prepare("SELECT Id FROM AUTHOR WHERE First_name = ? AND Last_name = ? AND Id != ?");
        $stmt->bind_param('ssi', $firstName, $lastName, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return false;
        }
        $stmt->close();

        $stmt = $this->db->prepare("UPDATE AUTHOR SET First_name = ?, Last_name = ? WHERE Id = ?");
        $stmt->bind_param('ssi', $firstName, $lastName, $id);
        return $stmt->execute();
    }

    public function deleteAuthor($id) {
        $stmt = $this->db->prepare("DELETE FROM AUTHOR WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // CATEGORY methods
    public function getCategories() {
        $stmt = $this->db->prepare("SELECT * FROM CATEGORY");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCategoryById($id) {
        $stmt = $this->db->prepare("SELECT * FROM CATEGORY WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertCategory($name) {
        $stmt = $this->db->prepare("INSERT INTO CATEGORY (Name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateCategory($id, $name) {
        $stmt = $this->db->prepare("UPDATE CATEGORY SET Name = ? WHERE Id = ?");
        $stmt->bind_param('si', $name, $id);
        return $stmt->execute();
    }

    public function deleteCategory($id) {
        $stmt = $this->db->prepare("DELETE FROM CATEGORY WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // PUBLISHER methods
    public function getPublishers() {
        $stmt = $this->db->prepare("SELECT * FROM PUBLISHER");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPublisherById($id) {
        $stmt = $this->db->prepare("SELECT * FROM PUBLISHER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertPublisher($name, $address) {
    
        $stmt = $this->db->prepare("SELECT Id FROM PUBLISHER WHERE Name = ?");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
        
            return false;
        }
        $stmt->close();

        $stmt = $this->db->prepare("INSERT INTO PUBLISHER (Name, Address) VALUES (?, ?)");
        $stmt->bind_param('ss', $name, $address);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
    }


    public function updatePublisher($id, $name, $address) {
        $stmt = $this->db->prepare("SELECT Id FROM PUBLISHER WHERE Name = ? AND Id != ?");
        $stmt->bind_param('si', $name, $id);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return false;
        }
        $stmt->close();
        $stmt = $this->db->prepare("UPDATE PUBLISHER SET Name = ?, Address = ? WHERE Id = ?");
        $stmt->bind_param('ssi', $name, $address, $id);
        return $stmt->execute();
    }

    public function deletePublisher($id) {
        $stmt = $this->db->prepare("DELETE FROM PUBLISHER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // POST methods
    public function getPosts() {
        $stmt = $this->db->prepare("SELECT * FROM POST");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getPostById($id) {
        $stmt = $this->db->prepare("SELECT * FROM POST WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertPost($text, $publicationDate, $authorId, $bookId) {
        $stmt = $this->db->prepare("INSERT INTO POST (Text, Publication_date, Author_id, Book_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssii', $text, $publicationDate, $authorId, $bookId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updatePost($id, $text, $publicationDate, $authorId, $bookId) {
        $stmt = $this->db->prepare("UPDATE POST SET Text = ?, Publication_date = ?, Author_id = ?, Book_id = ? WHERE Id = ?");
        $stmt->bind_param('ssiii', $text, $publicationDate, $authorId, $bookId, $id);
        return $stmt->execute();
    }

    public function deletePost($id) {
        $stmt = $this->db->prepare("DELETE FROM POST WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    
    // REVIEW methods
    public function getReviews() {
        $stmt = $this->db->prepare("SELECT * FROM REVIEW");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getReviewById($id) {
        $stmt = $this->db->prepare("SELECT * FROM REVIEW WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertReview($text, $rating, $bookId, $customerId) {
    // Controlla se il cliente ha acquistato il libro
    $stmt = $this->db->prepare("
        SELECT COUNT(*) as count
        FROM ORDER_DETAIL od
        JOIN `ORDER` o ON od.Order_id = o.Id
        WHERE od.Book_id = ? AND o.Customer_id = ?
    ");
    $stmt->bind_param('ii', $bookId, $customerId);
    $stmt->execute();
    $result = $stmt->get_result()->fetch_assoc();

    if ($result['count'] == 0) {
        throw new Exception("Il cliente non ha acquistato questo libro e non può lasciare una recensione.");
    }

    // Inserisci la recensione
    $stmt = $this->db->prepare("INSERT INTO REVIEW (Text, Rating, Book_id, Customer_id) VALUES (?, ?, ?, ?)");
    $stmt->bind_param('siii', $text, $rating, $bookId, $customerId);
    $stmt->execute();
    return $stmt->insert_id;
}

    public function updateReview($id, $text, $rating, $bookId, $customerId) {
        $stmt = $this->db->prepare("UPDATE REVIEW SET Text = ?, Rating = ?, Book_id = ?, Customer_id = ? WHERE Id = ?");
        $stmt->bind_param('siiii', $text, $rating, $bookId, $customerId, $id);
        return $stmt->execute();
    }

    public function deleteReview($id) {
        $stmt = $this->db->prepare("DELETE FROM REVIEW WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }
    
    // CART methods
    public function getCarts() {
        $stmt = $this->db->prepare("SELECT * FROM CART");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getCartByCustomerId($id) {
        $stmt = $this->db->prepare("SELECT * FROM CART WHERE Customer_id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function updateCart($id, $subtotal, $lastModified, $itemCount, $customerId) {
        $stmt = $this->db->prepare("UPDATE CART SET Subtotal = ?, Last_modified = ?, Item_count = ?, Customer_id = ? WHERE Id = ?");
        $stmt->bind_param('dsiii', $subtotal, $lastModified, $itemCount, $customerId, $id);
        return $stmt->execute();
    }


    // BOOK_IN_CART methods
    public function getBooksInCart($cartid) {
        $stmt = $this->db->prepare("SELECT * FROM BOOK_IN_CART WHERE Cart_id = ?");
        $stmt->bind_param('i', $cartid);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookInCartById($id) {
        $stmt = $this->db->prepare("SELECT * FROM BOOK_IN_CART WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

  

    public function updateBookInCart($cartId, $bookId, $quantity) {
        $stmt = $this->db->prepare("UPDATE BOOK_IN_CART SET Quantity = ? WHERE Cart_id = ? AND Book_id = ?");
        $stmt->bind_param('iii', $quantity, $cartId, $bookId);
        return $stmt->execute();
    }

    public function insertBookInCart($cartId, $bookId, $quantity) {
        // Controlla se il libro è già nel carrello
        $stmt_check = $this->db->prepare("SELECT Id, Quantity FROM BOOK_IN_CART WHERE Cart_id = ? AND Book_id = ?");
        $stmt_check->bind_param('ii', $cartId, $bookId);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        if ($result_check->num_rows > 0) {
            // Il libro è già nel carrello, aggiorna la quantità
            $row = $result_check->fetch_assoc();
            $existing_quantity = $row['Quantity'];
            $new_quantity = $existing_quantity + $quantity;
            
            $stmt_update = $this->db->prepare("UPDATE BOOK_IN_CART SET Quantity = ? WHERE Cart_id = ? AND Book_id = ?");
            $stmt_update->bind_param('iii', $new_quantity, $cartId, $bookId);
            if ($stmt_update->execute()) {
                return $row['Id']; // Restituisce l'ID della riga esistente
            } else {
                // Gestisci l'errore di aggiornamento, se necessario
                error_log("Errore durante l'aggiornamento della quantità per il libro $bookId nel carrello $cartId: " . $stmt_update->error);
                return false; 
            }
        } else {
              // Il libro non è nel carrello, inseriscilo
            $stmt_insert = $this->db->prepare("INSERT INTO BOOK_IN_CART (Cart_id, Book_id, Quantity) VALUES (?, ?, ?)");
            $stmt_insert->bind_param('iii', $cartId, $bookId, $quantity);
            if ($stmt_insert->execute()) {
                return $stmt_insert->insert_id; // Restituisce l'ID della nuova riga inserita
            } else {
                // Gestisci l'errore di inserimento, se necessario
                error_log("Errore durante l'inserimento del libro $bookId nel carrello $cartId: " . $stmt_insert->error);
                return false;
            }
        }
    }

    public function deleteBookInCart($cartId, $bookId) {
        $stmt = $this->db->prepare("DELETE FROM BOOK_IN_CART WHERE Cart_id = ? AND Book_id = ?");
        $stmt->bind_param('ii', $cartId, $bookId);
        return $stmt->execute();
    }


    // DISCOUNT_CODE methods
    public function getDiscountCodes() {
        $stmt = $this->db->prepare("SELECT * FROM DISCOUNT_CODE");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getDiscountCodeById($id) {
        $stmt = $this->db->prepare("SELECT * FROM DISCOUNT_CODE WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertDiscountCode($code, $type, $value, $startDate, $endDate, $singleUse, $active) {
        $stmt = $this->db->prepare("SELECT Id FROM DISCOUNT_CODE WHERE Code = ?");
        $stmt->bind_param('s', $code);
        $stmt->execute();
        $stmt->store_result();
        if ($stmt->num_rows > 0) {
            return false;
        }
        $stmt->close();
    
        // Inserimento nuovo codice
        $stmt = $this->db->prepare("INSERT INTO DISCOUNT_CODE (Code, Type, Value, Start_date, End_date, Single_use, Active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdssii', $code, $type, $value, $startDate, $endDate, $singleUse, $active);
        if ($stmt->execute()) {
            return $stmt->insert_id;
        } else {
            return false;
        }
}

    public function updateDiscountCode($id, $code, $type, $value, $startDate, $endDate, $singleUse, $active) {
        $stmt = $this->db->prepare("UPDATE DISCOUNT_CODE SET Code = ?, Type = ?, Value = ?, Start_date = ?, End_date = ?, Single_use = ?, Active = ? WHERE Id = ?");
        $stmt->bind_param('ssdssiii', $code, $type, $value, $startDate, $endDate, $singleUse, $active, $id);
        return $stmt->execute();
    }

    public function deleteDiscountCode($id) {
        $stmt = $this->db->prepare("DELETE FROM DISCOUNT_CODE WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    // ORDER methods
    public function getOrders() {
        $stmt = $this->db->prepare("SELECT * FROM `ORDER`");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderById($id) {
        $stmt = $this->db->prepare("SELECT * FROM `ORDER` WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderByCustomerId($customerId) {
        $stmt = $this->db->prepare("SELECT * FROM `ORDER` WHERE Customer_id = ?");
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }


    public function insertOrder($date, $total, $customerId, $discountCodeId) {
        // Inizio transazione
        $this->db->begin_transaction();
        
        try {
            // Recupera il Cart_id
            $cart = $this->getCartByCustomerId($customerId);
            $cartId = $cart['Id'];
    
            // Verifica se il carrello è vuoto
            if ($cart['Item_count'] == 0) {
                throw new Exception("Il carrello è vuoto, impossibile completare l'ordine.");
            }
            $discountUsed = false;
            // Calcola il totale con lo sconto, se applicabile
            if ($discountCodeId !== null) {
                $discount = $this->getDiscountCodeById($discountCodeId);
    
                if ($discount) {
                    $currentDate = date('Y-m-d'); // Ottieni la data corrente
                
                    if ($discount['Active'] && $currentDate >= $discount['Start_date'] && $currentDate <= $discount['End_date']) {
                        if ($discount['Type'] === 'percentage') {
                            // Applica lo sconto percentuale
                            $total -= ($total * ($discount['Value'] / 100));
                        } elseif ($discount['Type'] === 'fixed') {
                            // Applica lo sconto fisso
                            $total -= $discount['Value'];
                        }
                
                        // Assicurati che il totale non sia negativo
                        if ($total < 0) {
                            $total = 0;
                        }

                        if ($discount['Single_use']) {
                            // Imposta il codice sconto come non più utilizzabile
                            $this->updateDiscountCode($discountCodeId, $discount['Code'], $discount['Type'], $discount['Value'], $discount['Start_date'], $discount['End_date'], false, $discount['Active']);
                        }
                        $discountUsed = true;
                     
                    } else {
                        throw new Exception("Codice sconto non valido o non applicabile.");
                    }
                } else {
                    throw new Exception("Codice sconto non valido.");
                }
            }

               // Recupera i libri nel carrello
            $booksInCart = $this->getBooksInCart($cartId);
            $errors = [];
            foreach ($booksInCart as $book) {
                $dbBook = $this->getBookById($book['Book_id']);
                if ($dbBook['Product_count'] <= 0) {
                    $errors[] = $dbBook['Title'];
                }
            }

            if (!empty($errors)) {
                throw new Exception("I seguenti libri non sono disponibili: " . implode(", ", $errors));
            }
    
            // Inserisci l'ordine
            $stmt = $this->db->prepare("INSERT INTO `ORDER` (Date, Total, Customer_id, Discount_code_id) VALUES (?, ?, ?, ?)");
            $stmt->bind_param('sdii', $date, $total, $customerId, $discountCodeId);
            $stmt->execute();
            $orderId = $stmt->insert_id;

            if ($discountUsed){
                // Registra l'utilizzo del codice sconto
                $this->insertDiscountCodeUsage(
                    date('Y-m-d H:i:s'), // Data di utilizzo
                    $discountCodeId,     // ID del codice sconto
                    $customerId,         // ID del cliente
                    $orderId                // L'ID dell'ordine sarà aggiunto dopo
                );
             }
    
         
    
            // Crea i dettagli dell'ordine per ogni libro nel carrello
            foreach ($booksInCart as $book) {
                $bookId = $book['Book_id'];
                $quantity = $book['Quantity'];
    
                // Recupera il prezzo del libro dalla tabella BOOK
                $stmt = $this->db->prepare("SELECT Price FROM BOOK WHERE Id = ?");
                $stmt->bind_param('i', $bookId);
                $stmt->execute();
                $result = $stmt->get_result()->fetch_assoc();
                $price = $result['Price'];
    
                // Calcola il subtotale
                $subtotal = $quantity * $price;
    
                // Inserisci i dettagli dell'ordine
                $stmt = $this->db->prepare("INSERT INTO ORDER_DETAIL (Quantity, Subtotal, Order_id, Book_id) VALUES (?, ?, ?, ?)");
                $stmt->bind_param('idii', $quantity, $subtotal, $orderId, $bookId);
                $stmt->execute();
            }
    
            // Elimina i libri dal carrello
            $stmt = $this->db->prepare("DELETE FROM BOOK_IN_CART WHERE Cart_id = ?");
            $stmt->bind_param('i', $cartId);
            $stmt->execute();
    
            // Azzera il carrello
            $stmt = $this->db->prepare("UPDATE CART SET Subtotal = 0, Item_count = 0, Last_modified = CURRENT_TIMESTAMP WHERE Customer_id = ?");
            $stmt->bind_param('i', $customerId);
            $stmt->execute();

            $this->insertOrderNotification($orderId,"Ordine in elaborazione" ,"Il tuo ordine è in elaborazione!", 'pending');
    
            // Commit transazione
            $this->db->commit();
    
            return $orderId;
        } catch (Exception $e) {
            // Rollback in caso di errore
            $this->db->rollback();
            throw $e;
        }
    }

    public function updateOrder($id, $date, $total, $customerId, $discountCodeId) {
        $stmt = $this->db->prepare("UPDATE `ORDER` SET Date = ?, Total = ?, Customer_id = ?, Discount_code_id = ? WHERE Id = ?");
        $stmt->bind_param('sdiii', $date, $total, $customerId, $discountCodeId, $id);
        return $stmt->execute();
    }

    public function updateOrderStatus($id, $status) {
        // Controlla che lo stato sia valido
        $validStatuses = ['pending', 'sent', 'arrived'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Stato non valido: $status");
        }
    
        // Recupera lo stato attuale dell'ordine
        $stmt = $this->db->prepare("SELECT Status FROM `ORDER` WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $currentOrder = $stmt->get_result()->fetch_assoc();
    
        if (!$currentOrder) {
            throw new Exception("Ordine non trovato.");
        }
    
        $currentStatus = $currentOrder['Status'];
    
        // Aggiorna lo stato dell'ordine
        $stmt = $this->db->prepare("UPDATE `ORDER` SET Status = ? WHERE Id = ?");
        $stmt->bind_param('si', $status, $id);
        $stmt->execute();
    
        // Verifica che l'aggiornamento sia avvenuto
        if ($stmt->affected_rows === 0) {
            throw new Exception("Ordine non trovato o stato non modificato.");
        }
    
        // Crea notifiche in base al nuovo stato
        if ($status === 'sent') {
            $this->insertOrderNotification($id,"Ordine spedito" ,"Il tuo ordine è stato spedito!", $status);
        } elseif ($status === 'arrived') {
            $this->insertOrderNotification($id,"Ordine arrivato" ,"Il tuo ordine è arrivato!", $status);
        }
    
        return true;
    }

    public function deleteOrder($id) {
        $stmt = $this->db->prepare("DELETE FROM `ORDER` WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ORDER_DETAIL methods
    public function getOrderDetails() {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_DETAIL");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderDetailById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_DETAIL WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderDetailsByOrderId($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_DETAIL WHERE Order_id = ?");
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertOrderDetail($quantity, $subtotal, $orderId, $bookId) {
        $stmt = $this->db->prepare("INSERT INTO ORDER_DETAIL (Quantity, Subtotal, Order_id, Book_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('idii', $quantity, $subtotal, $orderId, $bookId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateOrderDetail($id, $quantity, $subtotal, $orderId, $bookId) {
        $stmt = $this->db->prepare("UPDATE ORDER_DETAIL SET Quantity = ?, Subtotal = ?, Order_id = ?, Book_id = ? WHERE Id = ?");
        $stmt->bind_param('idiii', $quantity, $subtotal, $orderId, $bookId, $id);
        return $stmt->execute();
    }

    public function deleteOrderDetail($id) {
        $stmt = $this->db->prepare("DELETE FROM ORDER_DETAIL WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // DISCOUNT_CODE_USAGE methods
    public function getDiscountCodeUsages() {
        $stmt = $this->db->prepare("SELECT * FROM DISCOUNT_CODE_USAGE");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getDiscountCodeUsageById($id) {
        $stmt = $this->db->prepare("SELECT * FROM DISCOUNT_CODE_USAGE WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function insertDiscountCodeUsage($usageDate, $discountCodeId, $customerId, $orderId) {
        $stmt = $this->db->prepare("INSERT INTO DISCOUNT_CODE_USAGE (Usage_date, Discount_code_id, Customer_id, Order_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siii', $usageDate, $discountCodeId, $customerId, $orderId);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateDiscountCodeUsage($id, $usageDate, $discountCodeId, $customerId, $orderId) {
        $stmt = $this->db->prepare("UPDATE DISCOUNT_CODE_USAGE SET Usage_date = ?, Discount_code_id = ?, Customer_id = ?, Order_id = ? WHERE Id = ?");
        $stmt->bind_param('siiii', $usageDate, $discountCodeId, $customerId, $orderId, $id);
        return $stmt->execute();
    }

    public function deleteDiscountCodeUsage($id) {
        $stmt = $this->db->prepare("DELETE FROM DISCOUNT_CODE_USAGE WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // ORDER_NOTIFICATION methods
    public function getOrderNotifications() {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_NOTIFICATION");
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderNotificationById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_NOTIFICATION WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function getOrderNotificationByCustomerId($customerId) {
        $stmt = $this->db->prepare("
            SELECT `on`.* 
            FROM ORDER_NOTIFICATION `on`
            JOIN `ORDER` o ON `on`.Order_id = o.Id
            WHERE o.Customer_id = ?
            ORDER BY `on`.Date DESC
        ");
        $stmt->bind_param('i', $customerId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrderNotificationByOrderId($orderId) {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_NOTIFICATION WHERE Order_id = ?");
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getOrdersNotificationByStatus($status) {
        $stmt = $this->db->prepare("SELECT * FROM ORDER_NOTIFICATION WHERE Status = ?");
        $stmt->bind_param('s', $status);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function insertOrderNotification($orderId, $preview, $message, $status) {
        $stmt = $this->db->prepare("INSERT INTO ORDER_NOTIFICATION (Order_id, Preview, Message, Status) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('isss', $orderId, $preview, $message, $status);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateOrderNotification($id, $orderId, $preview, $message, $status) {
        $this->db->begin_transaction(); 

        try {
          
            $stmtNotification = $this->db->prepare("UPDATE ORDER_NOTIFICATION SET Order_id = ?, Preview = ?, Message = ?, Status = ?, Date = CURRENT_TIMESTAMP WHERE Id = ?");
            $stmtNotification->bind_param('isssi', $orderId, $preview, $message, $status, $id);
            
            if (!$stmtNotification->execute()) {
                $this->db->rollback();
                error_log("Errore durante l'aggiornamento della notifica ID $id: " . $stmtNotification->error);
                return false;
            }

            $this->updateOrderStatus($orderId, $status); 
            $this->db->commit(); 
            return true;

        } catch (Exception $e) {
            $this->db->rollback(); 
            error_log("Errore in updateOrderNotification per notifica ID $id e ordine ID $orderId: " . $e->getMessage());
            return false;
        }
    }

    public function SetSeenNotification($id) {
        $stmt = $this->db->prepare("UPDATE ORDER_NOTIFICATION SET Seen = ? WHERE Id = ?");
        $seen = true;

        $stmt->bind_param('ii', $seen, $id);
        $stmt->execute();
    
        // Verifica che l'aggiornamento sia avvenuto
        if ($stmt->affected_rows === 0) {
            throw new Exception("Notifica non trovata o già aggiornata.");
        }
    
        return true;
    }

    public function deleteOrderNotification($id) {
        $stmt = $this->db->prepare("DELETE FROM ORDER_NOTIFICATION WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    public function getAdminOrderNotifications() {
        $stmt = $this->db->prepare("SELECT Id, Order_id, Preview, Message, Date, Seen FROM ADMIN_ORDER_NOTIFICATION ORDER BY Date DESC");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getAdminOrderNotificationById($id) {
        $stmt = $this->db->prepare("SELECT * FROM ADMIN_ORDER_NOTIFICATION WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        return $stmt->get_result()->fetch_assoc();
    }

    public function SetSeenAdminNotification($notificationId) {
        $stmt = $this->db->prepare("UPDATE ADMIN_ORDER_NOTIFICATION SET Seen = 1 WHERE Id = ?");
        $stmt->bind_param('i', $notificationId);
        return $stmt->execute();
    }

    public function deleteAdminNotification($id) {
        $stmt = $this->db->prepare("DELETE FROM ADMIN_ORDER_NOTIFICATION WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }


    
    public function getBestSellers($numberOfBooks) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   bs.Purchase_count,
                   a.First_name as Author_First_name, 
                   a.Last_name as Author_Last_name,
                   CONCAT(a.First_name, ' ', a.Last_name) as Author_name
            FROM BEST_SELLER bs
            JOIN BOOK b ON bs.Book_id = b.Id
            LEFT JOIN AUTHOR a ON b.Author_id = a.Id
            ORDER BY bs.Purchase_count DESC
            LIMIT ?
        ");
        $stmt->bind_param('i', $numberOfBooks);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

   
    public function getNewBooks($numberOfBooks) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                IFNULL(bs.Purchase_count, 0) as Purchase_count,
                a.First_name as Author_First_name, 
                a.Last_name as Author_Last_name,
                CONCAT(a.First_name, ' ', a.Last_name) as Author_name
            FROM BOOK b
            LEFT JOIN BEST_SELLER bs ON b.Id = bs.Book_id
            LEFT JOIN AUTHOR a ON b.Author_id = a.Id
            ORDER BY b.Id DESC  -- Ordina per ID decrescente (più recenti prima)
            LIMIT ?
        ");
        $stmt->bind_param('i', $numberOfBooks);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function  getOrderDetailsWithAllInformation($orderId){
        $stmt = $this->db->prepare("
            SELECT od.*, b.Title, b.Price, c.First_name AS CustomerFirstName, c.Last_name AS CustomerLastName
            FROM ORDER_DETAIL od
            JOIN BOOK b ON od.Book_id = b.Id
            JOIN `ORDER` o ON od.Order_id = o.Id
            JOIN CUSTOMER c ON o.Customer_id = c.Id
            WHERE od.Order_id = ?
        ");
        $stmt->bind_param('i', $orderId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    
    public function checkLogin($email, $password) {
        // Prima controlla nella tabella CUSTOMER
        $stmt = $this->db->prepare("SELECT Id, Email, First_name, Last_name, Password FROM CUSTOMER WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifica la password hashata
            if (password_verify($password, $user['Password'])) {
                return [
                    'id' => $user['Id'],
                    'username' => $user['Email'],
                    'name' => $user['First_name'] . ' ' . $user['Last_name'],
                    'admin' => false
                ];
            }
        }

        // Se non trovato, controlla nella tabella ADMIN
        $stmt = $this->db->prepare("SELECT Id, Email, First_name, Last_name, Password FROM ADMIN WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows > 0) {
            $user = $result->fetch_assoc();
            // Verifica la password hashata
            if (password_verify($password, $user['Password'])) {
                return [
                    'id' => $user['Id'],
                    'username' => $user['Email'],
                    'name' => $user['First_name'] . ' ' . $user['Last_name'],
                    'admin' => true
                ];
            }
    }

    return null;
}

    public function getAccountInfo($email){
        // Prima controlla nella tabella CUSTOMER
        $stmt = $this->db->prepare("SELECT * FROM CUSTOMER WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        // Se non trovato, controlla nella tabella ADMIN
        $stmt = $this->db->prepare("SELECT * FROM ADMIN WHERE Email = ?");
        $stmt->bind_param('s', $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return $result->fetch_assoc();
        }
        
        // Se non trovato in nessuna tabella
        return null;
    }

    public function getCartBooksWithInfo($cartId) {
        $stmt = $this->db->prepare("
            SELECT bic.*, b.*, a.First_name AS Author_First_name, a.Last_name AS Author_Last_name
            FROM BOOK_IN_CART bic
            JOIN BOOK b ON bic.Book_id = b.Id
            LEFT JOIN AUTHOR a ON b.Author_id = a.Id
            WHERE bic.Cart_id = ?
        ");
        $stmt->bind_param('i', $cartId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookDetailsById($bookId) {
        $stmt = $this->db->prepare("
            SELECT b.*, 
                c.Name AS Category_name,
                p.Name AS Publisher_name,
                a.First_name AS Author_First_name, 
                a.Last_name AS Author_Last_name,
                CONCAT(a.First_name, ' ', a.Last_name) AS Author_full_name,
                IFNULL(AVG(r.Rating), 0) AS Average_rating,
                COUNT(r.Id) AS Review_count
            FROM BOOK b
            LEFT JOIN CATEGORY c ON b.Category_id = c.Id
            LEFT JOIN PUBLISHER p ON b.Publisher_id = p.Id
            LEFT JOIN AUTHOR a ON b.Author_id = a.Id
            LEFT JOIN REVIEW r ON b.Id = r.Book_id
            WHERE b.Id = ?
            GROUP BY b.Id
        ");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return null;
        }
        
        return $result->fetch_assoc();
    }

    public function getBookReviews($bookId) {
        $stmt = $this->db->prepare("
            SELECT r.*, 
                   c.First_name AS Customer_First_name, 
                   c.Last_name AS Customer_Last_name,
                   CONCAT(c.First_name, ' ', c.Last_name) AS Customer_full_name
            FROM REVIEW r
            JOIN CUSTOMER c ON r.Customer_id = c.Id
            WHERE r.Book_id = ?
            ORDER BY r.Id DESC
        ");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function GetRelatedBooks($bookId, $limit = 5) {
        // Ottieni l'autore del libro corrente
        $stmt = $this->db->prepare("
            SELECT Author_id 
            FROM BOOK 
            WHERE Id = ?
        ");
        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 0) {
            return [];
        }
        
        $authorId = $result->fetch_assoc()['Author_id'];
        
        // Trova altri libri dello stesso autore, escludendo il libro corrente
        $stmt = $this->db->prepare("
            SELECT b.*, 
                   a.First_name AS Author_First_name, 
                   a.Last_name AS Author_Last_name,
                   CONCAT(a.First_name, ' ', a.Last_name) AS Author_name,
                   c.Name AS Category_name
            FROM BOOK b
            JOIN AUTHOR a ON b.Author_id = a.Id
            LEFT JOIN CATEGORY c ON b.Category_id = c.Id
            WHERE b.Author_id = ? AND b.Id != ?
            ORDER BY b.Id DESC
            LIMIT ?
        ");
        $stmt->bind_param('iii', $authorId, $bookId, $limit);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function GetBooksFromCategoryId($categoryId) {
        $stmt = $this->db->prepare("
            SELECT b.*, a.First_name AS Author_First_name, 
                   a.Last_name AS Author_Last_name,
                   CONCAT(a.First_name, ' ', a.Last_name) AS Author_name
            FROM BOOK AS b, AUTHOR AS a
            WHERE b.Category_id = ? AND b.Author_id = a.Id
        ");
        $stmt->bind_param('i', $categoryId);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function getBookByTitle($title) {
        $stmt = $this->db->prepare("
            SELECT b.*, a.First_name AS Author_First_name, 
                   a.Last_name AS Author_Last_name,
                   CONCAT(a.First_name, ' ', a.Last_name) AS Author_name
            FROM BOOK AS b, AUTHOR AS a
            WHERE b.Title = ? AND b.Author_id = a.Id
        ");
        $stmt->bind_param('s', $title);
        $stmt->execute();
        return $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

    public function searchBooks($searchTerm) {
         $param = "%" . $searchTerm . "%";
        $query = "SELECT b.*, 
                        a.First_name AS Author_First_name, 
                        a.Last_name AS Author_Last_name,
                        c.Name AS Category_name
                FROM BOOK b
                LEFT JOIN AUTHOR a ON b.Author_id = a.Id
                LEFT JOIN CATEGORY c ON b.Category_id = c.Id
                WHERE b.Title LIKE ? 
                    OR b.Description LIKE ?
                    OR a.First_name LIKE ?
                    OR a.Last_name LIKE ?";
        $stmt = $this->db->prepare($query);
        $stmt->bind_param('ssss', $param, $param, $param, $param);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }  

    public function searchAuthors($searchTerm) {
        $param = "%" . $searchTerm . "%";
        $query = "SELECT * FROM AUTHOR 
                WHERE First_name LIKE ? 
                    OR Last_name LIKE ?";
        $stmt = $this->db->prepare($query);
        // Devi legare il parametro per ogni placeholder '?'
        $stmt->bind_param('ss', $param, $param);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function registerUser($nomeCompleto, $email, $password, $indirizzo, $telefono) {
        $nomeArray = explode(' ', $nomeCompleto, 2);
        $firstName = $nomeArray[0];
        $lastName = isset($nomeArray[1]) ? $nomeArray[1] : '';
    
        $stmt = $this->db->prepare("
            INSERT INTO CUSTOMER (First_name, Last_name, Email, Password, Address, Phone) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->bind_param('ssssss', $firstName, $lastName, $email, $password, $indirizzo, $telefono);
    
        return $stmt->execute();    // True if user is created correctly, false otherwise
    }

    public function hasUserPurchaseBookId($userId, $bookId) {
        $stmt = $this->db->prepare("
            SELECT COUNT(*) as count
            FROM ORDER_DETAIL od
            JOIN `ORDER` o ON od.Order_id = o.Id
            WHERE o.Customer_id = ? AND od.Book_id = ?
        ");
        $stmt->bind_param('ii', $userId, $bookId);
        $stmt->execute();
        $result = $stmt->get_result()->fetch_assoc();
        return $result['count'] > 0; // Restituisce true se l'utente ha acquistato il libro, altrimenti false
    }

    public function addReview($text, $rating, $bookId, $userId) {
        // Controlla se il cliente ha acquistato il libro
        $canUserReview = $this->hasUserPurchaseBookId($userId, $bookId);
        if(!$canUserReview){
            throw new Exception("Il cliente non ha acquistato questo libro e non può lasciare una recensione.");
        }

        $stmt = $this->db->prepare("INSERT INTO REVIEW (Text, Rating, Book_id, Customer_id) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('siii', $text, $rating, $bookId, $userId);
        $stmt->execute();
        return $stmt->insert_id;
    }


    public function isBookPurchasable($bookId) {
        if (!is_numeric($bookId) || $bookId <= 0) {
            // Gestione input non valido
            error_log("isBookPurchasable: ID libro non valido fornito: " . $bookId);
            return false;
        }

        $query = "SELECT Product_count FROM BOOK WHERE Id = ?";
        $stmt = $this->db->prepare($query);

        if (!$stmt) {
            // Gestione errore preparazione statement
            error_log("Errore nella preparazione dello statement per isBookPurchasable: " . $this->db->error);
            return false; 
        }

        $stmt->bind_param('i', $bookId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $book = $result->fetch_assoc();
            $stmt->close();
            return (isset($book['Product_count']) && $book['Product_count'] > 0);
        } else {
            // Libro non trovato o errore nella query
            if ($stmt->error) {
                error_log("Errore nell'esecuzione dello statement per isBookPurchasable: " . $stmt->error);
            } else {
                error_log("isBookPurchasable: Libro non trovato con ID: " . $bookId);
            }
            $stmt->close();
            return false;
        }
    }

}
?>