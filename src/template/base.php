<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <title><?php echo isset($templateParams["titolo"]) ? htmlspecialchars($templateParams["titolo"]) : "ChapterOne Shop"; ?></title>
</head>

<body  class="d-flex flex-column min-vh-100">
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">ChapterOne Shop</a>
                    <div class="d-flex align-items-center order-lg-2">
                    
                    <?php  ?>
                    <?php if(isset($_SESSION['username'])): ?>
                        <a class="nav-link me-2" href="notifiche.php" aria-label="Notifiche"><i class="bi bi-bell me-1"></i><span class="d-none d-md-inline"> Notifiche</span></a>
                    <?php endif; ?>

                    <?php ?>
                    <?php if(isset($_SESSION['username']) && (!isset($_SESSION['admin']) || !$_SESSION['admin'])): ?>
                        <a class="nav-link me-2" href="cart.php" aria-label="Carrello"><i class="bi bi-cart me-1"></i><span class="d-none d-md-inline"> Carrello</span></a>
                    <?php endif; ?>
                    
                    <?php ?>
                    <?php if(!isset($_SESSION['username'])): ?>
                        <a class="nav-link me-2" href="login.php" aria-label="Login"><i class="bi bi-box-arrow-in-right me-1"></i><span class="d-none d-md-inline"> Login</span></a>
                    <?php elseif(isset($_SESSION['admin']) && $_SESSION['admin']): ?>
                        <a class="nav-link me-2" href="accountadmin.php" aria-label="Pannello Admin"><i class="bi bi-shield-lock me-1"></i><span class="d-none d-md-inline"> Admin</span></a>
                    <?php else: ?>
                        <a class="nav-link me-2" href="account.php" aria-label="Account Utente"><i class="bi bi-person me-1"></i><span class="d-none d-md-inline"> Account</span></a>
                    <?php endif; ?>

                    <form class="d-flex" method="GET" action="./utils/find.php">
                        <input class="form-control me-2" name="query" type="search" placeholder="Cerca" aria-label="Search">
                        <button class="btn" type="submit"><i class="bi bi-search"></i></button>
                    </form>
                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav"
                        aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>
                </div>
                <div class="collapse navbar-collapse order-lg-1" id="navbarNav">
                    <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                        <li class="nav-item">
                            <a class="nav-link" href="index.php">Home</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="catalogo.php">Catalogo</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="categories.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categorie
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <?php foreach ($templateParams["categorie"] as $category): ?>
                                    <?php echo '<li><a class="dropdown-item" href="categoria.php?id=' . $category['Id'] . '">' . $category['Name'] . '</a></li>'; ?>
                                <?php endforeach; ?>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="aboutus.php">About Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>
    </header>
    <main class="container py-4 flex-grow-1">

    <?php
    if(isset($templateParams["nome"])){
        require($templateParams["nome"]);
    }
    ?>
    
    </main>
    <footer class="bg-light text-white text-center py3 mt-auto">
        <div class="text-center p-3 bg-dark text-white">
            © 2023 ChapterOne Shop. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-TcB5vHnxnKlW1qS6WX1kzeF7L/ZFZ2pT3zYWE7GvJm7XwnR2s4vqJ2UmBa4/qnHp" crossorigin="anonymous"></script>
</body>
</html>