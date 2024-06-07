<?php
require(__DIR__.'/core/.config.php');
$current_page = basename($_SERVER['PHP_SELF']);
if(file_exists(__DIR__.$path_info.".php") && !is_dir(__DIR__.$path_info.".php")){
    if (empty($_SERVER['HTTP_REFERER']) || (isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], BASE_URL(null)) === false)) {
        print_r(json_encode([
            'status' => 'error',
            'msg'   =>  'Không Thể Xác Thực Người Dùng'
        ]));
        return;
    }
    include __DIR__.$path_info.".php";
    return;
}elseif (file_exists(__DIR__ . "/view".$path_info.".php")) {
    include __DIR__ . "/view".$path_info.".php";
    return;
}else{
    header("location: /client/login");
}
