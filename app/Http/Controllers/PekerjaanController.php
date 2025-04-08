<?php

namespace App\Http\Controllers;

use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class PekerjaanController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Pekerjaan::query()->with(['user', 'keuangan', 'program']);
            
            // Filter by status if provided
            if ($request->has('status_proses')) {
                $query->where('status_proses', $request->status_proses);
            }
            
            if ($request->has('status_gr')) {
                $query->where('status_gr', $request->status_gr);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'nama_pekerjaan', 'created_at', 'updated_at', 'user_id'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results
            $perPage = $request->input('per_page', 15);
            $pekerjaans = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaans,
                'message' => 'Pekerjaan retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pekerjaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function create()
    {
        return view('pekerjaan.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama_pekerjaan' => 'required|string|max:255',
            'volume' => 'required|numeric',
            'satuan' => 'required|string|max:50',
            'sumber_keterangan' => 'required|string',
            'sub_unit' => 'required|string',
            'jenis_op' => 'required|string',
            'nilai_paket_pekerjaan' => 'required|numeric',
            'jadwal_mulai' => 'required|date',
            'jadwal_selesai' => 'required|date|after_or_equal:jadwal_mulai',
            'jenis_investasi' => 'required|string',
            'div' => 'required|string',
            'nilai_item_investasi' => 'required|numeric',
            'pbj' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $data = $validator->validated();
            $data['user_id'] = Auth::id() ?? 1; // Default to 1 if not authenticated for API testing
            $data['status_proses'] = $request->status_proses ?? 'Belum Dimulai';
            $data['status_gr'] = $request->status_gr ?? 'Belum GR';

            $pekerjaan = Pekerjaan::create($data);
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaan,
                'message' => 'Pekerjaan created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create pekerjaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $pekerjaan = Pekerjaan::with(['user', 'keuangan', 'program', 'monevKeuangan'])->find($id);
            
            if (!$pekerjaan) {
                return response()->json(['status' => 'error', 'message' => 'Pekerjaan not found'], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaan,
                'message' => 'Pekerjaan retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve pekerjaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit(Pekerjaan $pekerjaan)
    {
        // Keep view functionality if needed
    }

    public function update(Request $request, $id)
    {
        try {
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                return response()->json(['status' => 'error', 'message' => 'Pekerjaan not found'], 404);
            }

            $validated = $request->validate([
                'nama_pekerjaan' => 'required|string|max:255',
                'volume' => 'required|numeric',
                'satuan' => 'required|string|max:50',
                'sumber_keterangan' => 'required|string',
                'sub_unit' => 'required|string',
                'jenis_op' => 'required|string',
                'nilai_paket_pekerjaan' => 'required|numeric',
                'jadwal_mulai' => 'required|date',
                'jadwal_selesai' => 'required|date|after_or_equal:jadwal_mulai',
                'status_proses' => 'required|string',
                'status_gr' => 'required|string',
                'jenis_investasi' => 'required|string',
                'div' => 'required|string',
                'nilai_item_investasi' => 'required|numeric',
                'pbj' => 'required|string',
            ]);

            $pekerjaan->update($validated);
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaan->fresh(),
                'message' => 'Pekerjaan updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update pekerjaan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                return response()->json(['status' => 'error', 'message' => 'Pekerjaan not found'], 404);
            }

            $pekerjaan->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Pekerjaan deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete pekerjaan: ' . $e->getMessage()
            ], 500);
        }
    }
}
