<?php
require_once '../db/database.php';
class DatabaseTest {
    private $db;

    public function __construct() {
        $this->db = new MySqlDatabase('mysql', 'root', 'mypassword', 'Chapter_one', 3306);
        $this->setUp();
    }

    public function setUp() {
        $this->db->db->query("TRUNCATE TABLE USER");
        $this->db->db->query("TRUNCATE TABLE CUSTOMER");
        $this->db->db->query("TRUNCATE TABLE BOOK");
    }

    public function tearDown() {
        $this->db->db->query("TRUNCATE TABLE USER");
        $this->db->db->query("TRUNCATE TABLE CUSTOMER");
        $this->db->db->query("TRUNCATE TABLE BOOK");
    }

    public function testInsertAndGetUser() {
        $userId = $this->db->insertUser('John', 'Doe', 'john.doe@example.com', 'password123');
        $user = $this->db->getUserById($userId);
        assert($user['First_name'] === 'John');
        assert($user['Last_name'] === 'Doe');
        assert($user['Email'] === 'john.doe@example.com');
    }

    public function testInsertAndGetCustomer() {
        $userId = $this->db->insertUser('Jane', 'Doe', 'jane.doe@example.com', 'password123');
        $customerId = $this->db->insertCustomer($userId, '123 Main St', '555-1234');
        $customer = $this->db->getCustomerById($customerId);
        assert($customer['Customer_id'] === $userId);
        assert($customer['Address'] === '123 Main St');
        assert($customer['Phone'] === '555-1234');
    }

    public function testInsertAndGetBook() {
        $bookId = $this->db->insertBook('Sample Book', 'This is a sample book.', 19.99, 'cover.jpg', 1, 1, 1);
        $book = $this->db->getBookById($bookId);
        assert($book['Title'] === 'Sample Book');
        assert($book['Description'] === 'This is a sample book.');
        assert($book['Price'] == 19.99);
        assert($book['Cover'] === 'cover.jpg');
    }

    public function runTests() {
        $this->testInsertAndGetUser();
        $this->testInsertAndGetCustomer();
        $this->testInsertAndGetBook();
        $this->tearDown();
    }
}

// Esegui i test
$test = new DatabaseTest();
$test->runTests();
?>