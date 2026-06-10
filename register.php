<div class="login-container">
    <div class="login-box">
        <form class="login-form" action="proses-registrasi.php" method="POST">

            <h3>Register Akun</h3>

            <label>
                <input type="radio" name="role" value="pembeli" checked onclick="toggleForm()"> Pembeli
            </label>
            <label>
                <input type="radio" name="role" value="penjual" onclick="toggleForm()"> Penjual
            </label>

            <br><br>

            <div id="formPembeli">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" required><br>

                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" required><br>

                <label for="nomor">No. Telp</label><br>
                <input type="tel" name="phone" required><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" required><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" required><br>
            </div>

            <div id="formPenjual" style="display: none;">
                <label for="nama">Nama</label><br>
                <input type="text" name="nama" required><br>

                <label for="alamat">Alamat</label><br>
                <input type="text" name="alamat" required><br>

                <label for="nomor">No. Telp</label><br>
                <input type="tel" name="phone" required><br>

                <label for="toko">Nama Toko</label><br>
                <input type="text" name="toko" required><br>

                <label for="email">Email</label><br>
                <input type="email" name="email" required><br>

                <label for="password">Password</label><br>
                <input type="password" name="password" required><br>
            </div>

            <button class="btn btn-primary" type="submit">Daftar</button>
            <p style="font-size: 14px;">Sudah punya akun? <a href="index.php?page=login">Login</a></p>

        </form>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", toggleForm);

    function toggleForm() {
        const role = document.querySelector('input[name="role"]:checked').value;
        const formPenjual = document.getElementById('formPenjual');
        const formPembeli = document.getElementById('formPembeli');

        if(role === "penjual") {
            formPenjual.style.display = "block";
            formPembeli.style.display = "none";
            formPembeli.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });
            formPenjual.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        } else {
            formPenjual.style.display = "none";
            formPembeli.style.display = "block";
            formPenjual.querySelectorAll('input').forEach(input => {
                input.disabled = true;
            });
            formPembeli.querySelectorAll('input').forEach(input => {
                input.disabled = false;
            });
        }
    }
</script>