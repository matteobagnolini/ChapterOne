<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class AuthorTest extends TestCase {
    private AuthorManager $db;

    protected function setUp(): void {
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);
    }

    protected function tearDown(): void {
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

    public function testAuthorCRUD() {
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
        $authorId1 = $this->db->insertAuthor('Alice', 'Smith');
        $this->assertIsInt($authorId1);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAuthor('Alice', 'Smith');
    }

    public function testMissingRequiredFields() {
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAuthor(null, 'Brown');

        $authorId = $this->db->insertAuthor('Diana', 'Prince');
        $this->assertIsInt($authorId);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->updateAuthor($authorId, null, 'Prince');
    }
}
?>