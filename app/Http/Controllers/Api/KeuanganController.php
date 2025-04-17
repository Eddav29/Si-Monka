<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keuangan;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class KeuanganController extends Controller
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
        
        return response()->view('pages.keuangan.data-keuangan.index', [
            'status' => 'success',
            'data' => $data['data'] ?? []
        ]);
    }

    /**
     * Get list of keuangan with pagination.
     */
    public function list(): JsonResponse
    {
        try {
            $keuangan = Keuangan::with('pekerjaan')
                ->orderBy('updated_at', 'desc')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => $keuangan,
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
        return response()->view('pages.keuangan.data-keuangan.create');
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
                'rkap' => 'required|numeric',
                'rkapt' => 'required|numeric',
                'pjpsda' => 'required|numeric',
                'rab' => 'required|numeric',
                'nomor_io' => 'required|string',
                'real_kontrak' => 'required|numeric',
                'nilai_progres' => 'required|numeric',
                'actual_spj' => 'required|numeric',
                'actual_sap' => 'required|numeric',
                'actual_pembayaran' => 'required|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $keuangan = Keuangan::create([
                'pekerjaan_id' => $request->pekerjaan_id,
                'rkap' => $request->rkap,
                'rkapt' => $request->rkapt,
                'pjpsda' => $request->pjpsda,
                'rab' => $request->rab,
                'nomor_io' => $request->nomor_io,
                'real_kontrak' => $request->real_kontrak,
                'nilai_progres' => $request->nilai_progres,
                'actual_spj' => $request->actual_spj,
                'actual_sap' => $request->actual_sap,
                'actual_pembayaran' => $request->actual_pembayaran,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Keuangan data created successfully',
                'data' => $keuangan
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create keuangan data',
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
            $keuangan = Keuangan::with('pekerjaan')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $keuangan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Keuangan data not found' 
                    : 'Failed to retrieve keuangan data',
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
            $keuangan = Keuangan::findOrFail($id);
            
            return response()->view('pages.keuangan.data-keuangan.edit', [
                'data' => $keuangan,
            ]);
        } catch (Exception $e) {
            // Redirect back to index with error message
            return response()->view('pages.keuangan.data-keuangan.index', [
                'status' => 'error',
                'message' => 'Keuangan data not found',
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
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Keuangan data not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'sometimes|exists:pekerjaans,id',
                'rkap' => 'sometimes|numeric',
                'rkapt' => 'sometimes|numeric',
                'pjpsda' => 'sometimes|numeric',
                'rab' => 'sometimes|numeric',
                'nomor_io' => 'sometimes|string',
                'real_kontrak' => 'sometimes|numeric',
                'nilai_progres' => 'sometimes|numeric',
                'actual_spj' => 'sometimes|numeric',
                'actual_sap' => 'sometimes|numeric',
                'actual_pembayaran' => 'sometimes|numeric',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $keuangan->update($request->all());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Keuangan data updated successfully',
                'data' => $keuangan
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update keuangan data',
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
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Keuangan data not found'
                ], 404);
            }

            $keuangan->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Keuangan data deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete keuangan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}