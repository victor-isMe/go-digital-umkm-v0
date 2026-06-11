    <h1 class="text-center mt-4 fs-2">
        Produk UMKM
    </h1>
    <div class="product-container">
        <div class="sidebar">
            <h3>Kategori</h3>
            <button onclick="filterProduk('all')">
                Semua
            </button>
            <button onclick="filterProduk('makanan')">
                Makanan/Minuman
            </button>
            <button onclick="filterProduk('kerajinan')">
                Kerajinan
            </button>
            <button onclick="filterProduk('fashion')">
                Fashion
            </button>
        </div>

        <div class="row row-cols-2 row-cols-md-3 g-3">
            <?php while ($row = mysqli_fetch_assoc($result)): ?>

                <div class="col <?= $row['kategori']; ?>">
                    <div class="card bg-white p-3 rounded-4 shadow-sm border-0 card-hover">
                        <img class="w-100 rounded-3 mb-2" src="<?= $row['foto']; ?>" alt="<?= $row['nama']; ?>">

                        <h6 class="mt-2 mb-2"><?= $row['nama']; ?></h6>
                        <p class="text-success fw-semibold mb-0">Rp <?= number_format($row['harga'], 0, ',','.'); ?></p>

                        <div class="row">
                            <div class="col-9">
                                <button class="btn btn-primary">Beli</button>
                            </div>
                            <div class="col-3">
                                <a href="index.php?page=produk&add_cart=<?= $row['id_produk'] ?>" class="btn btn-success">🛒</a>
                            </div>
                        </div>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>

    </div>

    <script>

        function filterProduk(kategori) {
            let produk =
                document.querySelectorAll(".col");
            produk.forEach(item => {
                if (kategori == "all") {
                    item.style.display = "block";
                } else if (item.classList.contains(kategori)) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }

            });

        }

        function toggleMenu() {
            const menu = document.getElementById("navMenu");
            menu.classList.toggle("active");
        }

    </script>