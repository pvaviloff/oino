<?php
namespace Oino\Services;

class FileUploader
{
    public function uploadJson(string $json, string $filePath): int
    {
        return file_put_contents($filePath, $json, LOCK_EX);
    }
}