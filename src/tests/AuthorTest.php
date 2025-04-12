<?php


require_once __DIR__ . '/BaseTest.php';

class AuthorTest extends BaseTest {
   

    public function testAuthorCRUD() {
        $this->tearDown();
        $authorId = $this->db->insertAuthor('John', 'Doe');
        $this->assertIsInt($authorId);

        $author = $this->db->getAuthorById($authorId);
        $this->assertEquals('John', $author['First_name']);
        $this->assertEquals('Doe', $author['Last_name']);

        $updated = $this->db->updateAuthor($authorId, 'Jane', 'Doe');
        $this->assertTrue($updated);

        $updatedAuthor = $this->db->getAuthorById($authorId);
        $this->assertEquals('Jane', $updatedAuthor['First_name']);
        $this->assertEquals('Doe', $updatedAuthor['Last_name']);

        $deleted = $this->db->deleteAuthor($authorId);
        $this->assertTrue($deleted);

        $deletedAuthor = $this->db->getAuthorById($authorId);
        $this->assertNull($deletedAuthor);
    }

    public function testDuplicateAuthor() {
        $this->tearDown();
        $authorId1 = $this->db->insertAuthor('Alice', 'Smith');
        $this->assertIsInt($authorId1);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAuthor('Alice', 'Smith');
    }

    public function testMissingRequiredFields() {
        $this->tearDown();
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAuthor(null, 'Brown');

        $authorId = $this->db->insertAuthor('Diana', 'Prince');
        $this->assertIsInt($authorId);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->updateAuthor($authorId, null, 'Prince');
    }
}
?>