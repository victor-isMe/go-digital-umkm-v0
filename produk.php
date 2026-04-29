    <h1 class="title">
        Produk UMKM Kami
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

                        <button onclick="tambahKeranjang('<?= $row['nama']; ?>', <?= $row['harga']; ?>)" class="btn btn-primary">
                            Beli
                        </button>
                    </div>
                </div>

            <?php endwhile; ?>
        </div>

        <div class="cart-float">
            🛒 (<span id="cart-count">0</span>)
        </div>

        <div id="cart" class="cart">
            <h3>Keranjang</h3>
            <div id="cart-items"></div>
            <p>
                Total :
                <b>
                    Rp <span id="total">0</span>
                </b>
            </p>
            <button onclick="bukaCheckout()" class="btn btn-primary btn-lg">
                Checkout
            </button>
        </div>
    </div>

    <div id="checkoutModal" class="modal">
        <div class="modal-content">
            <h2>Checkout</h2>
            <p>
                Total :
                <b>
                    Rp <span id="totalCheckout"></span>
                </b>
            </p>
            <input id="nama" placeholder="Nama">
            <input id="alamat" placeholder="Alamat">
            <label>
                <input type="radio" name="pay" value="Transfer Bank">
                Transfer Bank
            </label>
            <label>
                <input type="radio" name="pay" value="E-Wallet">
                E Wallet
            </label>
            <label>
                <input type="radio" name="pay" value="COD">
                COD
            </label>
            <button onclick="prosesCheckout()" class="btn btn-primary">
                Bayar
            </button>
            <button onclick="tutupCheckout()" class="btn btn-danger">
                Batal
            </button>
        </div>
    </div>

    <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        updateCart();

        function tambahKeranjang(nama, harga) {
            let found = false;

            cart.forEach(item => {
                if (item.nama === nama) {
                    item.qty += 1;
                    found = true;
                }
            });

            if (!found) {
                cart.push({
                    nama: nama,
                    harga: harga,
                    qty: 1
                });
            }

            simpan();
            updateCart();
        }

        function updateCart() {
            let items = document.getElementById("cart-items");
            items.innerHTML = "";

            let total = 0;
            let jumlahItem = 0;

            cart.forEach((item, index) => {
                total += item.harga * item.qty;
                jumlahItem += item.qty;

                items.innerHTML +=
                    `<div class="cart-item">
            ${item.nama} (x${item.qty}) Rp ${item.harga * item.qty}
            <div class="toggleCart">
                <button onclick="kurang(${index})" class="btn btn-outline-dark btn-sm">-</button>
                <button onclick="tambah(${index})" class="btn btn-outline-dark btn-sm">+</button>
                <button class="btn btn-danger btn-sm"
                onclick="hapus(${index})">
                X
                </button>
            </div>
        </div>`;
            });
            document.getElementById("total").innerHTML = total;

            document.getElementById("cart-count").innerHTML = jumlahItem;
        }

        function kurang(index) {
            if (cart[index].qty > 1) {
                cart[index].qty -= 1;
            } else {
                cart.splice(index, 1);
            }

            simpan();
            updateCart();
        }

        function tambah(index) {
            cart[index].qty += 1;

            simpan();
            updateCart();
        }

        function hapus(index) {
            cart.splice(index, 1);
            simpan();
            updateCart();
        }

        function simpan() {
            localStorage.setItem(
                "cart",
                JSON.stringify(cart)
            );

        }

        function bukaCheckout() {
            if (cart.length == 0) {
                alert("Keranjang kosong");
                return;
            }
            let total = 0;
            cart.forEach(item => {
                total += item.harga;
            });
            document.getElementById(
                "totalCheckout"
            ).innerHTML = total;
            document.getElementById(
                "checkoutModal"
            ).style.display = "flex";
        }

        function prosesCheckout() {
            let nama =
                document.getElementById("nama").value;
            let alamat =
                document.getElementById("alamat").value;
            let payment =
                document.querySelector(
                    "input[name='pay']:checked"
                );
            if (
                nama == "" ||
                alamat == "" ||
                payment == null
            ) {
                alert("Lengkapi data");
                return;
            }
            let total = 0;
            let detail = "";
            cart.forEach(item => {
                total += item.harga * item.qty;
                detail += item.nama + " x" + item.qty + " Rp" + (item.harga * item.qty) + "\n";
            });
            let order =
                "INV" +
                Math.floor(
                    Math.random() * 100000
                );
            alert(
                "INVOICE\n\n" +
                "No :" + order +
                "\nNama :" + nama +
                "\nPembayaran :" +
                payment.value +
                "\n\nProduk:\n" +
                detail +
                "\nTotal : Rp " +
                total +
                "\n\nPesanan berhasil"
            );
            cart = [];
            simpan();
            updateCart();
            tutupCheckout();
        }

        function tutupCheckout() {
            document.getElementById(
                "checkoutModal"
            ).style.display = "none";
        }

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
        // fetch("template/header.php")
        //     .then(res => res.text())
        //     .then(data => {
        //         document.getElementById(
        //             "header"
        //         ).innerHTML = data;
        //     });
        // fetch("template/footer.html")
        //     .then(res => res.text())
        //     .then(data => {
        //         document.getElementById(
        //             "footer"
        //         ).innerHTML = data;
        //     });

        function toggleMenu() {
            const menu = document.getElementById("navMenu");
            menu.classList.toggle("active");
        }

        document.querySelector(".cart-float").onclick = () => {
            document.getElementById("cart").scrollIntoView({
                behavior: "smooth"
            });
        };
    </script>