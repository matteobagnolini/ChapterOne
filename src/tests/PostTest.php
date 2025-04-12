<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/BaseTest.php';

class PostTest extends TestCase {
    private PostManager $db;

    protected function setUp(): void {
        // Inizializza il database
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);
    }

    protected function tearDown(): void {
        // Pulisci tutte le tabelle
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

    public function testPostCRUD(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null,
            null,
            $authorId
        );

        $postId = $this->db->insertPost(
            'This is a post about a book.',
            '2025-04-09 12:00:00',
            $authorId,
            $bookId
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('This is a post about a book.', $post['Text']);
        $this->assertEquals('2025-04-09 12:00:00', $post['Publication_date']);
        $this->assertEquals($authorId, $post['Author_id']);
        $this->assertEquals($bookId, $post['Book_id']);

        $updated = $this->db->updatePost(
            $postId,
            'Updated post text.',
            '2025-04-10 12:00:00',
            $authorId,
            $bookId
        );
        $this->assertTrue($updated);

        $updatedPost = $this->db->getPostById($postId);
        $this->assertEquals('Updated post text.', $updatedPost['Text']);
        $this->assertEquals('2025-04-10 12:00:00', $updatedPost['Publication_date']);

        $deleted = $this->db->deletePost($postId);
        $this->assertTrue($deleted);

        $deletedPost = $this->db->getPostById($postId);
        $this->assertNull($deletedPost);
    }

    public function testInsertPostWithoutReferences(): void {
        $this->tearDown(); 

        $postId = $this->db->insertPost(
            'Post without references.',
            '2025-04-09 12:00:00',
            null,
            null
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('Post without references.', $post['Text']);
        $this->assertNull($post['Author_id']);
        $this->assertNull($post['Book_id']);
    }

    public function testInsertPostWithOnlyBook(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null,
            null,
            $authorId
        );

        $postId = $this->db->insertPost(
            'Post with only book.',
            '2025-04-09 12:00:00',
            null,
            $bookId
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('Post with only book.', $post['Text']);
        $this->assertNull($post['Author_id']);
        $this->assertEquals($bookId, $post['Book_id']);
    }

    public function testInsertPostWithOnlyAuthor(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');

        $postId = $this->db->insertPost(
            'Post with only author.',
            '2025-04-09 12:00:00',
            $authorId,
            null
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('Post with only author.', $post['Text']);
        $this->assertEquals($authorId, $post['Author_id']);
        $this->assertNull($post['Book_id']);
    }

    public function testDeleteBookCascade(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null,
            null,
            $authorId
        );

        $postId = $this->db->insertPost(
            'Post with book.',
            '2025-04-09 12:00:00',
            $authorId,
            $bookId
        );

        $this->db->deleteBook($bookId);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }

    public function testDeleteAuthorCascade(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null,
            null,
            $authorId
        );

        $postId = $this->db->insertPost(
            'Post with author.',
            '2025-04-09 12:00:00',
            $authorId,
            $bookId
        );

        $this->db->deleteAuthor($authorId);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }

    public function testDeletePost(): void {
        $this->tearDown(); 

        $authorId = $this->db->insertAuthor('John', 'Doe');
        $bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null,
            null,
            $authorId
        );

        $postId = $this->db->insertPost(
            'Post to delete.',
            '2025-04-09 12:00:00',
            $authorId,
            $bookId
        );

        $deleted = $this->db->deletePost($postId);
        $this->assertTrue($deleted);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }
}
?>