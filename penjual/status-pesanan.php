<?php
if (isset($_POST['update-status'])){
    $id = $_POST['id_pesanan'];
    $status = $_POST['status'];

    mysqli_query($koneksi, "UPDATE pesanan SET status='$status' WHERE id_pesanan='$id'");

    echo "
    <script>
        alert('Status pesanan berhasil diperbarui');
        location='index.php?page=penjual/status-pesanan';
    </script>";
}
?>

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
                        <th width="250">Update Status</th>
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
                            <td>
                                <form method="POST">
                                    <input type="hidden" name="id_pesanan" value="<?= $row['id_pesanan'] ?>">

                                    <div class="d-flex gap-2">
                                        <select name="status" class="form-select">
                                            <option <?= $row['status'] == 'diproses' ? 'selected' : '' ?>>Diproses</option>
                                            <option <?= $row['status'] == 'dikirim' ? 'selected' : '' ?>>Dikirim</option>
                                            <option <?= $row['status'] == 'selesai' ? 'selected' : '' ?>>Selesai</option>
                                        </select>

                                        <button type="submit" name="update-status" class="btn btn-primary">
                                            Save
                                        </button>
                                    </div>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
        </table>
    </div>
</div>