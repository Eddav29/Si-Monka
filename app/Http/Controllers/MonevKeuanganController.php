<?php

namespace App\Http\Controllers;

use App\Models\MonevKeuangan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class MonevKeuanganController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = MonevKeuangan::query()->with(['pekerjaan', 'user']);
            
            // Filter by pekerjaan_id if provided
            if ($request->has('pekerjaan_id')) {
                $query->where('pekerjaan_id', $request->pekerjaan_id);
            }
            
            // Filter by jenis_monitoring if provided
            if ($request->has('jenis_monitoring')) {
                $query->where('jenis_monitoring', $request->jenis_monitoring);
            }
            
            // Filter by status_monitoring if provided
            if ($request->has('status_monitoring')) {
                $query->where('status_monitoring', $request->status_monitoring);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'pekerjaan_id', 'jenis_monitoring', 'status_monitoring', 'created_at', 'updated_at'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results
            $perPage = $request->input('per_page', 15);
            $monevKeuangans = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangans,
                'message' => 'Monev keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pekerjaan_id' => 'required|exists:pekerjaans,id',
            'jenis_monitoring' => 'required|string',
            'status_monitoring' => 'required|string',
            'program' => 'required|string',
            'realisasi' => 'required|string',
            'evaluasi' => 'required|string',
            'pic' => 'required|string',
            'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
            'tanggal_mulai' => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
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
            
            // Handle file upload
            if ($request->hasFile('file')) {
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/products', $fileName);
                $data['file'] = $fileName;
            }
            
            $monevKeuangan = MonevKeuangan::create($data);
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangan,
                'message' => 'Monev keuangan data created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $monevKeuangan = MonevKeuangan::with(['pekerjaan', 'user'])->find($id);
            
            if (!$monevKeuangan) {
                return response()->json(['status' => 'error', 'message' => 'Monev keuangan data not found'], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangan,
                'message' => 'Monev keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $monevKeuangan = MonevKeuangan::find($id);
            
            if (!$monevKeuangan) {
                return response()->json(['status' => 'error', 'message' => 'Monev keuangan data not found'], 404);
            }

            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'exists:pekerjaans,id',
                'jenis_monitoring' => 'string',
                'status_monitoring' => 'string',
                'program' => 'string',
                'realisasi' => 'string',
                'evaluasi' => 'string',
                'pic' => 'string',
                'file' => 'nullable|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:2048',
                'tanggal_mulai' => 'date',
                'tanggal_selesai' => 'date|after_or_equal:tanggal_mulai',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'errors' => $validator->errors(),
                    'message' => 'Validation failed'
                ], 422);
            }

            $data = $validator->validated();
            
            // Handle file upload
            if ($request->hasFile('file')) {
                // Delete old file if exists
                if ($monevKeuangan->getRawOriginal('file') && Storage::exists('public/products/' . $monevKeuangan->getRawOriginal('file'))) {
                    Storage::delete('public/products/' . $monevKeuangan->getRawOriginal('file'));
                }
                
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $file->storeAs('public/products', $fileName);
                $data['file'] = $fileName;
            }

            $monevKeuangan->update($data);
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangan->fresh(),
                'message' => 'Monev keuangan data updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $monevKeuangan = MonevKeuangan::find($id);
            
            if (!$monevKeuangan) {
                return response()->json(['status' => 'error', 'message' => 'Monev keuangan data not found'], 404);
            }

            // Delete file if exists
            if ($monevKeuangan->getRawOriginal('file') && Storage::exists('public/products/' . $monevKeuangan->getRawOriginal('file'))) {
                Storage::delete('public/products/' . $monevKeuangan->getRawOriginal('file'));
            }

            $monevKeuangan->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Monev keuangan data deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getByPekerjaanId($pekerjaanId)
    {
        try {
            $monevKeuangans = MonevKeuangan::where('pekerjaan_id', $pekerjaanId)
                ->with(['user'])
                ->orderBy('created_at', 'desc')
                ->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangans,
                'message' => 'Monev keuangan data retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve monev keuangan data: ' . $e->getMessage()
            ], 500);
        }
    }
}
