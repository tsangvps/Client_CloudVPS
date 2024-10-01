<?php
require(__DIR__ . '/core/file.php');

try {
    $updateManager = new UpdateManager();
    $updateManager->checkAndUpdateFiles();
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>
