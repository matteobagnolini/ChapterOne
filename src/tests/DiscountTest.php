<?php

require_once __DIR__ . '/BaseTest.php';

class DiscountTest extends BaseTest {

    public function testInsertUpdateDeleteDiscount(): void {
        $this->tearDown();

        // Inserisci un codice sconto
        $discountCodeId = $this->db->insertDiscountCode(
            'DISCOUNT20', 
            'fixed', 
            20.00, 
            '2025-01-01', // Data di inizio valida
            '2025-12-31', // Data di fine valida
            false, 
            true
        );

        // Verifica che il codice sconto sia stato inserito
        $this->assertIsInt($discountCodeId, "Il codice sconto dovrebbe essere stato inserito con un ID valido.");

        // Recupera il codice sconto dal database
        $discount = $this->db->getDiscountCodeById($discountCodeId);
        $this->assertEquals('DISCOUNT20', $discount['Code'], "Il codice sconto dovrebbe essere 'DISCOUNT20'.");
        $this->assertEquals(20.00, $discount['Value'], "Il valore del codice sconto dovrebbe essere 20.00.");
        $this->assertEquals('fixed', $discount['Type'], "Il tipo del codice sconto dovrebbe essere 'fixed'.");

        // Aggiorna il codice sconto
        $this->db->updateDiscountCode($discountCodeId, 'DISCOUNT25', 'percentage', 25.00, '2025-01-01', '2025-12-31', false, true);

        // Verifica che il codice sconto sia stato aggiornato
        $updatedDiscount = $this->db->getDiscountCodeById($discountCodeId);
        $this->assertEquals('DISCOUNT25', $updatedDiscount['Code'], "Il codice sconto dovrebbe essere stato aggiornato a 'DISCOUNT25'.");
        $this->assertEquals(25.00, $updatedDiscount['Value'], "Il valore del codice sconto dovrebbe essere stato aggiornato a 25.00.");
        $this->assertEquals('percentage', $updatedDiscount['Type'], "Il tipo del codice sconto dovrebbe essere stato aggiornato a 'percentage'.");

        // Elimina il codice sconto
        $this->db->deleteDiscountCode($discountCodeId);

        // Verifica che il codice sconto sia stato eliminato
        $deletedDiscount = $this->db->getDiscountCodeById($discountCodeId);
        $this->assertNull($deletedDiscount, "Il codice sconto dovrebbe essere stato eliminato.");
    }

    public function testApplyPercentageDiscountToOrder(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 20.00, 'cover1.jpg', null, null, null);
        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 30.00, 'cover2.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1
        $this->db->insertBookInCart($cartId, $book2Id, 2); // 2 copie di Book 2

        // Calcola il totale del carrello
        $cartTotal = 20.00 + (30.00 * 2); // 80.00

        // Aggiungi un codice sconto
        $discountCodeId = $this->db->insertDiscountCode(
            'DISCOUNT10', 
            'percentage', 
            10.00, 
            '2025-04-01', // Data di inizio valida
            '2030-12-31', // Data di fine valida
            false, 
            true
        );

        // Applica lo sconto e crea l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId); // Applica il 10% di sconto
        $this->assertIsInt($orderId);

        // Verifica che il totale dell'ordine sia corretto
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals(72.00, $order['Total'], "Il totale dell'ordine dovrebbe essere 72.00 dopo l'applicazione dello sconto.");

