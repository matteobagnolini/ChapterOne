<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/BaseTest.php';



class PublisherTest extends BaseTest {


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

    public function testDuplicatePublisherError() {
        $this->tearDown();
        // Insert a publisher
        $publisherId = $this->db->insertPublisher('Penguin Books');
        $this->assertIsInt($publisherId);

        // Try to insert the same publisher again
        $this->expectException(Exception::class);
        $this->db->insertPublisher('Penguin Books');
    }
}
