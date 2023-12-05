<?php

use Google\Client;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/drive', function () {
    $client = new Client();
    $client->setClientId('187047503063-836580lsqijqali7vvmshilhh6bag565.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://127.0.0.1:8000/google-drive/api/callback');
    $client->setScopes([
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ]);
    $url = $client->createAuthUrl();
    return redirect($url);
});

Route::get('/google-drive/api/callback', function () {
    $code = request('code');
    return $code;
    $client = new Client();
    $client->setClientId('187047503063-836580lsqijqali7vvmshilhh6bag565.apps.googleusercontent.com');
    $client->setClientSecret('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');
    $client->setRedirectUri('http://127.0.0.1:8000/google-drive/api/callback');
    $token = $client->fetchAccessTokenWithAuthCode($code);
    return $token;
});

Route::get('/upload', function () {
    $access_token = 'ya29.a0AfB_byBvEuEH-MV9I-VLdn9EVx0K49UX-2q7RUI6Q_k5iz4gbY5PGeogHp9-EaSa2H2C2H5ny3oKfULOMvV-uuBNLa3RK--5DIkiwndT9BpnV_P_Cb9D4Q4OXva4WKaUHT9VyznaDGds1CyUAqken7THoXhTLN-dj-AxaCgYKAfYSARMSFQHGX2MigNg8Ti320LMhISKH9Pz12w0171';
    $client = new Client();
    $client->setAccessToken($access_token);
    $service = new Google\Service\Drive($client);
    $file = new Google\Service\Drive\DriveFile();

    DEFINE("TESTFILE", 'testfile-small.txt');
    if (!file_exists(TESTFILE)) {
        $fh = fopen(TESTFILE, 'w');
        fseek($fh, 1024 * 1024);
        fwrite($fh, "!", 1);
        fclose($fh);
    }

    $file = new Google\Service\Drive\DriveFile();
    $file->setName("Hello World!");
    $service->files->create(
        $file,
        [
            'data' => file_get_contents(TESTFILE),
            'mimeType' => 'application/octet-stream',
            'uploadType' => 'multipart'
        ]
    );
});