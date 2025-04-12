<?php

use PHPUnit\Framework\TestCase;
require_once __DIR__ . '/../db/database.php';

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

        // Resetta l'AUTO_INCREMENT per ogni tabella
        $this->db->db->query("ALTER TABLE BOOK_IN_CART AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE CART AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE REVIEW AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE `ORDER` AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE ORDER_DETAIL AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE DISCOUNT_CODE_USAGE AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE DISCOUNT_CODE AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE POST AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE BOOK AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE CATEGORY AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE AUTHOR AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE PUBLISHER AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE CUSTOMER AUTO_INCREMENT = 1");
        $this->db->db->query("ALTER TABLE ADMIN AUTO_INCREMENT = 1");
        }
}