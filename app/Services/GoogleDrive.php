<?php

namespace App\Services;

use Google\Client;
use Google\Service\Drive;
use Google\Service\Drive\DriveFile;

class GoogleDrive {
    protected $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function uploadFile($access_token, $zipPath) {
        $this->client->setAccessToken($access_token);
        $service = new Drive($this->client);
        $file = new DriveFile();

        $file->setName("Hello World!");
        $service->files->create(
            $file,
            [
                'data' => file_get_contents($zipPath),
                'mimeType' => 'application/octet-stream',
                'uploadType' => 'multipart'
            ]
        );
    }
}