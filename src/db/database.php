<?php
require_once '../interfaces/DatabaseInterface.php';

class MySqlDatabase implements DatabaseInterface {
    public $db;

    public function __construct($servername, $username, $password, $dbname, $port) {
        $this->db = new mysqli($servername, $username, $password, $dbname, $port);
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
    }

    public function getUsers() {
        $stmt = $this->db->prepare("SELECT * FROM USER");
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    public function getUserById($id) {
        $stmt = $this->db->prepare("SELECT * FROM USER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    public function insertUser($firstName, $lastName, $email, $password) {
        $stmt = $this->db->prepare("INSERT INTO USER (First_name, Last_name, Email, Password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param('ssss', $firstName, $lastName, $email, $password);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateUser($id, $firstName, $lastName, $email, $password) {
        $stmt = $this->db->prepare("UPDATE USER SET First_name = ?, Last_name = ?, Email = ?, Password = ? WHERE Id = ?");
        $stmt->bind_param('ssssi', $firstName, $lastName, $email, $password, $id);
        return $stmt->execute();
    }

    public function deleteUser($id) {
        $stmt = $this->db->prepare("DELETE FROM USER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

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

    public function insertCustomer($customerId, $address, $phone) {
        $stmt = $this->db->prepare("INSERT INTO CUSTOMER (Customer_id, Address, Phone) VALUES (?, ?, ?)");
        $stmt->bind_param('iss', $customerId, $address, $phone);
        $stmt->execute();
        return $stmt->insert_id;
    }

    public function updateCustomer($id, $address, $phone) {
        $stmt = $this->db->prepare("UPDATE CUSTOMER SET Address = ?, Phone = ? WHERE Id = ?");
        $stmt->bind_param('ssi', $address, $phone, $id);
        return $stmt->execute();
    }

    public function deleteCustomer($id) {
        $stmt = $this->db->prepare("DELETE FROM CUSTOMER WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

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

    public function updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId) {
        $stmt = $this->db->prepare("UPDATE BOOK SET Title = ?, Description = ?, Price = ?, Cover = ?, Category_id = ?, Publisher_id = ?, Author_id = ? WHERE Id = ?");
        $stmt->bind_param('ssdsiiii', $title, $description, $price, $cover, $categoryId, $publisherId, $authorId, $id);
        return $stmt->execute();
    }

    public function deleteBook($id) {
        $stmt = $this->db->prepare("DELETE FROM BOOK WHERE Id = ?");
        $stmt->bind_param('i', $id);
        return $stmt->execute();
    }

    // Aggiungi altri metodi per le altre tabelle come ADMIN, AUTHOR, CATEGORY, ecc.
}
?>