<?php


require_once __DIR__ . '/BaseTest.php';

class CustomerTest extends BaseTest {


    public function testCustomerCRUD() {
        $this->tearDown();
        // Insert a fake customer
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Retrieve the inserted customer
        $customer = $this->db->getCustomerById($customerId);
        $this->assertEquals('John', $customer['First_name']);
        $this->assertEquals('Doe', $customer['Last_name']);

        // Update the customer
        $updated = $this->db->updateCustomer($customerId, 'Jane', 'Doe', 'jane.doe@example.com', 'newpassword123', '456 Elm St', '0987654321');
        $this->assertTrue($updated);

        // Verify the update
        $updatedCustomer = $this->db->getCustomerById($customerId);
        $this->assertEquals('Jane', $updatedCustomer['First_name']);
        $this->assertEquals('Doe', $updatedCustomer['Last_name']);

        // Delete the customer
        $deleted = $this->db->deleteCustomer($customerId);
        $this->assertTrue($deleted);

        // Verify the customer has been deleted
        $deletedCustomer = $this->db->getCustomerById($customerId);
        $this->assertNull($deletedCustomer);
    }

    public function testDuplicateEmail() {
        $this->tearDown();
        // Insert the first customer
        $customerId1 = $this->db->insertCustomer('Alice', 'Smith', 'alice.smith@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId1);

        // Attempt to insert a second customer with the same email
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCustomer('Bob', 'Johnson', 'alice.smith@example.com', 'password456', '456 Elm St', '0987654321');
    }

    public function testMissingRequiredFields() {
        $this->tearDown();
        // Attempt to insert a customer without all required fields
        $this->expectException(mysqli_sql_exception::class);
        $this->db->insertCustomer('Charlie', 'Brown', null, 'password123', '789 Oak St', '1234567890');

        // Valid insertion
        $customerId = $this->db->insertCustomer('Diana', 'Prince', 'diana.prince@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Attempt to update the customer by removing a required field
        $this->expectException(mysqli_sql_exception::class);
        $this->db->updateCustomer($customerId, 'Diana', 'Prince', null, 'password123', '123 Main St', '1234567890');
    }

    public function testCustomerOrderAndReviewDeletion(): void {
        $this->tearDown();

        // Creazione del cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Creazione di due libri
        $book1Id = $this->db->insertBook('Book 1', 'Description 1', 20.00, 'cover1.jpg', null, null, null);
        $this->assertIsInt($book1Id);

        $book2Id = $this->db->insertBook('Book 2', 'Description 2', 25.00, 'cover2.jpg', null, null, null);
        $this->assertIsInt($book2Id);

        // Aggiunta del primo libro al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $book1Id, 1);

        // Completamento del primo ordine
        $orderId1 = $this->db->insertOrder('2025-04-09 12:00:00', 20.00, $customerId, null);
        $this->assertIsInt($orderId1);

        // Verifica dei dettagli del primo ordine
        $orderDetails1 = $this->db->getOrderDetails($orderId1);
        $this->assertCount(1, $orderDetails1);
        $this->assertEquals($book1Id, $orderDetails1[0]['Book_id']);
        $this->assertEquals(1, $orderDetails1[0]['Quantity']);
        $this->assertEquals(20.00, $orderDetails1[0]['Subtotal']);

        // Aggiunta di una recensione per il primo libro
        $reviewId1 = $this->db->insertReview('Great book!', 5, $book1Id, $customerId);
        $this->assertIsInt($reviewId1);

        // Verifica della recensione per il primo libro
        $review1 = $this->db->getReviewById($reviewId1);
        $this->assertEquals('Great book!', $review1['Text']);
        $this->assertEquals(5, $review1['Rating']);
        $this->assertEquals($book1Id, $review1['Book_id']);
        $this->assertEquals($customerId, $review1['Customer_id']);

        // Aggiunta del secondo libro al carrello
        $this->db->insertBookInCart($cartId, $book2Id, 1);

        // Completamento del secondo ordine
        $orderId2 = $this->db->insertOrder('2025-04-10 12:00:00', 25.00, $customerId, null);
        $this->assertIsInt($orderId2);

        // Verifica dei dettagli del secondo ordine
        $orderDetails2 = $this->db->getOrderDetails($orderId2);
        $this->assertCount(2, $orderDetails2);
        $this->assertEquals($book2Id, $orderDetails2[1]['Book_id']);
        $this->assertEquals(1, $orderDetails2[1]['Quantity']);
        $this->assertEquals(25.00, $orderDetails2[1]['Subtotal']);

        // Aggiunta di una recensione per il secondo libro
        $reviewId2 = $this->db->insertReview('Another great book!', 4, $book2Id, $customerId);
        $this->assertIsInt($reviewId2);

        // Verifica della recensione per il secondo libro
        $review2 = $this->db->getReviewById($reviewId2);
        $this->assertEquals('Another great book!', $review2['Text']);
        $this->assertEquals(4, $review2['Rating']);
        $this->assertEquals($book2Id, $review2['Book_id']);
        $this->assertEquals($customerId, $review2['Customer_id']);

        // Eliminazione del cliente
        $deleted = $this->db->deleteCustomer($customerId);
        $this->assertTrue($deleted);

        // Verifica che tutto sia stato eliminato
        $deletedCustomer = $this->db->getCustomerById($customerId);
        $this->assertNull($deletedCustomer);

        $deletedCart = $this->db->getCartByCustomerId($customerId);
        $this->assertNull($deletedCart);

        $deletedOrder1 = $this->db->getOrderById($orderId1);
        $this->assertNull($deletedOrder1);

        $deletedOrder2 = $this->db->getOrderById($orderId2);
        $this->assertNull($deletedOrder2);

        $deletedReview1 = $this->db->getReviewById($reviewId1);
        $this->assertNull($deletedReview1);

        $deletedReview2 = $this->db->getReviewById($reviewId2);
        $this->assertNull($deletedReview2);
    }


    public function testCustomerOrderAndReviewDeletion_WithNotifications(): void {
        $this->tearDown();

        // Creazione del cliente
        $customerId = $this->db->insertCustomer('John', 'Doe', 'john.doe@example.com', 'password123', '123 Main St', '1234567890');
        $this->assertIsInt($customerId);

        // Creazione di un libro
        $bookId = $this->db->insertBook('Book 1', 'Description 1', 20.00, 'cover1.jpg', null, null, null);
        $this->assertIsInt($bookId);

        // Aggiunta del libro al carrello
        $cart = $this->db->getCartByCustomerId($customerId);
        $cartId = $cart['Id'];
        $this->db->insertBookInCart($cartId, $bookId, 1);

        // Completamento dell'ordine
        $orderId = $this->db->insertOrder('2025-04-09 12:00:00', 20.00, $customerId, null);
        $this->assertIsInt($orderId);

        // Verifica dei dettagli dell'ordine
        $orderDetails = $this->db->getOrderDetails($orderId);
        $this->assertCount(1, $orderDetails);
        $this->assertEquals($bookId, $orderDetails[0]['Book_id']);
        $this->assertEquals(1, $orderDetails[0]['Quantity']);
        $this->assertEquals(20.00, $orderDetails[0]['Subtotal']);

        // Aggiunta di una recensione per il libro
        $reviewId = $this->db->insertReview('Great book!', 5, $bookId, $customerId);
        $this->assertIsInt($reviewId);

        // Verifica della recensione per il libro
        $review = $this->db->getReviewById($reviewId);
        $this->assertEquals('Great book!', $review['Text']);
        $this->assertEquals(5, $review['Rating']);
        $this->assertEquals($bookId, $review['Book_id']);
        $this->assertEquals($customerId, $review['Customer_id']);

        // Aggiorna lo stato dell'ordine a 'sent'
        $this->db->updateOrderStatus($orderId, 'sent');

        // Verifica notifica per 'sent'
        $notifications = $this->db->getOrderNotifications($orderId);
        $this->assertCount(1, $notifications);
        $this->assertEquals("Il tuo ordine è stato spedito!", $notifications[0]['Message']);
        $this->assertEquals("sent", $notifications[0]['Status']);

        // Aggiorna lo stato dell'ordine a 'arrived'
        $this->db->updateOrderStatus($orderId, 'arrived');

        // Verifica notifica per 'arrived'
        $notifications = $this->db->getOrderNotifications($orderId);
        $this->assertCount(2, $notifications);
        $this->assertEquals("Il tuo ordine è arrivato!", $notifications[1]['Message']);
        $this->assertEquals("arrived", $notifications[1]['Status']);

        // Eliminazione del cliente
        $deleted = $this->db->deleteCustomer($customerId);
        $this->assertTrue($deleted);

        // Verifica che tutto sia stato eliminato
        $deletedCustomer = $this->db->getCustomerById($customerId);
        $this->assertNull($deletedCustomer);

        $deletedCart = $this->db->getCartByCustomerId($customerId);
        $this->assertNull($deletedCart);

        $deletedOrder = $this->db->getOrderById($orderId);
        $this->assertNull($deletedOrder);

        $deletedReview = $this->db->getReviewById($reviewId);
        $this->assertNull($deletedReview);

        // Verifica che le notifiche siano state eliminate
        $deletedNotifications = $this->db->getOrderNotifications($orderId);
        $this->assertEmpty($deletedNotifications);
    }
    
}
