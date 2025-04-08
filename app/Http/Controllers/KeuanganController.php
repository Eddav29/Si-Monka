<?php

namespace App\Http\Controllers;

use App\Models\Keuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KeuanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Keuangan::query()->with('pekerjaan');
            
            // Filter by pekerjaan_id if provided
            if ($request->has('pekerjaan_id')) {
                $query->where('pekerjaan_id', $request->pekerjaan_id);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'pekerjaan_id', 'rkap', 'created_at', 'updated_at'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results
            $perPage = $request->input('per_page', 15);
            $keuangans = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangans,
                'message' => 'Keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pekerjaan_id' => 'required|exists:pekerjaans,id',
            'rkap' => 'nullable|numeric',
            'rkapt' => 'nullable|numeric',
            'pjpsda' => 'nullable|string',
            'rab' => 'nullable|numeric',
            'nomor_io' => 'nullable|string',
            'real_kontrak' => 'nullable|numeric',
            'nilai_progres' => 'nullable|numeric',
            'actual_spj' => 'nullable|numeric',
            'actual_sap' => 'nullable|numeric',
            'actual_pembayaran' => 'nullable|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $keuangan = Keuangan::create($validator->validated());
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangan,
                'message' => 'Keuangan data created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $keuangan = Keuangan::with('pekerjaan')->find($id);
            
            if (!$keuangan) {
                return response()->json(['status' => 'error', 'message' => 'Keuangan data not found'], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangan,
                'message' => 'Keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                return response()->json(['status' => 'error', 'message' => 'Keuangan data not found'], 404);
            }

            $validated = $request->validate([
                'pekerjaan_id' => 'exists:pekerjaans,id',
                'rkap' => 'nullable|numeric',
                'rkapt' => 'nullable|numeric',
                'pjpsda' => 'nullable|string',
                'rab' => 'nullable|numeric',
                'nomor_io' => 'nullable|string',
                'real_kontrak' => 'nullable|numeric',
                'nilai_progres' => 'nullable|numeric',
                'actual_spj' => 'nullable|numeric',
                'actual_sap' => 'nullable|numeric',
                'actual_pembayaran' => 'nullable|numeric',
            ]);

            $keuangan->update($validated);
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangan->fresh(),
                'message' => 'Keuangan data updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                return response()->json(['status' => 'error', 'message' => 'Keuangan data not found'], 404);
            }

            $keuangan->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Keuangan data deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getByPekerjaanId($pekerjaanId)
    {
        try {
            $keuangan = Keuangan::where('pekerjaan_id', $pekerjaanId)->first();
            
            if (!$keuangan) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Keuangan data not found for this pekerjaan'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangan,
                'message' => 'Keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
