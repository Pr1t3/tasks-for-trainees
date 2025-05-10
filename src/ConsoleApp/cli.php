<?php

namespace ConsoleApp;

class YandexDiskCli {
    private $config;
    private $command;
    private $repository;

    public function __construct($config)
    {
        if (!isset($config['command']) && !isset($config['c']) || isset($config['command']) && isset($config['c'])) {
            echo "One command is required\n";
            exit(1);
        }
        $this->config = $config;
        $this->command = $config['command'] ?? $config['c'];
        $this->repository = new \Repository\YandexDiskRepository($config['token']);
    }

    public function execute()
    {
        switch ($this->command) {
            case 'upload':
                $this->uploadFile();
                break;
            case 'view':
                $this->viewFile();
                break;
            case 'delete':
                $this->deleteFile();
                break;
            case 'list':
                $this->listFiles();
                break;
            case 'edit':
                $this->editFile();
                break;
            default:
                echo "Unknown command\n";
        }
    }

    private function uploadFile()
    {
        $filePathOnDisk = $this->config['path'] ?? $this->config['p'] ?? '';
        $filePath = $this->config['file'] ?? $this->config['f'] ?? '';
        $fileContent = $this->config['content'] ?? $this->config['C'] ?? '';

        if (empty($filePathOnDisk) || (empty($filePath) && empty($fileContent))) {
            echo "File path on disk and file path(or file content) are required\n";
            exit(1);
        }

        if (!empty($filePath)) {
            $fileContent = file_get_contents($filePath);
        }
        $this->repository->uploadFile($filePathOnDisk, $fileContent);
        echo "File uploaded successfully\n";
    }

    private function viewFile()
    {
        $filePath = $this->config['path'] ?? $this->config['p'] ?? '';

        if (empty($filePath)) {
            echo "File path is required\n";
            exit(1);
        }

        print_r($this->repository->getFile($filePath));
    }

    private function deleteFile()
    {
        $filePathOnDisk = $this->config['path'] ?? $this->config['p'] ?? '';

        if (empty($filePathOnDisk)) {
            echo "File path on disk is required\n";
            exit(1);
        }

        $this->repository->deleteFile($filePathOnDisk);
        echo "File deleted successfully\n";
    }

    private function listFiles()
    {
        $files = $this->repository->getFiles();
        print_r($files);
    }

    private function editFile()
    {
        $filePathOnDisk = $this->config['path'] ?? $this->config['p'] ?? '';
        $filePath = $this->config['file'] ?? $this->config['f'] ?? '';
        $fileContent = $this->config['content'] ?? $this->config['C'] ?? '';

        if (empty($filePathOnDisk) || (empty($filePath) && empty($fileContent))) {
            echo "File path on disk and file path(or file content) are required\n";
            exit(1);
        }

        if (!empty($filePath)) {
            $fileContent = file_get_contents($filePath);
        }
        $this->repository->editFile($filePathOnDisk, $fileContent);
        echo "File edited successfully\n";
    }

}

?>