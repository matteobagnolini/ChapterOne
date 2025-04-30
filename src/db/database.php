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
    BusinessLogic
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
        $stmt = $this->db->prepare("INSERT INTO CUSTOMER (First_name, Last_name, Email, Password, Address, Phone) VALUES (?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssssss', $firstName, $lastName, $email, $password, $address, $phone);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateCustomer($id, $firstName, $lastName, $email, $password, $address, $phone) {
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
        $stmt = $this->db->prepare("SELECT * FROM BOOK");
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
        $stmt = $this->db->prepare("INSERT INTO BOOK (Title, Description, Price, Cover, Excepter, Category_id, Publisher_id, Author_id) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdsiii', $title, $description, $price, $cover, $exceptr, $categoryId, $publisherId, $authorId);
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

    public function insertPublisher($name) {
        $stmt = $this->db->prepare("INSERT INTO PUBLISHER (Name) VALUES (?)");
        $stmt->bind_param('s', $name);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updatePublisher($id, $name) {
        $stmt = $this->db->prepare("UPDATE PUBLISHER SET Name = ? WHERE Id = ?");
        $stmt->bind_param('si', $name, $id);
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

    public function insertBookInCart($cartId, $bookId, $quantity) {
        $stmt = $this->db->prepare("INSERT INTO BOOK_IN_CART (Cart_id, Book_id, Quantity) VALUES (?, ?, ?)");
        $stmt->bind_param('iii', $cartId, $bookId, $quantity);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateBookInCart($cartId, $bookId, $quantity) {
        $stmt = $this->db->prepare("UPDATE BOOK_IN_CART SET Quantity = ? WHERE Cart_id = ? AND Book_id = ?");
        $stmt->bind_param('iii', $quantity, $cartId, $bookId);
        return $stmt->execute();
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
        $stmt = $this->db->prepare("INSERT INTO DISCOUNT_CODE (Code, Type, Value, Start_date, End_date, Single_use, Active) VALUES (?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param('ssdssii', $code, $type, $value, $startDate, $endDate, $singleUse, $active);
        $stmt->execute();
        return $stmt->insert_id;
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
    
            // Recupera i libri nel carrello
            $booksInCart = $this->getBooksInCart($cartId);
    
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
            $this->insertOrderNotification($id, "Il tuo ordine è stato spedito!", $status);
        } elseif ($status === 'arrived') {
            $this->insertOrderNotification($id, "Il tuo ordine è arrivato!", $status);
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

    public function insertOrderNotification($orderId, $message, $status) {
        $stmt = $this->db->prepare("INSERT INTO ORDER_NOTIFICATION (Order_id, Message, Status) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $orderId, $message, $status);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateOrderNotification($id, $orderId, $message, $status) {
        $stmt = $this->db->prepare("UPDATE ORDER_NOTIFICATION SET Order_id = ?, Message = ?, Status = ? WHERE Id = ?");
        $stmt->bind_param('iss', $orderId, $message, $status, $id);
        return $stmt->execute();
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
        $stmt = $this->db->prepare("SELECT * FROM CUSTOMER WHERE Email = ? AND Password = ?");
        $stmt->bind_param('ss', $email, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows > 0) {
            return true;
        } else {
            return false;
        }
    }

}
?>