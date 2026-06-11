<?php
$id_penjual = $_SESSION['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual='$id_penjual' ORDER BY id_pesanan DESC");
?>

<div class="container mt-4">
    <div class="card-header bg-success text-white">
        <h4 class="mb-0">
            Daftar Pesanan
        </h4>
    </div>

    <div class="card-body">
        <table class="table table-bordered">
            <thead>
                    <tr>
                        <th>ID</th>
                        <th>Pemesan</th>
                        <th>Total</th>
                        <th>Metode Bayar</th>
                        <th>Status</th>
                    </tr>
                </thead>

                <tbody>
                    <?php
                    while ($row = mysqli_fetch_assoc($query)):
                    ?>
                        <tr>
                            <td><?= $row['id_pesanan'] ?></td>
                            <td><?= $row['nama_pemesan'] ?></td>
                            <td>Rp <?= number_format($row['total_harga']) ?></td>
                            <td><?= $row['metode_bayar'] ?></td>
                            <td>
                                <?php
                                $badge = "secondary";

                                if($row['status'] == 'diproses')
                                    $badge = "info";

                                if($row['status'] == 'dikirim')
                                    $badge = "primary";

                                if($row['status'] == 'selesai')
                                    $badge = "success";
                                ?>

                                <span class="badge bg-<?= $badge ?>">
                                    <?= $row['status'] ?>
                                </span>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
        </table>
    </div>
</div>