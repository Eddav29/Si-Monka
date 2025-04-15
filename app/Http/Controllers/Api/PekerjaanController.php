<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Pekerjaan;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class PekerjaanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $result = $this->list();
        
        // Check if response content is empty before decoding
        if (empty($result->getContent())) {
            $data = [];
        } else {
            $data = json_decode($result->getContent(), true);
        }
        
        return response()->view('pages.pekerjaan.index', [
            'status' => 'success',
            'data' => $data['data'] ?? []
        ]);
    }

    /**
     * Get list of pekerjaan with pagination and filters.
     */
    public function list(Request $request = null): JsonResponse
    {
        try {
            $request = $request ?? request();
            $query = Pekerjaan::query()->with(['user', 'keuangan', 'program']);
            
            // Filter by status if provided
            if ($request->has('status_proses')) {
                $query->where('status_proses', $request->status_proses);
            }
            
            if ($request->has('status_gr')) {
                $query->where('status_gr', $request->status_gr);
            }
            
            // Filter by search term if provided
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nama_pekerjaan', 'like', "%{$searchTerm}%")
                      ->orWhere('sub_unit', 'like', "%{$searchTerm}%")
                      ->orWhere('jenis_op', 'like', "%{$searchTerm}%");
                });
            }
            
            // Additional filters
            if ($request->has('jenis_op')) {
                $query->where('jenis_op', $request->jenis_op);
            }
            
            if ($request->has('sub_unit')) {
                $query->where('sub_unit', $request->sub_unit);
            }
            
            if ($request->has('jenis_investasi')) {
                $query->where('jenis_investasi', $request->jenis_investasi);
            }
            
            if ($request->has('div')) {
                $query->where('div', $request->div);
            }
            
            // Date range filter
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('jadwal_mulai', [$request->start_date, $request->end_date]);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'nama_pekerjaan', 'created_at', 'updated_at', 'user_id', 'nilai_paket_pekerjaan', 'status_proses', 'status_gr', 'jadwal_mulai', 'jadwal_selesai'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Get all results
            $pekerjaans = $query->get();
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaans
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
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'nama_pekerjaan' => 'required|string|max:255',
                'volume' => 'required|numeric',
                'satuan' => 'required|string|max:50',
                'sumber_keterangan' => 'required|string|max:255',
                'sub_unit' => 'required|string|max:255',
                'jenis_op' => 'required|string|in:Investasi,Operasional',
                'nilai_paket_pekerjaan' => 'required|numeric',
                'jadwal_mulai' => 'required|date',
                'jadwal_selesai' => 'required|date|after_or_equal:jadwal_mulai',
                'status_proses' => 'required|string|in:Belum Dimulai,Dalam Proses,Selesai',
                'status_gr' => 'required|string|in:Belum GR,Sudah GR',
                'jenis_investasi' => 'required|string|max:255',
                'div' => 'required|string|max:255',
                'nilai_item_investasi' => 'required|numeric',
                'pbj' => 'required|string|max:255',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $validated = $validator->validated();
            $validated['user_id'] = Auth::user()->id; // Assign the authenticated user ID

            if (!$validated['user_id']) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Authentication required',
                ], 401);
            }

            $pekerjaan = Pekerjaan::create($validated);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pekerjaan created successfully',
                'data' => $pekerjaan
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create pekerjaan',
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
            $pekerjaan = Pekerjaan::with(['user', 'keuangan', 'program', 'monevKeuangan'])->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $pekerjaan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Pekerjaan not found' 
                    : 'Failed to retrieve pekerjaan',
                'error' => $e->getMessage()
            ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pekerjaan not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'nama_pekerjaan' => 'sometimes|string|max:255',
                'volume' => 'sometimes|numeric',
                'satuan' => 'sometimes|string|max:50',
                'sumber_keterangan' => 'sometimes|string',
                'sub_unit' => 'sometimes|string',
                'jenis_op' => 'sometimes|string|in:Investasi,Operasional',
                'nilai_paket_pekerjaan' => 'sometimes|numeric',
                'jadwal_mulai' => 'sometimes|date',
                'jadwal_selesai' => 'sometimes|date|after_or_equal:jadwal_mulai',
                'status_proses' => 'sometimes|string|in:Belum Dimulai,Dalam Proses,Selesai',
                'status_gr' => 'sometimes|string|in:Belum GR,Sudah GR',
                'jenis_investasi' => 'sometimes|string',
                'div' => 'sometimes|string',
                'nilai_item_investasi' => 'sometimes|numeric',
                'pbj' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $pekerjaan->update($validator->validated());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pekerjaan updated successfully',
                'data' => $pekerjaan
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update pekerjaan',
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
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pekerjaan not found'
                ], 404);
            }

            $pekerjaan->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Pekerjaan deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete pekerjaan',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Display the form for creating a new resource.
     */
    public function create(): Response
    {
        return response()->view('pages.pekerjaan.create');
    }

    /**
     * Display the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $pekerjaan = Pekerjaan::findOrFail($id);
            
            return response()->view('pages.pekerjaan.edit', [
                'status' => 'success',
                'data' => $pekerjaan
            ]);
        } catch (Exception $e) {
            return response()->view('pages.pekerjaan.index', [
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Pekerjaan not found' 
                    : 'Failed to retrieve pekerjaan',
            ], 404);
        }
    }
}