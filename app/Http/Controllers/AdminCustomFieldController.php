<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminCustomFieldController extends Controller
{
    private $table = 'jcow_profile_fields';

    public function index()
    {
        $fields = [];
        if (Schema::hasTable($this->table)) {
            $fields = DB::table($this->table)->get();
        }

        return view('admin.custom_fields.index', compact('fields'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'fields' => 'required|array|size:7',
            'fields.*.name' => 'nullable|string|max:255',
            'fields.*.type' => 'required|string|in:Text Box,Select Box,Disabled',
            'fields.*.options' => 'nullable|string',
            'fields.*.description' => 'nullable|string',
            'fields.*.required' => 'nullable|boolean'
        ]);

        if (!Schema::hasTable($this->table)) {
            return back()->withErrors(['message' => 'Tabel jcow_profile_fields tidak ditemukan. Pastikan database Anda sudah memiliki tabel ini.']);
        }

        // Karena sistem baru menggunakan 7 slot permanen, kita hapus semua lalu insert ulang
        DB::table($this->table)->truncate();

        $insertData = [];
        foreach ($request->fields as $field) {
            $insertData[] = [
                'name' => $field['name'] ?? '',
                'type' => $field['type'] ?? 'Disabled',
                'options' => $field['options'] ?? '',
                'description' => $field['description'] ?? '',
                'required' => isset($field['required']) && $field['required'] ? 1 : 0,
            ];
        }

        DB::table($this->table)->insert($insertData);

        return back()->with('success', 'Custom fields berhasil disimpan.');
    }

    public function destroy($id)
    {
        if (!Schema::hasTable($this->table)) {
            return back()->withErrors(['message' => 'Tabel jcow_profile_fields tidak ditemukan.']);
        }

        DB::table($this->table)->where('id', $id)->delete();
        
        // Opsional: Hapus nilainya juga dari jcow_profile_values jika tabel itu digunakan
        if (Schema::hasTable('jcow_profile_values')) {
            DB::table('jcow_profile_values')->where('field_id', $id)->delete();
        }

        return back()->with('success', 'Custom field berhasil dihapus.');
    }
}
