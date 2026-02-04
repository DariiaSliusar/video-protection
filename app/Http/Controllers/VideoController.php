<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\URL;

class VideoController extends Controller
{
    private const VIDEO_PATH = 'videos/test-video';

    public function getPlaylist(Request $request)
    {
        $path = self::VIDEO_PATH . '/playlist.m3u8';

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        $content = Storage::disk('private')->get($path);

        $content = preg_replace_callback('/^.*\\.ts$/m', function ($matches) {
            $filename = trim($matches[0]);

            return URL::temporarySignedRoute(
                'video.segment',
                now()->addMinutes(1),
                ['filename' => $filename]
            );
        }, $content);

        return response($content)
            ->header('Content-Type', 'application/x-mpegURL')
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate');
    }

    public function getSegment(Request $request, $filename)
    {
        $path = self::VIDEO_PATH . '/' . $filename;

        if (!Storage::disk('private')->exists($path)) {
            abort(404);
        }

        return Storage::disk('private')->response($path, null, [
            'Content-Type' => 'video/MP2T',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
