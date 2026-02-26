<?php
require_once "config/Csrf.php";

function validatePassword($password) {
    // Minimum uzunluk
    if (strlen($password) < 12) {
        return "Şifre en az 12 karakter olmalıdır.";
    }

    // Büyük harf
    if (!preg_match('/[A-Z]/', $password)) {
        return "Şifre en az 1 büyük harf içermelidir.";
    }

    // Küçük harf
    if (!preg_match('/[a-z]/', $password)) {
        return "Şifre en az 1 küçük harf içermelidir.";
    }

    // Rakam
    if (!preg_match('/[0-9]/', $password)) {
        return "Şifre en az 1 rakam içermelidir.";
    }

    // Özel karakter
    if (!preg_match('/[\W]/', $password)) {
        return "Şifre en az 1 özel karakter içermelidir.";
    }

    // Yasaklı basit şifreler
    $weakPasswords = [
        '123456', '12345678', 'password', 'Password123',
        'qwerty', 'admin123', '123456789'
    ];

    if (in_array(strtolower($password), array_map('strtolower', $weakPasswords))) {
        return "Bu şifre çok zayıf. Lütfen daha güçlü bir şifre seçin.";
    }

    return true;
}

$db = new Database();

// CSRF kontrolü
if (!Csrf::verify($_POST['csrf_token'] ?? null)) {
    $_SESSION['error'] = "Geçersiz CSRF tokeni!";
    header("Location: " . route('kullanici_listesi'));
    exit;
}

$action = $_POST['action'] ?? '';

/* ================= KULLANICI EKLE ================= */
if ($action === 'add_user') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['password_confirm'] ?? '';

    if (strlen($username) < 3) {
        $_SESSION['error'] = "Kullanıcı adı en az 3 karakter olmalı";
        header("Location: " . route('kullanici_listesi'));
        exit;
    }

    if ($password !== $confirm) {
        $_SESSION['error'] = "Şifreler uyuşmuyor";
        header("Location: " . route('kullanici_listesi'));
        exit;
    }
    
    // ✅ Güçlü şifre kontrolü
    $check = validatePassword($password);
    if ($check !== true) {
        $_SESSION['error'] = $check;
        header("Location: " . route('kullanici_listesi'));
        exit;
    }

    // ❗ Kullanıcı adı var mı kontrol
    $existing = $db->fetch("SELECT id FROM users WHERE username=?", "s", [$username]);
    if ($existing) {
        $_SESSION['error'] = "Bu kullanıcı adı zaten alınmış";
        header("Location: " . route('kullanici_listesi'));
        exit;
    }

    $hash = password_hash($password, PASSWORD_DEFAULT);

    $db->execute(
        "INSERT INTO users (username, password) VALUES (?, ?)",
        "ss",
        [$username, $hash]
    );

    $_SESSION['success'] = "Kullanıcı başarıyla eklendi";
    header("Location: " . route('kullanici_listesi'));
    exit;
}


/* ================= SİL ================= */
$user_id = (int)($_POST['user_id'] ?? 0);
if (($action === 'delete' || $action === 'change_password') && $user_id <= 0) {
    $_SESSION['error'] = "Geçersiz kullanıcı";
    header("Location: " . route('kullanici_listesi'));
    exit;
}

if ($action === 'delete') {
    $db->execute("DELETE FROM users WHERE id=?", "i", [$user_id]);
    $_SESSION['success'] = "Kullanıcı silindi";
    header("Location: " . route('kullanici_listesi'));
    exit;
}

/* ================= ŞİFRE DEĞİŞTİR ================= */
if ($action === 'change_password') {
    $newPass = $_POST['new_password'] ?? '';
    $confirm = $_POST['new_password_confirm'] ?? '';

    if ($newPass !== $confirm) {
        $_SESSION['error'] = "Şifreler uyuşmuyor";
        header("Location: " . route('kullanici_listesi'));
        exit;
    }
    
    // ✅ Güçlü şifre kontrolü
    $check = validatePassword($newPass);
    if ($check !== true) {
        $_SESSION['error'] = $check;
        header("Location: " . route('kullanici_listesi'));
        exit;
    }

    $hash = password_hash($newPass, PASSWORD_DEFAULT);

    $db->execute(
        "UPDATE users SET password=? WHERE id=?",
        "si",
        [$hash, $user_id]
    );

    $_SESSION['success'] = "Şifre başarıyla değiştirildi";
    header("Location: " . route('kullanici_listesi'));
    exit;
}

// Eğer action bilinmiyorsa
$_SESSION['error'] = "Geçersiz işlem";
header("Location: " . route('kullanici_listesi'));
exit;
