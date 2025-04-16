<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\MonevKeuangan;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;
use Exception;

class MonevKeuanganController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $data = $this->list();
        
        // Check if response content is empty before decoding
        if (empty($data->getContent())) {
            $data = [];
        } else {
            $data = json_decode($data->getContent(), true);
        }
        
        return response()->view('pages.keuangan.monev-keuangan.index', [
            'status' => 'success',
            'data' => $data['data'] ?? []
        ]);
    }

    /**
     * Get list of monitoring keuangan with pagination.
     */
    public function list(): JsonResponse
    {
        try {
            $monevKeuangans = MonevKeuangan::with(['pekerjaan', 'user'])
                ->orderBy('updated_at', 'desc')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangans,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve data',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()->view('pages.keuangan.monev-keuangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'required|exists:pekerjaans,id',
                'jenis_monitoring' => 'required|in:perencanaan,verifikasi,pengadaan,pelaksanaan,laporan',
                'status_monitoring' => 'required|in:Belum Dimulai,Sedang Berjalan,Selesai',
                'program' => 'required|numeric',
                'realisasi' => 'required|numeric',
                'evaluasi' => 'nullable|string',
                'pic' => 'required|string',
                'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
                'user_id' => 'required|exists:users,id',
                'tanggal_mulai' => 'required|date',
                'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Handle file upload
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('monev_keuangan', $fileName, 'public');

            $monevKeuangan = MonevKeuangan::create([
                'pekerjaan_id' => $request->pekerjaan_id,
                'jenis_monitoring' => $request->jenis_monitoring,
                'status_monitoring' => $request->status_monitoring,
                'program' => $request->program,
                'realisasi' => $request->realisasi,
                'evaluasi' => $request->evaluasi,
                'pic' => $request->pic,
                'file' => $filePath,
                'user_id' => $request->user_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_selesai' => $request->tanggal_selesai,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Monitoring keuangan created successfully',
                'data' => $monevKeuangan
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create monitoring keuangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $monevKeuangan = MonevKeuangan::with(['pekerjaan', 'user'])->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $monevKeuangan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Monitoring keuangan not found' 
                    : 'Failed to retrieve monitoring keuangan',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $monevKeuangan = MonevKeuangan::findOrFail($id);
            
            return response()->view('pages.keuangan.monev-keuangan.edit', [
                'data' => $monevKeuangan,
            ]);
        } catch (Exception $e) {
            // Redirect back to index with error message
            return response()->view('pages.keuangan.monev-keuangan.index', [
                'status' => 'error',
                'message' => 'Monitoring keuangan not found',
                'error' => $e->getMessage()
            ], 404);    
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $monevKeuangan = MonevKeuangan::find($id);
            
            if (!$monevKeuangan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Monitoring keuangan not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'sometimes|exists:pekerjaans,id',
                'jenis_monitoring' => 'sometimes|in:perencanaan,verifikasi,pengadaan,pelaksanaan,laporan',
                'status_monitoring' => 'sometimes|in:Belum Dimulai,Sedang Berjalan,Selesai',
                'program' => 'sometimes|numeric',
                'realisasi' => 'sometimes|numeric',
                'evaluasi' => 'nullable|string',
                'pic' => 'sometimes|string',
                'file' => 'sometimes|file|mimes:pdf,doc,docx,xls,xlsx,jpg,jpeg,png|max:10240',
                'user_id' => 'sometimes|exists:users,id',
                'tanggal_mulai' => 'sometimes|date',
                'tanggal_selesai' => 'sometimes|date|after_or_equal:tanggal_mulai',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update file if a new one is uploaded
            if ($request->hasFile('file')) {
                // Delete old file
                if ($monevKeuangan->file) {
                    Storage::disk('public')->delete($monevKeuangan->file);
                }
                
                // Store new file
                $file = $request->file('file');
                $fileName = time() . '_' . $file->getClientOriginalName();
                $filePath = $file->storeAs('monev_keuangan', $fileName, 'public');
                $monevKeuangan->file = $filePath;
            }

            // Update other fields
            $monevKeuangan->pekerjaan_id = $request->pekerjaan_id ?? $monevKeuangan->pekerjaan_id;
            $monevKeuangan->jenis_monitoring = $request->jenis_monitoring ?? $monevKeuangan->jenis_monitoring;
            $monevKeuangan->status_monitoring = $request->status_monitoring ?? $monevKeuangan->status_monitoring;
            $monevKeuangan->program = $request->program ?? $monevKeuangan->program;
            $monevKeuangan->realisasi = $request->realisasi ?? $monevKeuangan->realisasi;
            $monevKeuangan->evaluasi = $request->evaluasi ?? $monevKeuangan->evaluasi;
            $monevKeuangan->pic = $request->pic ?? $monevKeuangan->pic;
            $monevKeuangan->user_id = $request->user_id ?? $monevKeuangan->user_id;
            $monevKeuangan->tanggal_mulai = $request->tanggal_mulai ?? $monevKeuangan->tanggal_mulai;
            $monevKeuangan->tanggal_selesai = $request->tanggal_selesai ?? $monevKeuangan->tanggal_selesai;
            
            $monevKeuangan->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Monitoring keuangan updated successfully',
                'data' => $monevKeuangan
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update monitoring keuangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $monevKeuangan = MonevKeuangan::find($id);
            
            if (!$monevKeuangan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Monitoring keuangan not found'
                ], 404);
            }

            // Delete associated file
            if ($monevKeuangan->file) {
                Storage::disk('public')->delete($monevKeuangan->file);
            }

            $monevKeuangan->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Monitoring keuangan deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete monitoring keuangan',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}