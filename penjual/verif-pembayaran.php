<?php
$id_penjual = $_SESSION['id'];

$query = mysqli_query($koneksi, "SELECT * FROM pesanan WHERE id_penjual='$id_penjual' ORDER BY tanggal DESC");
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
                        <th>Bukti Bayar</th>
                        <th>Verifikasi</th>
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
                                    $bayar = $row['metode_bayar'];
                                    $bukti_bayar = htmlspecialchars($row['bukti_bayar']);
                                ?>

                                <?php if ($bayar !== 'COD'): ?>
                                    <img class="img-thumbnail preview-img" src="<?= $bukti_bayar ?>" data-bs-toggle='modal' data-bs-target='#imgModal' width="50">
                                <?php else: ?>
                                    <span>-</span>
                                <?php endif; ?>
                                    <div class="modal fade" id="imgModal" tabindex="-1">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content bg-transparent border-0">
                                                <button type="button" class="btn-close btn-close-white ms-auto m-2" data-bs-dismiss='modal'></button>
                                                <img id="modalImage" class="w-100 rounded">
                                            </div>
                                        </div>
                                    </div>                            
                            </td>
                            <td>
                                <button class="btn btn-primary btn-sm">Verifikasi</button>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
        </table>
    </div>
</div>

<script>
    const previewImage = document.querySelectorAll('.preview-img');
    const modalImage = document.getElementById('modalImage');

    previewImage.forEach(img => {
        img.addEventListener('click', function () {
            modalImage.src = this.src;
        });
    });
</script>