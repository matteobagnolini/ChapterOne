<?php
// filepath: c:\Users\Giuseppe\Documents\Progetti\ChapterOne\src\tests\BaseTest.php

use PHPUnit\Framework\TestCase;

abstract class BaseTest extends TestCase {
    protected MySqlDatabase $db;

    protected function setUp(): void {
        // Inizializza il database
        $this->db = new MySqlDatabase('database', 'root', 'mypassword', 'Chapter_one', 3306);

        // Pulisci il database prima di ogni test
        $this->tearDown();
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
}