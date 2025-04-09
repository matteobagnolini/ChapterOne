<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class PostTest extends TestCase {
    private PostManager $db;
    private int $authorId;
    private int $bookId;

    protected function setUp(): void {
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

        // Inserire dati necessari per i test
        $this->authorId = $this->db->insertAuthor('John', 'Doe');
        $this->bookId = $this->db->insertBook(
            'The Great Gatsby',
            'A classic novel by F. Scott Fitzgerald',
            10.99,
            'cover.jpg',
            null, // Nessuna categoria
            null, // Nessun publisher
            $this->authorId
        );
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

    public function testPostCRUD(): void {
        // Inserire un post
        $postId = $this->db->insertPost(
            'This is a post about a book.',
            '2025-04-09 12:00:00',
            $this->authorId,
            $this->bookId
        );
        $this->assertIsInt($postId);

        // Recuperare il post inserito
        $post = $this->db->getPostById($postId);
        $this->assertEquals('This is a post about a book.', $post['Text']);
        $this->assertEquals('2025-04-09 12:00:00', $post['Publication_date']);
        $this->assertEquals($this->authorId, $post['Author_id']);
        $this->assertEquals($this->bookId, $post['Book_id']);

        // Aggiornare il post
        $updated = $this->db->updatePost(
            $postId,
            'Updated post text.',
            '2025-04-10 12:00:00',
            $this->authorId,
            $this->bookId
        );
        $this->assertTrue($updated);

        // Verificare l'aggiornamento
        $updatedPost = $this->db->getPostById($postId);
        $this->assertEquals('Updated post text.', $updatedPost['Text']);
        $this->assertEquals('2025-04-10 12:00:00', $updatedPost['Publication_date']);

        // Eliminare il post
        $deleted = $this->db->deletePost($postId);
        $this->assertTrue($deleted);

        // Verificare che il post sia stato eliminato
        $deletedPost = $this->db->getPostById($postId);
        $this->assertNull($deletedPost);
    }

    public function testInsertPostWithoutReferences(): void {
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
        $postId = $this->db->insertPost(
            'Post with only book.',
            '2025-04-09 12:00:00',
            null,
            $this->bookId
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('Post with only book.', $post['Text']);
        $this->assertNull($post['Author_id']);
        $this->assertEquals($this->bookId, $post['Book_id']);
    }

    public function testInsertPostWithOnlyAuthor(): void {
        $postId = $this->db->insertPost(
            'Post with only author.',
            '2025-04-09 12:00:00',
            $this->authorId,
            null
        );
        $this->assertIsInt($postId);

        $post = $this->db->getPostById($postId);
        $this->assertEquals('Post with only author.', $post['Text']);
        $this->assertEquals($this->authorId, $post['Author_id']);
        $this->assertNull($post['Book_id']);
    }

    public function testDeleteBookCascade(): void {
        $postId = $this->db->insertPost(
            'Post with book.',
            '2025-04-09 12:00:00',
            $this->authorId,
            $this->bookId
        );

        $this->db->deleteBook($this->bookId);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }

    public function testDeleteAuthorCascade(): void {
        $postId = $this->db->insertPost(
            'Post with author.',
            '2025-04-09 12:00:00',
            $this->authorId,
            $this->bookId
        );

        $this->db->deleteAuthor($this->authorId);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }

    public function testDeletePost(): void {
        $postId = $this->db->insertPost(
            'Post to delete.',
            '2025-04-09 12:00:00',
            $this->authorId,
            $this->bookId
        );

        $deleted = $this->db->deletePost($postId);
        $this->assertTrue($deleted);

        $post = $this->db->getPostById($postId);
        $this->assertNull($post);
    }
}
?>