<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class CategoryTest extends TestCase {
    private CategoryManager $db;

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

    public function testCategoryCRUD() {
        $categoryId = $this->db->insertCategory('Fiction');
        $this->assertIsInt($categoryId);

        $category = $this->db->getCategoryById($categoryId);
        $this->assertEquals('Fiction', $category['Name']);

        $updated = $this->db->updateCategory($categoryId, 'Science Fiction');
        $this->assertTrue($updated);

        $updatedCategory = $this->db->getCategoryById($categoryId);
        $this->assertEquals('Science Fiction', $updatedCategory['Name']);

        $deleted = $this->db->deleteCategory($categoryId);
        $this->assertTrue($deleted);

        $deletedCategory = $this->db->getCategoryById($categoryId);
        $this->assertNull($deletedCategory);
    }

    public function testDuplicateCategoryName() {
        $categoryId1 = $this->db->insertCategory('Non-Fiction');
        $this->assertIsInt($categoryId1);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCategory('Non-Fiction');
    }

    public function testMissingRequiredFields() {
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCategory(null);
    }
}
?>