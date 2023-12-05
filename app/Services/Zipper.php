<?php

namespace App\Services;

use ZipArchive;
use Illuminate\Support\Facades\Storage;

class Zipper {
    public static function createOfZip ($fileName) {
        $zip = new ZipArchive;
        $zipFileName = now()->timestamp.'-task.zip';
        $zipPath = storage_path('app/public/temp/'.$zipFileName);
        if ($zip->open($zipPath, ZipArchive::CREATE) === true){
            // $filePath = storage_path('app/public/temp'.$fileName);
            $filePath = Storage::disk('public')->path($fileName);
            $zip->addFile($filePath);
        }
        $zip->close();
        return $zipPath;
    }
}