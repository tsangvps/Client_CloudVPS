<?php
date_default_timezone_set('Asia/Ho_Chi_Minh');

//Cấu Hình Cơ Sở Dữ Liệu
define('APP_NAME', 'VPS VIÊT NAM GIÁ ƯU ĐÃI');
define('DOMAINS', 'https://dichvucloudvps.com'); //Url Tới Trang Chính của WEBSITE VD: http://localhost/{path nếu có}
define('PATH', __DIR__.'/../storage/');

// Máy Chủ Key
define('KeyAPI', ''); //Liên Hệ fb: https://facebook.com/tsangvps.com để được hỗ trợ
define('encryptionKey', ''); //Liên Hệ fb: https://facebook.com/tsangvps.com để được hỗ trợ
define('encryptionIv', ''); //Liên Hệ fb: https://facebook.com/tsangvps.com để được hỗ trợ
define("IP_ADMIN", []);

// Bảng Giá
define('PRICE_ADD_RAM', '30000'); //Giá 30k 1GB Ram
define('PRICE_ADD_DISK', '25000'); //Giá 25k 10GB Disk
define('PRICE_ADD_CPU', '30000'); //Giá 30k 1CPU

// Cơ Cấu SQL
define('DB_CONNECTION', 'mysql');
define('DB_HOST', 'localhost');
define('DB_DATABASE', '');
define('DB_USERNAME', '');
define('DB_PASSWORD', '');
