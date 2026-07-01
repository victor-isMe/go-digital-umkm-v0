<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

require_once __DIR__ . '/../vendor/autoload.php';

/**
 * Membuat dan mengembalikan instance PHPMailer
 * yang sudah dikonfigurasi dengan SMTP Gmail.
 *
 * Untuk testing lokal, ganti host ke Mailtrap
 * (lihat komentar di bawah).
 */
function createMailer(): PHPMailer {
    $mail = new PHPMailer(true);

    $mail->isSMTP();
    $mail->CharSet    = 'UTF-8';
    $mail->SMTPDebug  = SMTP::DEBUG_OFF;  // Ganti ke DEBUG_SERVER saat dev

    // ── Konfigurasi SMTP ─────────────────────────────────────
    // PILIHAN A: Gmail (production)
    $mail->Host       = 'smtp.gmail.com';
    $mail->SMTPAuth   = true;
    $mail->Username   = 'muhammadfaizniwansyah23@gmail.com';
    $mail->Password   = 'mjsv zxvl jefq hjix';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port       = 587;

    return $mail;
}