        // Verifica che il codice sconto sia stato associato all'ordine
        $this->assertEquals($discountCodeId, $order['Discount_code_id'], "Il codice sconto dovrebbe essere associato all'ordine.");
    }

    public function testApplyFixedDiscountToOrder(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Jane', 'Smith', 'jane.smith@example.com', 'password123', '456 Elm St', '0987654321');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 50.00, 'cover1.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1

        // Calcola il totale del carrello
        $cartTotal = 50.00;

        // Aggiungi un codice sconto
        $discountCodeId = $this->db->insertDiscountCode(
            'DISCOUNT15', 
            'fixed', 
            15.00, 
            '2025-04-01', // Data di inizio valida
            '2025-12-31', // Data di fine valida
            false, 
            true
        );

        // Applica lo sconto e crea l'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId); // Applica lo sconto fisso
        $this->assertIsInt($orderId);

        // Verifica che il totale dell'ordine sia corretto
        $order = $this->db->getOrderById($orderId);
        $this->assertEquals(35.00, $order['Total'], "Il totale dell'ordine dovrebbe essere 35.00 dopo l'applicazione dello sconto fisso.");

        // Verifica che il codice sconto sia stato associato all'ordine
        $this->assertEquals($discountCodeId, $order['Discount_code_id'], "Il codice sconto dovrebbe essere associato all'ordine.");
    }

    public function testSingleUseDiscount(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Alice', 'Brown', 'alice.brown@example.com', 'password123', '789 Pine St', '1122334455');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 40.00, 'cover1.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1

        // Calcola il totale del carrello
        $cartTotal = 40.00;

        // Aggiungi un codice sconto a uso singolo
        $discountCodeId = $this->db->insertDiscountCode(
            'SINGLEUSE', 
            'fixed', 
            10.00, 
            '2025-04-01', // Data di inizio valida
            '2025-12-31', // Data di fine valida
            true, // Uso singolo
            true
        );

        // Applica lo sconto e crea il primo ordine
        $orderId1 = $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId);
        $this->assertIsInt($orderId1);

        // Verifica che il totale dell'ordine sia corretto
        $order1 = $this->db->getOrderById($orderId1);
        $this->assertEquals(30.00, $order1['Total'], "Il totale dell'ordine dovrebbe essere 30.00 dopo l'applicazione dello sconto.");

        // Prova a riutilizzare lo stesso codice sconto
        $this->expectException(Exception::class);
        $this->db->insertOrder('2025-04-10 12:00:00', $cartTotal, $customerId, $discountCodeId);
    }

    public function testInactiveDiscount(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Bob', 'White', 'bob.white@example.com', 'password123', '123 Oak St', '5566778899');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 60.00, 'cover1.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1

        // Calcola il totale del carrello
        $cartTotal = 60.00;

        // Aggiungi un codice sconto inattivo
        $discountCodeId = $this->db->insertDiscountCode(
            'INACTIVE', 
            'fixed', 
            20.00, 
            '2025-04-01', // Data di inizio valida
            '2025-12-31', // Data di fine valida
            false, 
            false // Non attivo
        );

        // Prova ad applicare lo sconto inattivo
        $this->expectException(Exception::class);
        $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId);
    }

  
    public function testExpiredDiscount(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Charlie', 'Green', 'charlie.green@example.com', 'password123', '321 Maple St', '6677889900');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 50.00, 'cover1.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1

        // Calcola il totale del carrello
        $cartTotal = 50.00;

        // Aggiungi un codice sconto scaduto
        $discountCodeId = $this->db->insertDiscountCode(
            'EXPIRED', 
            'fixed', 
            10.00, 
            '2024-01-01', // Data di inizio
            '2024-12-31', // Data di fine (scaduto)
            false, 
            true
        );

        // Prova ad applicare lo sconto scaduto
        $this->expectException(Exception::class);
        $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId);
    }

    
    public function testSingleUseDiscountOnMultipleOrders(): void {
        $this->tearDown();

        // Aggiungi un cliente
        $customerId = $this->db->insertCustomer('Diana', 'Blue', 'diana.blue@example.com', 'password123', '654 Birch St', '7788990011');

        // Aggiungi libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 30.00, 'cover1.jpg', null, null, null);

        // Aggiungi i libri al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1); // 1 copia di Book 1

        // Calcola il totale del carrello
        $cartTotal = 30.00;

        // Aggiungi un codice sconto a uso singolo
        $discountCodeId = $this->db->insertDiscountCode(
            'SINGLEUSE2', 
            'fixed', 
            5.00, 
            '2025-01-01', // Data di inizio valida
            '2025-12-31', // Data di fine valida
            true, // Uso singolo
            true
        );

        // Applica lo sconto e crea il primo ordine
        $orderId1 = $this->db->insertOrder('2025-04-09 12:00:00', $cartTotal, $customerId, $discountCodeId);
        $this->assertIsInt($orderId1);

        // Verifica che il totale dell'ordine sia corretto
        $order1 = $this->db->getOrderById($orderId1);
        $this->assertEquals(25.00, $order1['Total'], "Il totale dell'ordine dovrebbe essere 25.00 dopo l'applicazione dello sconto.");

        // Prova a riutilizzare lo stesso codice sconto su un secondo ordine
        $this->expectException(Exception::class);
        $this->db->insertOrder('2025-04-10 12:00:00', $cartTotal, $customerId, $discountCodeId);
    }
}