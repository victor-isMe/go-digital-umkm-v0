<?php
$query = mysqli_query($koneksi, "SELECT pj.*, COUNT(p.id_produk) AS jumlah_produk
                        FROM penjual pj
                        LEFT JOIN produk p ON pj.id_penjual = p.id_penjual
                        GROUP BY pj.id_penjual");
?>

<div class="container mt-4">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Daftar Akun UMKM</h4>
        </div>

        <div class="card-body">
            <table class="table table-bordered table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Nama Toko</th>
                        <th>Nama Pemilik</th>
                        <th>Email</th>
                        <th>Jumlah Produk</th>
                        <th>Status</th>
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
                        <td><?= $data['jumlah_produk'] ?></td>
                        <td>
                            <?php if($data['status'] == 'aktif'): ?>
                                <span class="badge bg-success">Aktif</span>
                            <?php else: ?>
                                <span class="badge bg-danger">Nonaktif</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>