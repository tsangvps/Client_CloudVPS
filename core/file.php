<?php
// PATH
$path_info = $_SERVER['PATH_INFO'] ?? '/';

//Root
if (isset($_SERVER['HTTP_HOST']) && isset($_SERVER['REQUEST_URI'])) {
    define("URL_ROOT", $_SERVER['HTTP_HOST']."/".$_SERVER['REQUEST_URI']);
} else {
    define("URL_ROOT", "default_value");
}

require_once(__DIR__.'/../class/Mail/class.smtp.php');
require_once(__DIR__.'/../class/Mail/PHPMailerAutoload.php');
require_once(__DIR__.'/../class/Mail/class.phpmailer.php');
require_once(__DIR__.'/config.php');
require_once(__DIR__.'/../class/Mysql.php');
require_once(__DIR__.'/../class/Session.php');
require_once(__DIR__.'/../class/Server.php');

// Giải mã nội dung file
function decryptContent($encryptedContent) {
    return openssl_decrypt($encryptedContent, 'AES-256-CBC', encryptionKey, 0, encryptionIv);
}

function calculateMonthDifference($startDate, $endDate) {
    $start = new DateTime($startDate);
    $end = new DateTime($endDate);
    $diff = $start->diff($end);
    $monthsDifference = ($diff->y * 12) + $diff->m;
    if($diff->d >= 15){
        $monthsDifference++;
    }
    if($diff->d < 15){
        $monthsDifference += 0.5;
    }
    if ($diff->invert == 1) {
        return 0;
    }
    return $monthsDifference;
}

