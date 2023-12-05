<?php

namespace App\Http\Controllers;

use ZipArchive;
use Google\Client;
use App\Models\Task;
use Google\Service\Drive;
use App\Models\Webservice;
use Illuminate\Http\Request;
use App\Services\GoogleDrive;
use Google\Service\Drive\DriveFile;
use App\Http\Resources\TaskResource;
use App\Services\Zipper;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class ServiceController extends Controller
{
    public const SCOPES_GOOGLE = [
        'https://www.googleapis.com/auth/drive',
        'https://www.googleapis.com/auth/drive.file',
    ];

    public function connect(Request $request, Client $client) {
        if ($request->service === 'google-drive') {
            $client->setScopes(self::SCOPES_GOOGLE);
            $url = $client->createAuthUrl();

            return response(['url' => $url]);
        }
    }

    public function callback(Request $request, Client $client) {
        $token = $client->fetchAccessTokenWithAuthCode($request->code);

        $webService = Webservice::create([
            'name' => 'google-drive',
            'token' => $token,
            'user_id' => auth()->id(),
        ]);

        return $webService;
    }

    public function store(Request $request, Webservice $service, GoogleDrive $drive) {
        // we need to fetch last 7 days of tasks
        $tasks = Task::where('created_at', '>=', now()->subDays(7))->get();
        // create a json file with this data
        $folder = 'temp';
        $fileName = "$folder/task_json.json";
        Storage::disk('public')->put($fileName, TaskResource::collection($tasks)->toJson());
        // create a zip file with this json data
        $zipFileName = Zipper::createOfZip($fileName);
        // send this zip to google drive
        $access_token = $service->token['access_token'];
        $drive->uploadFile($access_token, $zipFileName);
        Storage::disk('public')->delete($zipFileName);
        return response('Upload', Response::HTTP_CREATED);
    }
}
