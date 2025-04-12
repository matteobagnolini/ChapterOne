<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';



class PublisherTest extends TestCase {
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

    public function testPublisherCRUD() {
        $this->tearDown();
        // Insert a publisher
        $publisherId = $this->db->insertPublisher('Penguin Books');
        $this->assertIsInt($publisherId);

        // Retrieve the inserted publisher
        $publisher = $this->db->getPublisherById($publisherId);
        $this->assertEquals('Penguin Books', $publisher['Name']);

        // Update the publisher
        $updated = $this->db->updatePublisher($publisherId, 'HarperCollins');
        $this->assertTrue($updated);

        // Verify the update
        $updatedPublisher = $this->db->getPublisherById($publisherId);
        $this->assertEquals('HarperCollins', $updatedPublisher['Name']);

        // Delete the publisher
        $deleted = $this->db->deletePublisher($publisherId);
        $this->assertTrue($deleted);

        // Verify the publisher has been deleted
        $deletedPublisher = $this->db->getPublisherById($publisherId);
        $this->assertNull($deletedPublisher);
    }
}
