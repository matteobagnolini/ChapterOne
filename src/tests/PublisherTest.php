<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/BaseTest.php';

class PublisherTest extends BaseTest {


    public function testPublisherCRUD() {
        $this->tearDown();
        // Insert a publisher
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Main St');
        $this->assertIsInt($publisherId);

        // Retrieve the inserted publisher
        $publisher = $this->db->getPublisherById($publisherId);
        $this->assertEquals('Penguin Books', $publisher['Name']);
        $this->assertEquals('123 Main St', $publisher['Address']);

        // Update the publisher
        $updated = $this->db->updatePublisher($publisherId, 'HarperCollins', '456 Oak Ave');
        $this->assertTrue($updated);

        // Verify the update
        $updatedPublisher = $this->db->getPublisherById($publisherId);
        $this->assertEquals('HarperCollins', $updatedPublisher['Name']);
        $this->assertEquals('456 Oak Ave', $updatedPublisher['Address']);

        // Delete the publisher
        $deleted = $this->db->deletePublisher($publisherId);
        $this->assertTrue($deleted);

        // Verify the publisher has been deleted
        $deletedPublisher = $this->db->getPublisherById($publisherId);
        $this->assertNull($deletedPublisher);
    }

    public function testDuplicatePublisherError() {
        $this->tearDown();
        // Insert a publisher
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Main St');
        $this->assertIsInt($publisherId);

        // Try to insert the same publisher again
        try {
            $this->db->insertPublisher('Penguin Books', '789 Pine Ln');
        } catch (mysqli_sql_exception $e) {
            $this->assertEquals(1062, $e->getCode());
        }
    }
}