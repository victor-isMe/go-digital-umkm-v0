<?php
// ── Guard: hanya user login ───────────────────────────────────
if (!isset($_SESSION['login'])) {
    header("Location: index.php?page=login");
    exit;
}

// ── Ambil data user dari SESSION — tidak pernah dari form ─────
$nama_user  = htmlspecialchars($_SESSION['nama']  ?? '', ENT_QUOTES, 'UTF-8');
$email_user = htmlspecialchars($_SESSION['email'] ?? '', ENT_QUOTES, 'UTF-8');

// ── Ambil flash message dari session ─────────────────────────
$success = $_SESSION['contact_success'] ?? null;
$error   = $_SESSION['contact_error']   ?? null;
$old     = $_SESSION['contact_old']     ?? [];
unset($_SESSION['contact_success'], $_SESSION['contact_error'], $_SESSION['contact_old']);
?>

<div class="ct-page">

    <a href="index.php?page=dashboard" class="btn-dashboard-ghost mb-3">
        <svg viewBox="0 0 24 24"><polyline points="15 18 9 12 15 6"/></svg>
        Kembali ke dashboard
    </a>

    <div class="ct-header">
        <div class="ct-title">Hubungi Admin</div>
        <div class="ct-subtitle">Kirim pertanyaan atau kendala kamu kepada tim kami</div>
    </div>

    <?php if ($success): ?>
        <div class="ct-alert ct-alert-success">
            <span class="ct-alert-icon">✅</span>
            <div><strong>Pesan berhasil dikirim!</strong><br><?= $success ?></div>
        </div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="ct-alert ct-alert-error">
            <span class="ct-alert-icon">⚠️</span>
            <div><strong>Terjadi kesalahan:</strong><br><?= $error ?></div>
        </div>
    <?php endif; ?>

    <div class="ct-card">
        <div class="ct-card-header">
            <span class="ct-card-title">Form kontak</span>
        </div>

        <!-- Action ke process_contact.php, bukan ke index.php -->
        <form method="POST" action="proses-contact.php" id="contactForm">
            <div class="ct-card-body">

                <div class="ct-grid">
                    <div class="ct-field">
                        <label class="ct-label">
                            Nama <span class="readonly-badge">Otomatis</span>
                        </label>
                        <!-- Tidak ada name= agar tidak ikut dikirim via POST -->
                        <input class="ct-input" type="text"
                               value="<?= $nama_user ?>" readonly tabindex="-1">
                    </div>
                    <div class="ct-field">
                        <label class="ct-label">
                            Email <span class="readonly-badge">Otomatis</span>
                        </label>
                        <!-- Sama, tidak ada name= -->
                        <input class="ct-input" type="email"
                               value="<?= $email_user ?>" readonly tabindex="-1">
                        <div class="ct-hint">Email dari akun yang terdaftar di sistem</div>
                    </div>
                </div>

                <div class="ct-field">
                    <label class="ct-label" for="subject">Subjek</label>
                    <input class="ct-input" type="text" name="subject" id="subject"
                           placeholder="Contoh: Pertanyaan tentang pesanan #1024"
                           value="<?= htmlspecialchars($old['subject'] ?? '') ?>"
                           maxlength="255" required oninput="checkForm()">
                </div>

                <div class="ct-field">
                    <label class="ct-label" for="message">Pesan</label>
                    <textarea class="ct-input ct-textarea" name="message" id="message"
                              placeholder="Tuliskan pesan kamu di sini..."
                              maxlength="1000" required
                              oninput="updateChar(this); checkForm()"
                    ><?= htmlspecialchars($old['message'] ?? '') ?></textarea>
                    <div class="ct-char" id="charCount">0 / 1000 karakter</div>
                </div>

            </div>

            <div class="ct-footer">
                <a href="index.php?page=dashboard" class="btn-back">
                    Kembali
                </a>
                <button type="submit" class="btn-send" id="btnSend" disabled>
                    Kirim pesan
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Update counter karakter pesan
function updateChar(el) {
    const len     = el.value.length;
    const counter = document.getElementById('charCount');
    counter.textContent = len + ' / 1000 karakter';
    counter.classList.toggle('over', len >= 950);
}

// Enable tombol kirim hanya kalau form valid
function checkForm() {
    const subject = document.getElementById('subject').value.trim();
    const message = document.getElementById('message').value.trim();
    const valid   = subject.length > 0 && message.length >= 1 && message.length <= 1000;
    document.getElementById('btnSend').disabled = !valid;
}

// Init char counter jika ada nilai lama (setelah error)
(function() {
    const msg = document.getElementById('message');
    if (msg.value) { updateChar(msg); checkForm(); }
})();
</script>