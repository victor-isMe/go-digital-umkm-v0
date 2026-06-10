<?php
if(isset($_GET['nonaktifkan'])) {
    $id = $_GET['nonaktifkan'];

    mysqli_query($koneksi, "UPDATE penjual
                    SET status='nonaktif'
                    WHERE id_penjual='$id'");

    echo "<script>location='index.php?page=admin/nonaktifkan-umkm'</script>";
}

if (isset($_GET['aktifkan'])) {
    $id = $_GET['aktifkan'];

    mysqli_query($koneksi, "UPDATE penjual
                    SET status='aktif'
                    WHERE id_penjual='$id'");
    
    echo "<script>location='index.php?page=admin/nonaktifkan-umkm'</script>";
}

$query = mysqli_query($koneksi, "SELECT * FROM penjual");
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-warning">
            <h4 class="mb-0">
                Kelola Status Akun UMKM
            </h4>
        </div>

        <div class="card-body">

            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Nama Toko</th>
                        <th>Nama Penjual</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th width="180">
                            Aksi
                        </th>
                    </tr>
                </thead>

                <tbody>

                    <?php
                    while($data = mysqli_fetch_assoc($query)):
                    ?>

                    <tr>
                        <td><?= $data['id_penjual'] ?></td>
                        <td><?= $data['nama_toko'] ?></td>
                        <td><?= $data['nama_penjual'] ?></td>
                        <td><?= $data['email_penjual'] ?></td>

                        <td>
                            <?php if($data['status'] == 'aktif'): ?>
                                <span class="badge bg-success">
                                    Aktif
                                </span>
                            <?php else: ?>
                                <span class="badge bg-danger">
                                    Nonaktif
                                </span>
                            <?php endif ?>
                        </td>

                        <td>

                            <?php if($data['status'] == 'aktif'): ?>

                                <a href="
                                index.php?page=admin/nonaktifkan-umkm&
                                nonaktifkan=<?= $data['id_penjual'] ?>"
                                class="btn btn-danger btn-sm">
                                    Nonaktifkan
                                </a>

                            <?php else: ?>

                                <a href="
                                index.php?page=admin/nonaktifkan-umkm&
                                aktifkan=<?= $data['id_penjual'] ?>"
                                class="btn btn-success btn-sm">
                                    Aktifkan
                                </a>

                            <?php endif ?>

                        </td>
                    </tr>

                    <?php endwhile ?>

                </tbody>
            </table>

        </div>
    </div>
</div>