<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class BookTest extends TestCase {
    private BookManager $db;
    private int $categoryId;
    private int $publisherId;
    private int $authorId;

    protected function setUp(): void {
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

        // Inserire dati necessari per i test
        $this->categoryId = $this->db->insertCategory('Fiction');
        $this->publisherId = $this->db->insertPublisher('Penguin Books');
        $this->authorId = $this->db->insertAuthor('John', 'Doe');
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

    public function testBookCRUD(): void {
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );
        $this->assertIsInt($bookId);

        $book = $this->db->getBookById($bookId);
        $this->assertSame('The Great Gatsby', $book['Title']);
        $this->assertSame('A classic novel by F. Scott Fitzgerald', $book['Description']);
        $this->assertSame(10.99, (float) $book['Price']);
        $this->assertSame('cover.jpg', $book['Cover']);
        $this->assertSame($this->categoryId, (int) $book['Category_id']);
        $this->assertSame($this->publisherId, (int) $book['Publisher_id']);
        $this->assertSame($this->authorId, (int) $book['Author_id']);

        $updated = $this->db->updateBook(
            $bookId,
            'The Great Gatsby - Updated',
            'An updated description',
            12.99,
            'new_cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );
        $this->assertTrue($updated);

        $updatedBook = $this->db->getBookById($bookId);
        $this->assertSame('The Great Gatsby - Updated', $updatedBook['Title']);
        $this->assertSame('An updated description', $updatedBook['Description']);
        $this->assertSame(12.99, (float) $updatedBook['Price']);
        $this->assertSame('new_cover.jpg', $updatedBook['Cover']);

        $deleted = $this->db->deleteBook($bookId);
        $this->assertTrue($deleted);

        $deletedBook = $this->db->getBookById($bookId);
        $this->assertNull($deletedBook);
    }

    public function testMissingRequiredFields(): void {
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertBook(
            null,
            'A book without a title',
            9.99,
            'cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );
    }

    public function testSetNullOnDeletePublisher(): void {
        $bookId = $this->db->insertBook(
            'Book with Publisher',
            'Description',
            15.99,
            'cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );

        $this->db->deletePublisher($this->publisherId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Publisher_id']);
    }

    public function testSetNullOnDeleteAuthor(): void {
        $bookId = $this->db->insertBook(
            'Book with Author',
            'Description',
            15.99,
            'cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );

        $this->db->deleteAuthor($this->authorId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Author_id']);
    }

    public function testSetNullOnDeleteCategory(): void {
        $bookId = $this->db->insertBook(
            'Book with Category',
            'Description',
            15.99,
            'cover.jpg',
            $this->categoryId,
            $this->publisherId,
            $this->authorId
        );

        $this->db->deleteCategory($this->categoryId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Category_id']);
    }
}
?>