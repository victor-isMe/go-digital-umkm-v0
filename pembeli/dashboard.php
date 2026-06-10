<?php

$id_pembeli = $_SESSION['id'];

?>

<div class="container-fluid">

<div class="row">

<div class="col-md-2 p-0">
    <?php include './template/sidebar.php'; ?>
</div>

<div class="col-md-10 p-4">

    <h2>Dashboard Pembeli</h2>

    <p class="text-muted">
        Selamat datang kembali.
    </p>

    <div class="row g-3 mb-4">

        <div class="col-md-4">

            <div class="card stat-card">

                <div class="card-body">

                    <h6>Produk Tersedia</h6>

                    <div class="stat-number">

                        <?= mysqli_num_rows(
                            mysqli_query(
                                $koneksi,
                                "SELECT * FROM produk"
                            )
                        ) ?>

                    </div>

                </div>

            </div>

        </div>

    </div>

    <div class="card">

        <div class="card-header">

            Mulai Belanja

        </div>

        <div class="card-body">

            <a href="index.php?page=produk"
               class="btn btn-success">

                Jelajahi Produk

            </a>

        </div>

    </div>

</div>

</div>

</div>