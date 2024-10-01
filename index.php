<?php
if(true){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
}   
require(__DIR__ . '/core/file.php');
$current_page = basename($_SERVER['PHP_SELF']);

if(file_exists(PATH.'/index.php')){
    eval('?>' . decryptContent(file_get_contents(PATH.'/index.php')));
    exit;
}else{
    echo "Vui Lòng Cập nhập trang web trước khi truy cập";
}
?>