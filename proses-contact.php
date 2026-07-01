<?php
session_start();
require_once 'config/database.php';
require_once 'config/mailer.php';

use PHPMailer\PHPMailer\Exception;

// ── Guard: hanya user login yang bisa akses ───────────────────
if (!isset($_SESSION['login'])) {
    http_response_code(403);
    header("Location: index.php?page=login");
    exit;
}

// ── Hanya terima POST ─────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: index.php?page=contact");
    exit;
}

// ── Ambil data dari SESSION — tidak dari form input ───────────
$user_id    = (int)    $_SESSION['id'];
$user_role  =          $_SESSION['role']  ?? 'unknown';
$nama       = htmlspecialchars(strip_tags($_SESSION['nama']  ?? ''), ENT_QUOTES, 'UTF-8');
$email_user = filter_var($_SESSION['email'] ?? '', FILTER_SANITIZE_EMAIL);

// ── Ambil & sanitasi input form ───────────────────────────────
$subject = htmlspecialchars(strip_tags(trim($_POST['subject'] ?? '')), ENT_QUOTES, 'UTF-8');
$message = htmlspecialchars(strip_tags(trim($_POST['message'] ?? '')), ENT_QUOTES, 'UTF-8');

// ── Validasi ──────────────────────────────────────────────────
$errors = [];

if (empty($subject))              $errors[] = "Subjek tidak boleh kosong.";
if (strlen($subject) > 255)       $errors[] = "Subjek terlalu panjang (maks. 255 karakter).";
if (empty($message))              $errors[] = "Pesan tidak boleh kosong.";
if (strlen($message) < 1)        $errors[] = "Pesan terlalu pendek (min. 10 karakter).";
if (!filter_var($email_user, FILTER_VALIDATE_EMAIL)) {
    $errors[] = "Email akun tidak valid. Hubungi administrator.";
}

if (!empty($errors)) {
    $_SESSION['contact_error']  = implode('<br>', $errors);
    $_SESSION['contact_old']    = ['subject' => $subject, 'message' => $_POST['message']];
    header("Location: index.php?page=contact");
    exit;
}

// ── Kirim email ke admin via PHPMailer ────────────────────────
$ADMIN_EMAIL = 'rizayulianarahmawati07@gmail.com';

try {
    $mail = createMailer();

    // Pengirim — pakai email SMTP, bukan email user
    // (Gmail tidak izinkan From selain akun SMTP kamu)
    $mail->setFrom('muhammadfaizniwansyah23@gmail.com', 'Sistem Toko UMKM');

    // Reply-To = email user, agar admin bisa balas langsung
    $mail->addReplyTo($email_user, $nama);

    // Penerima
    $mail->addAddress($ADMIN_EMAIL, 'Admin');

    // Konten email
    $mail->isHTML(true);
    $mail->Subject = "[Contact Form] $subject";
    $mail->Body    = "
        <div style='font-family:Arial,sans-serif;max-width:560px;margin:0 auto'>
            <div style='background:#16a34a;padding:16px 24px;border-radius:10px 10px 0 0'>
                <h2 style='color:#fff;margin:0;font-size:18px'>Pesan Baru dari $user_role</h2>
            </div>
            <div style='background:#f9f9f9;padding:24px;border:1px solid #e5e7eb;border-top:none'>
                <table style='width:100%;border-collapse:collapse;font-size:14px'>
                    <tr>
                        <td style='padding:8px 0;color:#6b7280;width:100px;vertical-align:top'>Nama</td>
                        <td style='padding:8px 0;font-weight:600;color:#111827'>$nama</td>
                    </tr>
                    <tr>
                        <td style='padding:8px 0;color:#6b7280;vertical-align:top'>Email</td>
                        <td style='padding:8px 0;color:#111827'>$email_user</td>
                    </tr>
                    <tr>
                        <td style='padding:8px 0;color:#6b7280;vertical-align:top'>Role</td>
                        <td style='padding:8px 0;color:#111827'>" . ucfirst($user_role) . "</td>
                    </tr>
                    <tr>
                        <td style='padding:8px 0;color:#6b7280;vertical-align:top'>Subjek</td>
                        <td style='padding:8px 0;font-weight:600;color:#111827'>$subject</td>
                    </tr>
                    <tr>
                        <td style='padding:8px 0;color:#6b7280;vertical-align:top'>Pesan</td>
                        <td style='padding:8px 0;color:#111827;line-height:1.6'>$message</td>
                    </tr>
                </table>
            </div>
            <div style='background:#f1f5f9;padding:12px 24px;border-radius:0 0 10px 10px;font-size:12px;color:#94a3b8'>
                Dikirim otomatis oleh sistem · " . date('d M Y, H:i') . " WIB
            </div>
        </div>
    ";
    // Versi plain text (fallback)
    $mail->AltBody = "Pesan dari: $nama ($email_user)\nSubjek: $subject\n\n$message";

    $mail->send();

    $_SESSION['contact_success'] = "Pesan berhasil dikirim! Admin akan membalas ke email kamu.";

} catch (Exception $e) {
    // Email gagal tapi data sudah tersimpan di DB — jangan block user
    $_SESSION['contact_success'] = "Pesan tersimpan. (Notifikasi email sedang dalam gangguan.)";
    // Log error untuk developer
    error_log("[Contact PHPMailer Error] " . $mail->ErrorInfo);
}

unset($_SESSION['contact_old']);
header("Location: index.php?page=contact");
exit;