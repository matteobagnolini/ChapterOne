<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class AdminTest extends TestCase {
    private AdminManager $db;

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

    public function testAdminCRUD() {
        $adminId = $this->db->insertAdmin('John', 'Doe', 'john.doe@example.com', 'password123');
        $this->assertIsInt($adminId);

        $admin = $this->db->getAdminById($adminId);
        $this->assertEquals('John', $admin['First_name']);
        $this->assertEquals('Doe', $admin['Last_name']);

        $updated = $this->db->updateAdmin($adminId, 'Jane', 'Doe', 'jane.doe@example.com', 'newpassword123');
        $this->assertTrue($updated);

        $updatedAdmin = $this->db->getAdminById($adminId);
        $this->assertEquals('Jane', $updatedAdmin['First_name']);
        $this->assertEquals('Doe', $updatedAdmin['Last_name']);

        $deleted = $this->db->deleteAdmin($adminId);
        $this->assertTrue($deleted);

        $deletedAdmin = $this->db->getAdminById($adminId);
        $this->assertNull($deletedAdmin);
    }

    public function testDuplicateEmail() {
        $adminId1 = $this->db->insertAdmin('Alice', 'Smith', 'alice.smith@example.com', 'password123');
        $this->assertIsInt($adminId1);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAdmin('Bob', 'Johnson', 'alice.smith@example.com', 'password456');
    }

    public function testMissingRequiredFields() {
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertAdmin('Charlie', null, 'charlie.brown@example.com', 'password123');

        $adminId = $this->db->insertAdmin('Diana', 'Prince', 'diana.prince@example.com', 'password123');
        $this->assertIsInt($adminId);

        $this->expectException(mysqli_sql_exception::class);
        $this->db->updateAdmin($adminId, null, 'Prince', 'diana.prince@example.com', 'password123');
    }
}
?>