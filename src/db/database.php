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
}
?>