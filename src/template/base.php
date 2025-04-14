<!DOCTYPE html>
<html lang="it">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link rel="stylesheet" type="text/css" href="./css/style.css" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;700&display=swap" rel="stylesheet">
    <title><?php echo $templateParams["titolo"]; ?></title>


    <style>
        /* Sticky footer styles */
        html, body {
            height: 100%;
        }
        body {
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1;
        }
    </style>
</head>

<body>
    <header>
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">ChapterOne Shop</a>
                <div class="d-flex align-items-center order-lg-2">
                    <a class="nav-link me-2" href="cart.php"><i class="bi bi-cart"></i>Cart</a>
                    <a class="nav-link me-2" href="account.php"><i class="bi bi-person"></i>Account</a>
                    <form class="d-flex">
                        <input class="form-control me-2" type="search" placeholder="Cerca" aria-label="Search">
                        <button class="btn" type="submit">Search</button>
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
                            <a class="nav-link" href="catalog.php">Catalog</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="releases.php">New Releases</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="categories.php" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                Categories
                            </a>
                            <ul class="dropdown-menu" aria-labelledby="navbarDropdown">
                                <li><a class="dropdown-item" href="#">Categoria 1</a></li>
                                <li><a class="dropdown-item" href="#">Categoria 2</a></li>
                                <li><a class="dropdown-item" href="#">Categoria 3</a></li>
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
    <main>

    <?php
    if(isset($templateParams["nome"])){
        require($templateParams["nome"]);
    }
    ?>
    
    </main>
    <footer class="bg-light text-center text-lg-start">
        <div class="text-center p-3 bg-dark text-white">
            © 2023 ChapterOne Shop. All rights reserved.
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-TcB5vHnxnKlW1qS6WX1kzeF7L/ZFZ2pT3zYWE7GvJm7XwnR2s4vqJ2UmBa4/qnHp" crossorigin="anonymous"></script>
</body>
</html>