<nav class="navbar sticky-top shadow navbar-expand-lg bg-body-tertiary">
    <div class="container-fluid">
        <a href="index.php" class="navbar-brand">Go Digital UMKM</a>
        <button type="button" class="navbar-toggler" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav justify-content-center text-center ms-auto">
                <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=produk">Products</a></li>
                <li class="nav-item"><a class="nav-link" href="index.php?page=form">Add Product</a></li>
                <?php if (!isset($_SESSION["login"])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="index.php?page=login">Login</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>
                <?php endif; ?>
                <!-- <li>
                <a href="keranjang.html">
                🛒
                </a>
                </li> -->
            </ul>
        </div>
    </div>
</nav>