<div class="login-container">

    <div class="login-box">

        <h2>Selamat Datang</h2>
        <p>Masuk ke akun anda</p>

        <form class="login-form" method="POST" action="proses-login.php">
            <?php if (isset($_GET['error'])): ?>
                <div id="toast" class="alert alert-danger fixed-bottom text-center">
                    Login gagal!
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded",
                        function() {
                            const toastEl = document.getElementById('toast');
                            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                            toast.show();
                        }
                    );
                </script>
            <?php endif; ?>   

            <!-- UJI COBA -->
            <?php if (isset($_GET['expired'])): ?>
                <div id="toast" class="alert alert-warning fixed-bottom text-center">
                    Session berakhir, silahkan login ulang!
                </div>

                <script>
                    document.addEventListener("DOMContentLoaded",
                        function() {
                            const toastEl = document.getElementById('toast');
                            const toast = new bootstrap.Toast(toastEl, { delay: 3000 });
                            toast.show();
                        }
                    );
                </script>
            <?php endif; ?>   
            <!-- UJI COBA -->

            <label>Email</label>
            <input name="email" type="email" placeholder="Email" required>

            <label>Password</label>
            <input name="password" type="password" required>

            <button type="submit" class="btn btn-primary">
                Login
            </button>
        </form>
        
    </div>

</div>