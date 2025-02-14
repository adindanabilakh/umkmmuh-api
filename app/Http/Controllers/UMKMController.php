<?php

namespace App\Http\Controllers;

use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UMKMController extends Controller
{

    // ✅ GET all UMKM
    public function index()
    {
        return response()->json(UMKM::all(), 200);
    }

    // ✅ GET single UMKM
    public function show($id)
    {
        $umkm = UMKM::find($id);
        if (!$umkm) {
            return response()->json(['message' => 'UMKM tidak ditemukan'], 404);
        }
        return response()->json($umkm, 200);
    }

    // ✅ POST create UMKM
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|unique:u_m_k_m_s|max:255',
            'type' => 'required|string',
            'status' => 'required|in:Active,Pending,Inactive',
            'address' => 'required|string',
            'location_url' => 'required|url',
            'email' => 'required|email|unique:u_m_k_m_s',
            'password' => 'required|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $umkm = UMKM::create([
            'name' => $request->name,
            'type' => $request->type,
            'status' => $request->status,
            'address' => $request->address,
            'location_url' => $request->location_url,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return response()->json([
            'message' => 'UMKM berhasil ditambahkan',
            'umkm' => $umkm
        ], 201);
    }

    // ✅ PUT update UMKM
    public function update(Request $request, $id)
    {
        $umkm = UMKM::find($id);
        if (!$umkm) {
            return response()->json(['message' => 'UMKM tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|max:255|unique:u_m_k_m_s,name,' . $id,
            'type' => 'sometimes|required|string',
            'status' => 'sometimes|required|in:Active,Pending,Inactive',
            'address' => 'sometimes|required|string',
            'location_url' => 'sometimes|required|url',
            'email' => 'sometimes|required|email|unique:u_m_k_m_s,email,' . $id,
            'password' => 'nullable|min:6',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $umkm->update([
            'name' => $request->name ?? $umkm->name,
            'type' => $request->type ?? $umkm->type,
            'status' => $request->status ?? $umkm->status,
            'address' => $request->address ?? $umkm->address,
            'location_url' => $request->location_url ?? $umkm->location_url,
            'email' => $request->email ?? $umkm->email,
            'password' => $request->password ? Hash::make($request->password) : $umkm->password,
        ]);

        return response()->json([
            'message' => 'UMKM berhasil diperbarui',
            'umkm' => $umkm
        ], 200);
    }
    // ✅ DELETE UMKM
    public function destroy($id)
    {
        $umkm = UMKM::find($id);
        if (!$umkm) {
            return response()->json(['message' => 'UMKM tidak ditemukan'], 404);
        }

        $umkm->delete();

        return response()->json(['message' => 'UMKM berhasil dihapus'], 200);
    }
}
