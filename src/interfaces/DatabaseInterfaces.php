<?php

interface CustomerManager {
    public function getCustomers();
    public function getCustomerById($id);
    public function insertCustomer($firstName, $lastName, $email, $password, $address, $phone);
    public function updateCustomer($id, $firstName, $lastName, $email, $password, $address, $phone);
    public function deleteCustomer($id);
}

interface AdminManager {
    public function getAdmins();
    public function getAdminById($id);
    public function insertAdmin($firstName, $lastName, $email, $password);
    public function updateAdmin($id, $firstName, $lastName, $email, $password);
    public function deleteAdmin($id);
}

interface AuthorManager {
    public function getAuthors();
    public function getAuthorById($id);
    public function insertAuthor($firstName, $lastName);
    public function updateAuthor($id, $firstName, $lastName);
    public function deleteAuthor($id);
}

interface CategoryManager {
    public function getCategories();
    public function getCategoryById($id);
    public function insertCategory($name);
    public function updateCategory($id, $name);
    public function deleteCategory($id);
}

interface PublisherManager {
    public function getPublishers();
    public function getPublisherById($id);
    public function insertPublisher($name);
    public function updatePublisher($id, $name);
    public function deletePublisher($id);
}

interface BookManager {
    public function getBooks();
    public function getBookById($id);
    public function insertBook($title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function deleteBook($id);
}

interface PostManager {
    public function getPosts();
    public function getPostById($id);
    public function insertPost($text, $publicationDate, $authorId, $bookId);
    public function updatePost($id, $text, $publicationDate, $authorId, $bookId);
    public function deletePost($id);
}

interface ReviewManager {
    public function getReviews();
    public function getReviewById($id);
    public function insertReview($text, $rating, $bookId, $customerId);
    public function updateReview($id, $text, $rating, $bookId, $customerId);
    public function deleteReview($id);
}

interface CartManager {
    public function getCarts();
    public function getCartByCustomerId($id);
    public function updateCart($id, $subtotal, $lastModified, $itemCount, $customerId);
}

interface BookInCartManager {
    public function getBooksInCart($cartId);
    public function getBookInCartById($id);
    public function insertBookInCart($cartId, $bookId, $quantity);
    public function updateBookInCart($cartId, $bookId, $quantity);
    public function deleteBookInCart($cartId, $bookId);
}

interface DiscountCodeManager {
    public function getDiscountCodes();
    public function getDiscountCodeById($id);
    public function insertDiscountCode($code, $type, $value, $startDate, $endDate, $singleUse, $active);
    public function updateDiscountCode($id, $code, $type, $value, $startDate, $endDate, $singleUse, $active);
    public function deleteDiscountCode($id);
}

interface OrderManager {
    public function getOrders();
    public function getOrderById($id);
    public function insertOrder($date, $total, $customerId, $discountCodeId);
    public function updateOrder($id, $date, $total, $customerId, $discountCodeId);
    public function deleteOrder($id);
    public function updateOrderStatus($id, $status);
    
}

interface OrderDetailManager {
    public function getOrderDetails();
    public function getOrderDetailById($id);
    public function insertOrderDetail($quantity, $subtotal, $orderId, $bookId);
    public function updateOrderDetail($id, $quantity, $subtotal, $orderId, $bookId);
    public function deleteOrderDetail($id);
}

interface DiscountCodeUsageManager {
    public function getDiscountCodeUsages();
    public function getDiscountCodeUsageById($id);
    public function insertDiscountCodeUsage($usageDate, $discountCodeId, $customerId, $orderId);
    public function updateDiscountCodeUsage($id, $usageDate, $discountCodeId, $customerId, $orderId);
    public function deleteDiscountCodeUsage($id);
}

interface OrderNotificationManager {
    public function getOrderNotifications();
    public function getOrderNotificationById($id);
    public function insertOrderNotification($orderId, $message, $status);
    public function updateOrderNotification($id, $orderId, $message, $status);
    public function deleteOrderNotification($id);
    public function SetSeenNotification($id);
}
?>