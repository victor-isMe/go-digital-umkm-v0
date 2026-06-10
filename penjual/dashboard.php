<?php

$id_penjual = $_SESSION['id'];

$total_produk = mysqli_num_rows(
    mysqli_query(
        $koneksi,
        "SELECT * FROM produk"
    )
);

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">
    <?php include './template/sidebar.php'; ?>
</div>

<div class="col-md-10 p-4">

    <h2>Dashboard Penjual</h2>

    <p class="text-muted">
        Selamat datang,
        <?= $_SESSION['toko'] ?>
    </p>

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card stat-card">

                <div class="card-body">

                    <h6>Total Produk</h6>

                    <div class="stat-number">

                        <?= $total_produk ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card quick-action">

        <div class="card-header">

            Aksi Cepat

        </div>

        <div class="card-body">

            <a href="index.php?page=form"
               class="btn btn-primary">

                Tambah Produk

            </a>

            <a href="index.php?page=products-admin"
               class="btn btn-outline-primary">

                Kelola Produk

            </a>

        </div>

    </div>

</div>

</div>

</div>