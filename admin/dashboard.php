<?php

$total_umkm = mysqli_num_rows(
    mysqli_query($koneksi,"SELECT * FROM penjual")
);

?>

<div class="container-fluid">

    <div class="row">

        <div class="col-md-2 sidebar p-3">

            <?php include './template/sidebar.php'; ?>

        </div>

        <div class="col-md-10 p-4">

            <h2 class="dashboard-title">
                Dashboard Admin
            </h2>

            <p class="text-muted">
                Kelola seluruh UMKM dalam marketplace.
            </p>

            <div class="row g-3 mb-4">

                <div class="col-md-4">

                    <div class="card stat-card">

                        <div class="card-body">

                            <h6>Total UMKM</h6>

                            <div class="stat-number">
                                <?= $total_umkm ?>
                            </div>

                        </div>

                    </div>

                </div>

                <div class="col-md-4">

            </div>

            <div class="card">

                <div class="card-header">
                    Aktivitas Sistem
                </div>

                <div class="card-body">

                    <ul>

                        <li>Monitoring UMKM</li>

                        <li>Nonaktifkan Akun Penjual</li>

                    </ul>

                </div>

            </div>

        </div>

    </div>

</div>