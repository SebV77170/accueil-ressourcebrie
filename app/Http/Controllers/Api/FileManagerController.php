<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Files\WebdavFileService;
use Illuminate\Http\Request;

class FileManagerController extends Controller
{
    public function __construct(private WebdavFileService $service)
    {
    }

    public function index(Request $request)
    {
        $path = $request->query('path', '');

        return response()->json([
            'path' => $path,
            'items' => $this->service->list($path),
        ]);
    }

    public function download(Request $request)
    {
        $path = $request->query('path', '');
        $download = $this->service->download($path);

        return response()->streamDownload(function () use ($download) {
            fpassthru($download['stream']);
            fclose($download['stream']);
        }, $download['name'], [
            'Content-Type' => $download['mime'],
        ]);
    }

    public function upload(Request $request)
    {
        $request->validate([
            'file' => ['required', 'file'],
            'path' => ['nullable', 'string'],
        ]);

        $file = $request->file('file');

        $this->service->upload(
            $request->input('path', ''),
            $file->getClientOriginalName(),
            $file
        );

        return response()->json(['status' => 'ok']);
    }

    public function createFolder(Request $request)
    {
        $request->validate([
            'path' => ['nullable', 'string'],
            'name' => ['required', 'string'],
        ]);

        $this->service->createDirectory(
            $request->input('path', ''),
            $request->input('name')
        );

        return response()->json(['status' => 'ok']);
    }

    public function move(Request $request)
    {
        $request->validate([
            'from' => ['required', 'string'],
            'to' => ['required', 'string'],
        ]);

        $this->service->move(
            $request->input('from'),
            $request->input('to')
        );

        return response()->json(['status' => 'ok']);
    }

    public function destroy(Request $request)
    {
        $request->validate([
            'path' => ['required', 'string'],
        ]);

        $this->service->delete($request->input('path'));

        return response()->json(['status' => 'ok']);
    }
}
