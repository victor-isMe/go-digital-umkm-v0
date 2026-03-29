<?php
$file = "products.json";

if (file_exists($file)) {
    $products = json_decode(file_get_contents($file), true);
} else {
    $products = [];
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Produk UMKM</title>
    <link rel="stylesheet" href="css/style.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">
    <style>
        .product-container {
            display: grid;
            grid-template-columns: 2fr;
            gap: 15px;
            padding: 15px;
            align-items: center;
        }

        .sidebar {
            width: 100%;
            background: white;
            padding: 15px;
            border-radius: 10px;
        }

        .sidebar button {
            width: 100%;
            margin: 5px 0;
            padding: 8px;
            border: none;
            background: #eee;
            cursor: pointer;
            border-radius: 6px;
        }

        .sidebar button:hover {
            background: #4CAF50;
            color: white;
        }

        .products {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .cart-float {
            position: fixed;
            bottom: 40px;
            right: 40px;
            background: #16a34a;
            padding: 12px 16px;
            border-radius: 50px;
            color: white;
            font-weight: bold;
            cursor: pointer;
            z-index: 9999;
        }

        .cart {
            width: 100%;
            max-height: 340px;
            position: static;
            overflow-y: scroll;
        }

        .cart-item {
            display: flex;
            justify-content: space-between;
            margin: 6px 0;
            font-size: 14px;
        }

        .toggleCart {
            display: flex;
            gap: 5px;
        }

        .hapus {
            background: red;
            color: white;
            border: none;
            padding: 4px 8px;
            border-radius: 5px;
            cursor: pointer;
        }

        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 15px;
            width: 320px;
            display: flex;
            flex-direction: column;
            gap: 10px;
        }

        .modal input {
            padding: 8px;
            border-radius: 6px;
            border: 1px solid #ccc;
        }

        @media (min-width: 768px) {
            .products {
                grid-template-columns: repeat(3, 1fr);
            }

            .sidebar {
                width: 100%;
            }
        }

        @media (min-width: 1200px) {
            .product-container {
                grid-template-columns: 220px 1fr 300px;
                align-items: start;
            }

            .sidebar {
                width: 100%;
                position: sticky;
                top: 100px;
            }

            .products {
                grid-template-columns: repeat(3, 1fr);
            }

            .cart {
                width: 100%;
                position: sticky;
                top: 100px;
            }

            .cart-float {
                display: none;
            }
        }
    </style>
</head>

<body>
    <div id="header"></div>
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

        <div class="products">
            <!-- MAKANAN -->
            <div class="card makanan"><img src="img/rendang.jpg">
                <h3>Rendang</h3>
                <p>Rp 85000</p><button onclick="tambahKeranjang('Rendang',85000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/cilok.jpg">
                <h3>Cilok</h3>
                <p>Rp 10000</p><button onclick="tambahKeranjang('Cilok',10000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/somay.jpg">
                <h3>Somay</h3>
                <p>Rp 15000</p><button onclick="tambahKeranjang('Somay',15000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/mieayam.jpg">
                <h3>Mie Ayam</h3>
                <p>Rp 12000</p><button onclick="tambahKeranjang('Mie Ayam',12000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/bakso.jpg">
                <h3>Bakso</h3>
                <p>Rp 15000</p><button onclick="tambahKeranjang('Bakso',15000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/dimsum.jpg">
                <h3>Dimsum</h3>
                <p>Rp 20000</p><button onclick="tambahKeranjang('Dimsum',20000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/pecel.jpg">
                <h3>Pecel</h3>
                <p>Rp 12000</p><button onclick="tambahKeranjang('Pecel',12000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/gudeg.jpg">
                <h3>Gudeg</h3>
                <p>Rp 18000</p><button onclick="tambahKeranjang('Gudeg',18000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/serabi.jpg">
                <h3>Serabi</h3>
                <p>Rp 8000</p><button onclick="tambahKeranjang('Serabi',8000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/sate.jpg">
                <h3>Sate</h3>
                <p>Rp 20000</p><button onclick="tambahKeranjang('Sate',20000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/ayambakar.jpg">
                <h3>Ayam Bakar</h3>
                <p>Rp 25000</p><button onclick="tambahKeranjang('Ayam Bakar',25000)" class="btn-primary">Beli</button>
            </div>


            <!-- MINUMAN -->

            <div class="card makanan"><img src="img/esjeruk.jpg">
                <h3>Es Jeruk</h3>
                <p>Rp 8000</p><button onclick="tambahKeranjang('Es Jeruk',8000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/eskopi.jpg">
                <h3>Es Kopi</h3>
                <p>Rp 12000</p><button onclick="tambahKeranjang('Es Kopi',12000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="https://picsum.photos/200">
                <h3>Kopi Hitam</h3>
                <p>Rp 7000</p><button onclick="tambahKeranjang('Kopi',7000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/esbuah.jpg">
                <h3>Es Buah</h3>
                <p>Rp 15000</p><button onclick="tambahKeranjang('Es Buah',15000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/esteler.jpg">
                <h3>Es Teler</h3>
                <p>Rp 17000</p><button onclick="tambahKeranjang('Es Teler',17000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/jus.jpg">
                <h3>Jus Buah</h3>
                <p>Rp 10000</p><button onclick="tambahKeranjang('Jus',10000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/dawet.jpg">
                <h3>Dawet</h3>
                <p>Rp 8000</p><button onclick="tambahKeranjang('Dawet',8000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/jamu.jpg">
                <h3>Jamu</h3>
                <p>Rp 10000</p><button onclick="tambahKeranjang('Jamu',10000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/esteh.jpg">
                <h3>Es Teh</h3>
                <p>Rp 5000</p><button onclick="tambahKeranjang('Es Teh',5000)" class="btn-primary">Beli</button>
            </div>

            <div class="card makanan"><img src="img/air.jpg">
                <h3>Air Putih</h3>
                <p>Rp 3000</p><button onclick="tambahKeranjang('Air',3000)" class="btn-primary">Beli</button>
            </div>


            <!-- KERAJINAN -->

            <div class="card kerajinan"><img src="img/tasanyaman.jpg">
                <h3>Tas Anyaman</h3>
                <p>Rp 75000</p><button onclick="tambahKeranjang('Tas Anyaman',75000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/caping.jpg">
                <h3>Caping</h3>
                <p>Rp 30000</p><button onclick="tambahKeranjang('Caping',30000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="https://picsum.photos/200">
                <h3>Pot Bunga</h3>
                <p>Rp 25000</p><button onclick="tambahKeranjang('Pot',25000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/keranjang.jpg">
                <h3>Keranjang</h3>
                <p>Rp 40000</p><button onclick="tambahKeranjang('Keranjang',40000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="https://picsum.photos/200">
                <h3>Bunga Hias</h3>
                <p>Rp 20000</p><button onclick="tambahKeranjang('Bunga',20000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/aksesoris.jpg">
                <h3>Aksesoris Handmade</h3>
                <p>Rp 15000</p><button onclick="tambahKeranjang('Aksesoris',15000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/tasrotan.jpg">
                <h3>Tas Rotan</h3>
                <p>Rp 90000</p><button onclick="tambahKeranjang('Tas Rotan',90000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/tasperca.jpg">
                <h3>Tas Kain Perca</h3>
                <p>Rp 50000</p><button onclick="tambahKeranjang('Tas Perca',50000)" class="btn-primary">Beli</button>
            </div>

            <div class="card kerajinan"><img src="img/dinding.jpg">
                <h3>Hiasan Dinding</h3>
                <p>Rp 35000</p><button onclick="tambahKeranjang('Hiasan',35000)" class="btn-primary">Beli</button>
            </div>


            <!-- FASHION -->

            <div class="card fashion"><img src="img/baju.jpg">
                <h3>Baju</h3>
                <p>Rp 100000</p><button onclick="tambahKeranjang('Baju',100000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/celana.jpg">
                <h3>Celana</h3>
                <p>Rp 90000</p><button onclick="tambahKeranjang('Celana',90000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/batik.jpg">
                <h3>Baju Batik</h3>
                <p>Rp 120000</p><button onclick="tambahKeranjang('Batik',120000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/rok.jpg">
                <h3>Rok Batik</h3>
                <p>Rp 110000</p><button onclick="tambahKeranjang('Rok',110000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/gamis.jpg">
                <h3>Gamis</h3>
                <p>Rp 150000</p><button onclick="tambahKeranjang('Gamis',150000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/pria.jpg">
                <h3>Baju Pria</h3>
                <p>Rp 130000</p><button onclick="tambahKeranjang('Baju Pria',130000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/oneset.jpg">
                <h3>One Set Anak</h3>
                <p>Rp 95000</p><button onclick="tambahKeranjang('One Set',95000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/keluarga.jpg">
                <h3>Seragam Keluarga</h3>
                <p>Rp 200000</p><button onclick="tambahKeranjang('Seragam',200000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/kerudung.jpg">
                <h3>Kerudung</h3>
                <p>Rp 50000</p><button onclick="tambahKeranjang('Kerudung',50000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/peci.jpg">
                <h3>Peci</h3>
                <p>Rp 30000</p><button onclick="tambahKeranjang('Peci',30000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/sarung.jpg">
                <h3>Sarung</h3>
                <p>Rp 60000</p><button onclick="tambahKeranjang('Sarung',60000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/sandalw.jpg">
                <h3>Sandal Wanita</h3>
                <p>Rp 70000</p><button onclick="tambahKeranjang('Sandal Wanita',70000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/sandalp.jpg">
                <h3>Sandal Pria</h3>
                <p>Rp 75000</p><button onclick="tambahKeranjang('Sandal Pria',75000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/sandala.jpg">
                <h3>Sandal Anak</h3>
                <p>Rp 50000</p><button onclick="tambahKeranjang('Sandal Anak',50000)" class="btn-primary">Beli</button>
            </div>

            <div class="card fashion"><img src="img/sepatu.jpg">
                <h3>Sepatu</h3>
                <p>Rp 180000</p><button onclick="tambahKeranjang('Sepatu',180000)" class="btn-primary">Beli</button>
            </div>

            <!-- rencana fitur load more -->
            <?php foreach ($products as $index => $product): ?>
                <div class="card <?= htmlspecialchars($product['category']); ?>" style="<?= $index >= 6 ? 'display: none;' : '' ?>">
                    <img src="<?= htmlspecialchars($product['image']); ?>" alt="Gambar Produk">
                    <h3><?= htmlspecialchars($product['name']); ?></h3>
                    <p>Rp <?= number_format($product['price'], 0, ',', '.'); ?></p>
                    <button onclick="tambahKeranjang('<?= htmlspecialchars($product['name'], ENT_QUOTES); ?>',<?= (int)$product['price']; ?>)" class="btn-primary">Beli</button>
                </div>
            <?php endforeach; ?>
            <button id="loadMoreBtn">Load More</button>
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
            <button onclick="bukaCheckout()" class="btn-primary">
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
            <button onclick="prosesCheckout()" class="btn-primary">
                Bayar
            </button>
            <button onclick="tutupCheckout()">
                Batal
            </button>
        </div>
    </div>

    <div id="footer"></div>
    <script>
        let cart = JSON.parse(localStorage.getItem("cart")) || [];
        updateCart();

        let currentItems = 6;
        const item = document.querySelectorAll('.card');
        const loadMoreBtn = document.getElementById('loadMoreBtn');

        loadMoreBtn.addEventListener('click', () => {
            for (let i = currentItems; i < currentItems + 3; i++) {
                if (items[i]) {
                    items[i].style.display = 'block';
                }
            }

            currentItems += 3;

            if (currentItems >= items.length) {
                loadMoreBtn.style.display = 'none';
            }
        });

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
                <button onclick="kurang(${index})">-</button>
                <button onclick="tambah(${index})">+</button>
                <button class="hapus"
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
                document.querySelectorAll(".card");
            produk.forEach(item => {
                if (
                    kategori == "all"
                ) {
                    item.style.display = "block";
                } else if (
                    item.classList.contains(
                        kategori
                    )
                ) {
                    item.style.display = "block";
                } else {
                    item.style.display = "none";
                }

            });

        }
        fetch("template/header.php")
            .then(res => res.text())
            .then(data => {
                document.getElementById(
                    "header"
                ).innerHTML = data;
            });
        fetch("template/footer.html")
            .then(res => res.text())
            .then(data => {
                document.getElementById(
                    "footer"
                ).innerHTML = data;
            });

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
</body>

</html>