<?php

require(__DIR__ . '/../core/file.php');

class CronJobProcessor
{
    private $cronFolder;

    public function __construct($cronFolder)
    {
        if (!is_dir($cronFolder)) {
            throw new Exception("Thư mục không tồn tại: $cronFolder");
        }
        $this->cronFolder = $cronFolder;
    }

    public function processFiles()
    {
        try {
            $files = scandir($this->cronFolder);
            if ($files === false) {
                throw new Exception("Không thể quét thư mục: {$this->cronFolder}");
            }

            if (empty($files)) {
                throw new Exception("Thư mục không có file nào.");
            }

            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..') {
                    echo "Đang xử lý file: $file\n";
                    $this->processFile($file);
                }
            }
        } catch (Exception $e) {
            echo "Lỗi: " . $e->getMessage() . "\n";
        }
    }

    private function processFile($file)
    {
        try {
            $filePath = $this->cronFolder . '/' . $file;
            if (!file_exists($filePath)) {
                throw new Exception("File không tồn tại: $filePath");
            }

            $encryptedContent = file_get_contents($filePath);
            if ($encryptedContent === false) {
                throw new Exception("Không thể đọc file: $filePath");
            }
            $decryptedContent = decryptContent($encryptedContent);
            if ($decryptedContent === false) {
                throw new Exception("Giải mã không thành công: $file");
            }
            $tempFilePath = $this->createTempFile($decryptedContent);
            if ($tempFilePath === false) {
                throw new Exception("Không thể tạo file tạm cho nội dung đã giải mã.");
            }
            $this->executeFile($tempFilePath, $file);
        } catch (Exception $e) {
            echo "Lỗi khi xử lý file $file: " . $e->getMessage() . "\n";
        } finally {
            if (isset($tempFilePath)) {
                $this->cleanupTempFile($tempFilePath);
            }
        }
    }


    private function createTempFile($content)
    {
        $tempFilePath = __DIR__ . '/' . uniqid('cron_', true) . '.php';
        if (file_put_contents($tempFilePath, $content) === false) {
            throw new Exception("Không thể ghi file tạm: $tempFilePath");
        }
        return $tempFilePath;
    }

    private function executeFile($tempFilePath, $file)
    {
        if (!file_exists($tempFilePath) || filesize($tempFilePath) === 0) {
            throw new Exception("File tạm không tồn tại hoặc rỗng: $tempFilePath");
        }
        $command = 'php ' . escapeshellarg($tempFilePath) . ' 2>&1';
        $output = shell_exec($command);

        if ($output === null) {
            $lastError = error_get_last();
            throw new Exception("Lỗi khi chạy file: $file - Chi tiết: " . $lastError['message']);
        } else {
            if (strpos($output, 'Fatal error:') !== false || strpos($output, 'Parse error:') !== false) {
                echo "Đã xảy ra lỗi khi thực thi file: $file\n";
                echo "Chi tiết lỗi:\n$output\n";
            } else {
                echo "Kết quả từ file: $file\n";
                echo $output;
            }
        }
    }

    private function cleanupTempFile($tempFilePath)
    {
        if (!unlink($tempFilePath)) {
            throw new Exception("Không thể xóa file tạm: $tempFilePath");
        }
    }
}

try {
    $cronProcessor = new CronJobProcessor(__DIR__ . '/../storage/cron');
    $cronProcessor->processFiles();
} catch (Exception $e) {
    echo "Lỗi khi khởi tạo CronJobProcessor: " . $e->getMessage() . "\n";
}
