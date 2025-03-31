<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class CustomerTest extends TestCase {
    private $db;

    protected function setUp(): void {
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);
    }

    protected function tearDown(): void {
        // Clean up the database after each test
        $this->db->db->query("DELETE FROM BOOK_IN_CART");
        $this->db->db->query("DELETE FROM CART");
        $this->db->db->query("DELETE FROM REVIEW");
        $this->db->db->query("DELETE FROM `ORDER`");
        $this->db->db->query("DELETE FROM ORDER_DETAIL");
        $this->db->db->query("DELETE FROM DISCOUNT_CODE_USAGE");
        $this->db->db->query("DELETE FROM DISCOUNT_CODE");
        $this->db->db->query("DELETE FROM POST");
        $this->db->db->query("DELETE FROM BOOK");
        $this->db->db->query("DELETE FROM CATEGORY");
        $this->db->db->query("DELETE FROM AUTHOR");
        $this->db->db->query("DELETE FROM PUBLISHER");
        $this->db->db->query("DELETE FROM CUSTOMER");
        $this->db->db->query("DELETE FROM ADMIN");
    }

    public function testCustomerCRUD() {
        // Insert a fake customer
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Retrieve the inserted customer
        $customer = $this->db->getCustomerById($customerId);
        $this->assertEquals('John', $customer['First_name']);
        $this->assertEquals('Doe', $customer['Last_name']);

        // Update the customer
        $updated = $this->db->updateCustomer($customerId, 'Jane', 'Doe', 'jane.doe@example.com', 'newpassword123', '456 Elm St', '0987654321');
        $this->assertTrue($updated);

        // Verify the update
        $updatedCustomer = $this->db->getCustomerById($customerId);
        $this->assertEquals('Jane', $updatedCustomer['First_name']);
        $this->assertEquals('Doe', $updatedCustomer['Last_name']);

        // Delete the customer
        $deleted = $this->db->deleteCustomer($customerId);
        $this->assertTrue($deleted);

        // Verify the customer has been deleted
        $deletedCustomer = $this->db->getCustomerById($customerId);
        $this->assertNull($deletedCustomer);
    }

    public function testDuplicateEmail() {
        // Insert the first customer
        $customerId1 = $this->db->insertCustomer('Alice', 'Smith', 'alice.smith@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId1);

        // Attempt to insert a second customer with the same email
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCustomer('Bob', 'Johnson', 'alice.smith@example.com', 'password456', '456 Elm St', '0987654321');
    }

    public function testMissingRequiredFields() {
        // Attempt to insert a customer without all required fields
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCustomer('Charlie', 'Brown', null, 'password123', '789 Oak St', '1234567890');

        // Valid insertion
        $customerId = $this->db->insertCustomer('Diana', 'Prince', 'diana.prince@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Attempt to update the customer by removing a required field
        $this->expectException(mysqli_sql_exception::class);
        $this->db->updateCustomer($customerId, 'Diana', 'Prince', null, 'password123', '123 Main St', '1234567890');
    }
}
