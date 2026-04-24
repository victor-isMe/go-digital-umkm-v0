<?php if (isset($error)): ?>
    <div class="alert alert-danger">
        <?= $error; ?>
    </div>
<?php endif; ?>
<div class="login-container">
    <div class="login-box">
        <h2>Selamat Datang</h2>
        <p>Masuk ke akun anda</p>
        <form class="login-form" method="POST">
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