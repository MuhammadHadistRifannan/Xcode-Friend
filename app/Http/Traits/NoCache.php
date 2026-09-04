<?php

namespace App\Http\Traits;

trait NoCache
{
    private function noCache($response)
    {
        return $response
            ->header('Cache-Control', 'no-cache, no-store, must-revalidate')
            ->header('Pragma', 'no-cache')
            ->header('Expires', '0');
    }
}
