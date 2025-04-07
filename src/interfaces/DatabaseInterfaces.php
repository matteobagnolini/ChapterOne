<?php
interface CustomerManager {
    public function getCustomers();
    public function getCustomerById($id);
    public function insertCustomer($firstName, $lastName, $email, $password, $address, $phone);
    public function updateCustomer($id, $firstName, $lastName, $email, $password, $address, $phone);
    public function deleteCustomer($id);
}

interface BookManager {
    public function getBooks();
    public function getBookById($id);
    public function insertBook($title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function updateBook($id, $title, $description, $price, $cover, $categoryId, $publisherId, $authorId);
    public function deleteBook($id);
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
?>