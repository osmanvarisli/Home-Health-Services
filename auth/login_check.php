<?php
require_once "config/Csrf.php";

/* =========================
   SADECE POST
========================= */
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: " . BASE_URL . "home");
    exit;
}

/* =========================
   CSRF KONTROL
========================= */
if (!Csrf::verify($_POST["csrf_token"] ?? null)) {
    $_SESSION["error"] = "Geçersiz istek (CSRF)";
    header("Location: " . BASE_URL . "login");
    exit;
}

/* =========================
   IP & BAN KONTROL
========================= */
$db  = new Database();
$ip  = $_SERVER["REMOTE_ADDR"];
$now = date("Y-m-d H:i:s");

$ban = $db->fetch(
    "SELECT * FROM login_attempts WHERE ip_address = ?",
    "s",
    [$ip]
);

if ($ban && $ban["banned_until"] && $ban["banned_until"] > $now) {
    $_SESSION["error"] = "Çok fazla hatalı giriş. Lütfen daha sonra tekrar deneyin.";
    header("Location: " . BASE_URL . "login");
    exit;
}

/* =========================
   CAPTCHA KONTROL
========================= */
if (
    !isset($_POST["captcha"]) ||
    !isset($_SESSION["captcha_answer"]) ||
    strtolower(trim($_POST["captcha"])) !== strtolower($_SESSION["captcha_answer"])
) {
    $_SESSION["error"] = "Güvenlik sorusu hatalı";
    recordFail($db, $ip);
    header("Location: " . BASE_URL . "login");
    exit;
}
unset($_SESSION["captcha_answer"]);
unset($_SESSION["captcha_question"]);


/* =========================
   INPUT
========================= */
$username = trim($_POST["username"] ?? "");
$password = $_POST["password"] ?? "";

/* Validation */
if ($username === "" || $password === "") {
    $_SESSION["error"] = "Kullanıcı adı ve şifre zorunlu";
    recordFail($db, $ip);
    header("Location: " . BASE_URL . "login");
    exit;
}

/* =========================
   KULLANICI KONTROL
========================= */
$sql  = "SELECT id, username, password FROM users WHERE username = ? LIMIT 1";
$user = $db->fetch($sql, "s", [$username]);

if (!$user || !password_verify($password, $user["password"])) {
    $_SESSION["error"] = "Kullanıcı adı veya şifre hatalı";
    recordFail($db, $ip);
    header("Location: " . BASE_URL . "login");
    exit;
}

/* =========================
   BAŞARILI GİRİŞ
========================= */

/* Session fixation koruması */
session_regenerate_id(true);

/* Hatalı deneme kayıtlarını temizle */
$db->execute(
    "DELETE FROM login_attempts WHERE ip_address = ?",
    "s",
    [$ip]
);

/* Session */
$_SESSION["user_id"]  = $user["id"];
$_SESSION["username"] = $user["username"];

/* Tek kullanımlık CSRF token sıfırla */
Csrf::destroy();

header("Location: " . BASE_URL . "login");
exit;

/* =========================
   HATALI GİRİŞ KAYDI
========================= */
function recordFail($db, $ip)
{
    $row = $db->fetch(
        "SELECT * FROM login_attempts WHERE ip_address = ?",
        "s",
        [$ip]
    );

    if ($row) {
        $attempts = $row["attempts"] + 1;
        $banUntil = null;

        if ($attempts >= 10) {
            $banUntil = date("Y-m-d H:i:s", strtotime("+10 minutes"));
        }

        $db->execute(
            "UPDATE login_attempts
             SET attempts = ?, banned_until = ?, last_attempt = NOW()
             WHERE ip_address = ?",
            "iss",
            [$attempts, $banUntil, $ip]
        );
    } else {
        $db->execute(
            "INSERT INTO login_attempts (ip_address, attempts, last_attempt)
             VALUES (?, 1, NOW())",
            "s",
            [$ip]
        );
    }
}
