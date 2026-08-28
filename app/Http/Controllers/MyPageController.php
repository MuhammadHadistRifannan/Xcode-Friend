<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class MyPageController extends Controller
{
    /**
     * Redirect ke pages/mine yang dikelola oleh PageController.
     * MyPageController dipertahankan sebagai alias untuk backward compatibility
     * (sidebar menu menggunakan route 'my-pages.index').
     */
    public function index()
    {
        return app(PageController::class)->mine();
    }
}
