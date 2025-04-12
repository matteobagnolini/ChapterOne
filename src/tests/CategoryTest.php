<?php


require_once __DIR__ . '/BaseTest.php';

class CategoryTest extends BaseTest {
  

    public function testCategoryCRUD() {
        $this->tearDown();
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
        $this->tearDown();
        $categoryId1 = $this->db->insertCategory('Non-Fiction');
        $this->assertIsInt($categoryId1);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCategory('Non-Fiction');
    }

    public function testMissingRequiredFields() {
        $this->tearDown();
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCategory(null);
    }
}
?>