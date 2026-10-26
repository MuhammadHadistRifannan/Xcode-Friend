<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class AdminBlockController extends Controller
{
    private function getTable()
    {
        return Schema::hasTable('jcow_settings') ? 'jcow_settings' : 'jcow_gvars';
    }

    private function getKeyName()
    {
        return Schema::hasTable('jcow_settings') ? 'setting_name' : 'gkey';
    }

    private function getValueName()
    {
        return Schema::hasTable('jcow_settings') ? 'setting_value' : 'gvalue';
    }

    public function index()
    {
        // Get raw HTML blocks from jcow_gvars / jcow_settings
        $blocks = [
            'header_code' => \App\Helpers\SettingHelper::get('theme_block_header_code', ''),
            'footer_code' => \App\Helpers\SettingHelper::get('theme_block_footer_code', ''),
            'left_column' => \App\Helpers\SettingHelper::get('theme_block_left_column', ''),
            'right_column' => \App\Helpers\SettingHelper::get('theme_block_right_column', ''),
            'center_column' => \App\Helpers\SettingHelper::get('theme_block_center_column', ''),
        ];

        // Daftar foto/banner yang telah diunggah untuk blocks
        $uploadedImages = [];
        $blocksPath = public_path('uploads/blocks');
        if (file_exists($blocksPath)) {
            $files = scandir($blocksPath);
            foreach ($files as $file) {
                if ($file !== '.' && $file !== '..' && !is_dir($blocksPath . '/' . $file)) {
                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                    if (in_array($ext, ['jpg', 'jpeg', 'png', 'gif', 'webp', 'svg'])) {
                        $uploadedImages[] = [
                            'filename' => $file,
                            'url' => asset('uploads/blocks/' . $file),
                            'size' => @filesize($blocksPath . '/' . $file) ?: 0,
                            'time' => @filemtime($blocksPath . '/' . $file) ?: time(),
                        ];
                    }
                }
            }
            usort($uploadedImages, fn($a, $b) => $b['time'] <=> $a['time']);
        }

        return view('admin.themes.blocks', compact('blocks', 'uploadedImages'));
    }

    public function update(Request $request)
    {
        $table = Schema::hasTable('jcow_gvars') ? 'jcow_gvars' : 'jcow_settings';
        $keyName = Schema::hasTable('jcow_gvars') ? 'gkey' : 'setting_name';
        $valName = Schema::hasTable('jcow_gvars') ? 'gvalue' : 'setting_value';

        $blocksToSave = [
            'theme_block_header_code' => $request->input('header_code') ?? '',
            'theme_block_footer_code' => $request->input('footer_code') ?? '',
            'theme_block_left_column' => $request->input('left_column') ?? '',
            'theme_block_right_column' => $request->input('right_column') ?? '',
            'theme_block_center_column' => $request->input('center_column') ?? '',
        ];

        foreach ($blocksToSave as $key => $html) {
            DB::table($table)->updateOrInsert(
                [$keyName => $key],
                [$valName => $html ?? '']
            );
            \Illuminate\Support\Facades\Cache::forget('jcow_setting_' . $key);
        }

        return back()->with('success', 'Kode HTML Blocks berhasil disimpan.');
    }

    /**
     * Upload satu atau beberapa foto untuk blocks
     */
    public function uploadImage(Request $request)
    {
        $request->validate([
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
            'images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp,svg|max:5120',
        ]);

        $files = [];
        if ($request->hasFile('images')) {
            $files = $request->file('images');
        } elseif ($request->hasFile('image')) {
            $files = [$request->file('image')];
        }

        if (empty($files)) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => 'Pilih file foto terlebih dahulu.'], 422);
            }
            return back()->withErrors(['image' => 'Pilih file foto terlebih dahulu.']);
        }

        $destDir = public_path('uploads/blocks');
        if (!file_exists($destDir)) {
            mkdir($destDir, 0777, true);
        }

        $savedList = [];
        foreach ($files as $file) {
            $originalName = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $cleanName = Str::slug($originalName);
            if (empty($cleanName)) $cleanName = 'block_image';

            $filename = time() . '_' . Str::random(5) . '_' . $cleanName . '.' . $file->getClientOriginalExtension();
            $file->move($destDir, $filename);

            $savedList[] = [
                'filename' => $filename,
                'url' => asset('uploads/blocks/' . $filename),
                'size' => @filesize($destDir . '/' . $filename) ?: 0,
                'time' => time(),
            ];
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json([
                'success' => true,
                'message' => count($savedList) . ' foto berhasil diunggah!',
                'images' => $savedList,
            ]);
        }

        return back()->with('success', count($savedList) . ' foto berhasil diunggah ke Blocks!');
    }

    /**
     * Hapus foto blocks
     */
    public function deleteImage(Request $request)
    {
        $filename = basename($request->input('filename'));
        $filePath = public_path('uploads/blocks/' . $filename);

        if (file_exists($filePath) && !is_dir($filePath)) {
            @unlink($filePath);
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => true, 'message' => 'Foto berhasil dihapus.']);
            }
            return back()->with('success', 'Foto berhasil dihapus.');
        }

        if ($request->ajax() || $request->wantsJson()) {
            return response()->json(['success' => false, 'message' => 'File tidak ditemukan.'], 404);
        }
        return back()->with('error', 'File tidak ditemukan.');
    }
}
