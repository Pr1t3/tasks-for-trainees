<?php

namespace Repository;

use Arhitector\Yandex\Disk;
use Exception;

class YandexDiskRepository {
    private $disk;

    public function __construct($token)
    {
        $this->disk = new Disk($token);
    }

    public function getFiles()
    {
        $files = [];
        $resources = $this->disk->getResources();

        foreach ($resources as $item) {
            $files[] = [
                'name' => $item['name'],
                'path' => $item['path']
            ];
        }

        return $files;
    }

    public function getFile($fileName)
    {
        $resource = $this->disk->getResource($fileName);
        $fp = fopen('php://memory', 'r+b');
        $resource->download($fp);
        rewind($fp);
        $fileContent = stream_get_contents($fp);
        fclose($fp);
        return $fileContent;
    }

    public function uploadFile($fileName, $fileContent)
    {
        $fp = fopen('php://memory', 'r+b');
        fwrite($fp, $fileContent);
        rewind($fp);
        $resource = $this->disk->getResource($fileName);
        $resource->upload($fp);
        fclose($fp);
    }

    public function deleteFile($fileName)
    {
        $resource = $this->disk->getResource($fileName);
        $resource->delete();
    }

    public function editFile($fileName, $fileContent)
    {
        $fp = fopen('php://memory', 'r+b');
        fwrite($fp, $fileContent);
        rewind($fp);
        $resource = $this->disk->getResource($fileName);
        $resource->upload($fp, true);
        fclose($fp);
    }
}
?>