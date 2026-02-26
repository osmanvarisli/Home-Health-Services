<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
/* Oturumdaki dili al, default tr */
$lang = $_SESSION["lang"] ?? "tr";
$text = require "lang/{$lang}.php";

// Rastgele bir soru seç
$questions = $text["captcha_questions"];
$random = $questions[array_rand($questions)];

$_SESSION["captcha_question"] = $random["question"];
$_SESSION["captcha_answer"]   = $random["answer"];
$_SESSION["captcha_options"]  = $random["options"];