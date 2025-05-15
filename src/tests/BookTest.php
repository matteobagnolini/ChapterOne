<?php

require_once __DIR__ . '/BaseTest.php';

class BookTest extends BaseTest {
 
    public function testBookCRUD(): void {
        $this->tearDown();

        $categoryId = $this->db->insertCategory('Fiction');
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Publisher St');
        $authorId = $this->db->insertAuthor('John', 'Doe');

        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
        );
        $this->assertIsInt($bookId);

        $book = $this->db->getBookById($bookId);
        $this->assertSame('The Great Gatsby', $book['Title']);
        $this->assertSame('A classic novel by F. Scott Fitzgerald', $book['Description']);
        $this->assertSame(10.99, (float) $book['Price']);
        $this->assertSame('cover.jpg', $book['Cover']);
        $this->assertSame($categoryId, (int) $book['Category_id']);
        $this->assertSame($publisherId, (int) $book['Publisher_id']);
        $this->assertSame($authorId, (int) $book['Author_id']);

        $updated = $this->db->updateBook(
            $bookId,
            'The Great Gatsby - Updated',
            'An updated description',
            12.99,
            'new_cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
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
        $this->tearDown();

        $categoryId = $this->db->insertCategory('Fiction');
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Publisher St');
        $authorId = $this->db->insertAuthor('John', 'Doe');

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertBook(
            null,
            'A book without a title',
            9.99,
            'cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
        );
    }

    public function testSetNullOnDeletePublisher(): void {
        $this->tearDown();

        $categoryId = $this->db->insertCategory('Fiction');
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Publisher St');
        $authorId = $this->db->insertAuthor('John', 'Doe');


        $bookId = $this->db->insertBook(
            'Book with Publisher',
            'Description',
            15.99,
            'cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
        );

        $this->db->deletePublisher($publisherId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Publisher_id']);
    }

    public function testSetNullOnDeleteAuthor(): void {
        $this->tearDown();

        $categoryId = $this->db->insertCategory('Fiction');
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Publisher St');
        $authorId = $this->db->insertAuthor('John', 'Doe');


        $bookId = $this->db->insertBook(
            'Book with Author',
            'Description',
            15.99,
            'cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
        );

        $this->db->deleteAuthor($authorId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Author_id']);
    }

    public function testSetNullOnDeleteCategory(): void {
        $this->tearDown();


        $categoryId = $this->db->insertCategory('Fiction');
        $publisherId = $this->db->insertPublisher('Penguin Books', '123 Publisher St');
        $authorId = $this->db->insertAuthor('John', 'Doe');


        $bookId = $this->db->insertBook(
            'Book with Category',
            'Description',
            15.99,
            'cover.jpg',
            $categoryId,
            $publisherId,
            $authorId
        );

        $this->db->deleteCategory($categoryId);

        $book = $this->db->getBookById($bookId);
        $this->assertNull($book['Category_id']);
    }
}
?>