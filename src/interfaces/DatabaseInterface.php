<?php
interface DatabaseInterface {
    public function getUsers();
    public function getUserById($id);
    public function insertUser($firstName, $lastName, $email, $password);
    public function updateUser($id, $firstName, $lastName, $email, $password);
    public function deleteUser($id);

    public function getCustomers();
    public function getCustomerById($id);
    public function insertCustomer($customerId, $address, $phone);
    public function updateCustomer($id, $address, $phone);
    public function deleteCustomer($id);

    public function getBooks();
    public function getBookById($id);
    public function insertBook($title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function deleteBook($id);

    // Aggiungi altri metodi per le altre tabelle come ADMIN, AUTHOR, CATEGORY, ecc.
}
?>