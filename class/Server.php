<?php
class UpdateManager {
    private $apiUrl = 'https://server.dichvucloudvps.com/API/';

    // Lấy danh sách file và phiên bản từ server
    private function getFileListFromServer() {
        $data = [
            'KeyAPI'    => KeyAPI,
            'Service'   => "CloudVPS"
        ];
        $response = $this->sendCurlRequest($this->apiUrl . 'version', $data);
        if (empty($response) || !isset($response['files'])) {
            throw new Exception("Error fetching file list or invalid response.");
        }

        return $response['files'];
    }

    // Lấy phiên bản hiện tại của một file
    private function getCurrentFileVersion($fileName) {
        $versionFilePath = __DIR__ . '/version.json';
        if (file_exists($versionFilePath)) {
            $versionData = json_decode(file_get_contents($versionFilePath), true);
            return $versionData['files'][$fileName]['version'] ?? '1.0.0';
        }
        return '1.0.0';
    }

    // Kiểm tra và cập nhật các file nếu cần thiết
    public function checkAndUpdateFiles() {
        $fileList = $this->getFileListFromServer();

        foreach ($fileList as $fileName => $newVersion) {
            $currentVersion = $this->getCurrentFileVersion($fileName);

            if (version_compare($newVersion, $currentVersion, '>')) {
                $data = [
                    'KeyAPI' => KeyAPI,
                    'file'   => $fileName,
                    'version'=> $currentVersion,
                    'Service'   => "CloudVPS"
                ];
                $response = $this->sendCurlRequest($this->apiUrl . 'service', $data);
                // print_r($response);
                if ($response['error'] === 0 && !empty($response['file_content'])) {
                    $encryptedContent = base64_decode($response['file_content']);
                    if ($this->downloadAndOverwriteFile($fileName, $encryptedContent)) {
                        $this->updateVersionFile($fileName, $newVersion);
                        echo "Update $fileName to version $newVersion successful.\n\n";
                    } else {
                        echo "Failed to update $fileName.\n\n";
                    }
                } else {
                    echo "Error updating $fileName: " . ($response['message'] ?? 'Unknown error') . "\n\n";
                }
            }
        }
    }

    // Gửi yêu cầu cURL (tối ưu hóa)
    private function sendCurlRequest($url, $data = null) {
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, $data !== null);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            throw new Exception('cURL Error: ' . curl_error($ch));
        }
        curl_close($ch);

        return json_decode($response, true);
    }

    // Tải và ghi đè file
    private function downloadAndOverwriteFile($filePath, $fileContent) {
        $fullPath = PATH . $filePath;
        $this->ensureDirectoryExists($fullPath);

        return file_put_contents($fullPath, $fileContent) !== false;
    }

    // Đảm bảo thư mục tồn tại
    private function ensureDirectoryExists($path) {
        $directory = dirname($path);
        if (!is_dir($directory)) {
            if (!mkdir($directory, 0755, true)) {
                throw new Exception("Failed to create directory: $directory");
            }
        }
    }

    // Cập nhật thông tin phiên bản sau khi tải file mới
    private function updateVersionFile($fileName, $newVersion) {
        $versionFilePath = __DIR__ . '/../version.json';
        $versionData = file_exists($versionFilePath) ? json_decode(file_get_contents($versionFilePath), true) : ['files' => []];

        $versionData['files'][$fileName] = [
            'version' => $newVersion,
            'Service' => "CloudVPS",
            'path'    => PATH . $fileName
        ];

        file_put_contents($versionFilePath, json_encode($versionData, JSON_PRETTY_PRINT));
    }
}