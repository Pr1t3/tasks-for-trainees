<?php

namespace WebApp;

use Exception;

try {

    require_once __DIR__ . '/../../vendor/autoload.php';
    require_once __DIR__ . '/../YandexDiskRepository.php';

    if (!isset($_ENV['YANDEX_TOKEN'])) {
        http_response_code(500);
        header('Content-Type: application/json');
        echo json_encode(['error' => 'Yandex token is not set']);
        exit;
    }

    $repository = new \Repository\YandexDiskRepository($_ENV['YANDEX_TOKEN']);

    $target = strtok($_SERVER['REQUEST_URI'], '?');
    $method = $_SERVER['REQUEST_METHOD'];

    switch ($target) {
        case '/':
            $files = $repository->getFiles();
            include __DIR__ . '/templates/list_files.php';
            break;
        case '/files':
            if ($method === 'GET') {
                $filePath = $_GET['path'] ?? '';
                if (empty($filePath)) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'File path is required']);
                    exit;
                }

                $fileContent = $repository->getFile($filePath);
                include __DIR__ . '/templates/view_file.php';
            } else {
                http_response_code(405);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Method not allowed']);
            }
            break;
        case '/upload':
            if ($method === 'POST') {
                $fileName = $_POST['name'] ?? '';
                $file = $_FILES['file']['tmp_name'] ?? '';
                $fileContent = $_POST['fileContent'] ?? '';
                if (empty($fileName) || (empty($fileContent) && empty($file))) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'File name on disk and file(or file content) are required']);
                    exit;
                }
                if (!empty($file)) {
                    $fileContent = file_get_contents($file);
                }
                $repository->uploadFile($fileName, $fileContent);
                header('Location: /upload');
            } else {
                include __DIR__ . '/templates/upload_file.php';
            }
            break;
        case '/edit':
            if ($method === 'POST') {
                $fileName = $_POST['name'] ?? '';
                $file = $_FILES['file']['tmp_name'] ?? '';
                $fileContent = $_POST['fileContent'] ?? '';
                if (empty($fileName) || (empty($fileContent) && empty($file))) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'File name on disk and file(or file content) are required']);
                    exit;
                }
                if (!empty($file)) {
                    $fileContent = file_get_contents($file);
                }
                $repository->editFile($fileName, $fileContent);
                header('Location: /edit');
            } else {
                $files = $repository->getFiles();
                include __DIR__ . '/templates/edit_file.php';
            }
            break;
        case '/delete':
            if ($method === 'POST') {
                $fileName = $_POST['name'] ?? '';
                if (empty($fileName)) {
                    http_response_code(400);
                    header('Content-Type: application/json');
                    echo json_encode(['error' => 'File name on disk is required']);
                    exit;
                }
                $repository->deleteFile($fileName);
                header('Location: /delete');
            } else {
                $files = $repository->getFiles();
                include __DIR__ . '/templates/delete_file.php';
            }
            break;
        case '/api/files':
            if ($method === 'GET') {
                $filePath = $_GET['path'] ?? '';
                if (empty($filePath)) {
                    $files = $repository->getFiles();
                    header('Content-Type: application/json');
                    echo json_encode($files);
                    break;
                }
                $fileContent = $repository->getFile($filePath);
                header('Content-Type: application/json');
                echo json_encode(['path' => $filePath, 'content' => $fileContent]);
            } else {
                http_response_code(405);
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Method not allowed']);
            }
            break;
        default:
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['error' => 'Not found']);
    }
} catch (Exception $e) {
    http_response_code(500);
    header('Content-Type: application/json');
    echo json_encode(['error' => $e->getMessage()]);
}

?>