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
                <label for="nama">Nama</label>
                <input type="text" name="nama" required>

                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" required>

                <label for="nomor">No. Telp</label>
                <input type="tel" name="phone" required>

                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <div id="formPenjual" style="display: none;">
                <label for="nama">Nama</label>
                <input type="text" name="nama" required>

                <label for="alamat">Alamat</label>
                <input type="text" name="alamat" required>

                <label for="nomor">No. Telp</label>
                <input type="tel" name="phone" required>

                <label for="toko">Nama Toko</label>
                <input type="text" name="toko" required>

                <label for="email">Email</label>
                <input type="email" name="email" required>

                <label for="password">Password</label>
                <input type="password" name="password" required>
            </div>

            <button class="btn btn-primary" type="submit">Daftar</button>

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