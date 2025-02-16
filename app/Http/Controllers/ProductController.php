<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\UMKM;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    // ✅ API untuk menambahkan produk berdasarkan UMKM ID
    public function store(Request $request, $umkmId)
    {
        $umkm = UMKM::find($umkmId);
        if (!$umkm) {
            return response()->json(['message' => 'UMKM tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'price' => 'required|numeric|min:0',
            // 'image' => 'nullable|image|mimes:jpg,jpeg,png|max:2048', // Max 2MB
            'image' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $imagePath = null;

        if ($request->hasFile('image')) {
            $imagePath = $request->file('image')->store('products', 'public');
        } elseif ($request->image) {
            $imagePath = $request->image; // Simpan URL langsung jika bukan file
        }

        $product = Product::create([
            'umkm_id' => $umkm->id,
            'name' => $request->name,
            'description' => $request->description,
            'price' => $request->price,
            'image' => $imagePath,
        ]);

        return response()->json([
            'message' => 'Produk berhasil ditambahkan',
            'product' => $product
        ], 201);
    }

    // ✅ API untuk mengedit produk berdasarkan ID produk
    public function update(Request $request, $productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'sometimes|required|string',
            'price' => 'sometimes|required|numeric|min:0',
            'image' => 'nullable|string|url',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        if ($request->has('name')) {
            $product->name = $request->name;
        }
        if ($request->has('description')) {
            $product->description = $request->description;
        }
        if ($request->has('price')) {
            $product->price = $request->price;
        }
        if ($request->has('image')) {
            $product->image = $request->image;
        }

        $product->save();

        return response()->json([
            'message' => 'Produk berhasil diperbarui',
            'product' => $product
        ], 200);
    }

    // ✅ API untuk menghapus produk berdasarkan ID produk
    public function destroy($productId)
    {
        $product = Product::find($productId);
        if (!$product) {
            return response()->json(['message' => 'Produk tidak ditemukan'], 404);
        }

        $product->delete();

        return response()->json(['message' => 'Produk berhasil dihapus'], 200);
    }



    // ✅ API untuk mendapatkan semua produk berdasarkan UMKM ID
    public function getByUMKM($umkmId)
    {
        $umkm = UMKM::find($umkmId);
        if (!$umkm) {
            return response()->json(['message' => 'UMKM tidak ditemukan'], 404);
        }

        return response()->json($umkm->products, 200);
    }
}
