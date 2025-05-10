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
        $fileName = $this->config['name'] ?? $this->config['n'] ?? '';
        $filePath = $this->config['file'] ?? $this->config['f'] ?? '';
        $fileContent = $this->config['content'] ?? $this->config['C'] ?? '';

        if (empty($fileName) || (empty($filePath) && empty($fileContent))) {
            echo "File name on disk and file path(or file content) are required\n";
            exit(1);
        }

        if (!empty($filePath)) {
            $fileContent = file_get_contents($filePath);
        }
        $this->repository->uploadFile($fileName, $fileContent);
        echo "File uploaded successfully\n";
    }

    private function viewFile()
    {
        $fileName = $this->config['name'] ?? $this->config['n'] ?? '';

        if (empty($fileName)) {
            echo "File name is required\n";
            exit(1);
        }

        print_r($this->repository->getFile($fileName));
    }

    private function deleteFile()
    {
        $fileName = $this->config['name'] ?? $this->config['n'] ?? '';

        if (empty($fileName)) {
            echo "File name on disk is required\n";
            exit(1);
        }

        $this->repository->deleteFile($fileName);
        echo "File deleted successfully\n";
    }

    private function listFiles()
    {
        $files = $this->repository->getFiles();
        print_r($files);
    }

    private function editFile()
    {
        $fileName = $this->config['name'] ?? $this->config['n'] ?? '';
        $filePath = $this->config['file'] ?? $this->config['f'] ?? '';
        $fileContent = $this->config['content'] ?? $this->config['C'] ?? '';

        if (empty($fileName) || (empty($filePath) && empty($fileContent))) {
            echo "File name and file path(or file content) are required\n";
            exit(1);
        }

        if (!empty($filePath)) {
            $fileContent = file_get_contents($filePath);
        }
        $this->repository->editFile($fileName, $fileContent);
        echo "File edited successfully\n";
    }

}

?>