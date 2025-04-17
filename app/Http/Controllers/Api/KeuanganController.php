<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Keuangan;
use Illuminate\Http\Request;
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
        $result = $this->list();
        
        // Check if response content is empty before decoding
        if (empty($result->getContent())) {
            $data = [];
        } else {
            $data = json_decode($result->getContent(), true);
        }
        
        return response()->view('pages.keuangan.index', [
            'status' => 'success',
            'data' => $data['data'] ?? [],
            'pagination' => $data['pagination'] ?? []
        ]);
    }

    /**
     * Get list of keuangan with pagination and filters.
     */
    public function list(Request $request = null): JsonResponse
    {
        try {
            $request = $request ?? request();
            $query = Keuangan::query()->with('pekerjaan');
            
            // Filter by pekerjaan_id if provided
            if ($request->has('pekerjaan_id')) {
                $query->where('pekerjaan_id', $request->pekerjaan_id);
            }
            
            // Filter by search term if provided
            if ($request->has('search')) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('nomor_io', 'like', "%{$searchTerm}%")
                      ->orWhere('pjpsda', 'like', "%{$searchTerm}%")
                      ->orWhereHas('pekerjaan', function($query) use ($searchTerm) {
                          $query->where('nama_pekerjaan', 'like', "%{$searchTerm}%");
                      });
                });
            }
            
            // Date range filter
            if ($request->has('start_date') && $request->has('end_date')) {
                $query->whereBetween('created_at', [$request->start_date, $request->end_date]);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'pekerjaan_id', 'rkap', 'rkapt', 'rab', 'real_kontrak', 'nilai_progres', 'actual_spj', 'actual_sap', 'actual_pembayaran', 'created_at', 'updated_at'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results if requested
            if ($request->has('per_page')) {
                $perPage = $request->input('per_page', 15);
                $keuangans = $query->paginate($perPage);
                
                return response()->json([
                    'status' => 'success',
                    'data' => $keuangans->items(),
                    'pagination' => [
                        'total' => $keuangans->total(),
                        'per_page' => $keuangans->perPage(),
                        'current_page' => $keuangans->currentPage(),
                        'last_page' => $keuangans->lastPage(),
                        'from' => $keuangans->firstItem(),
                        'to' => $keuangans->lastItem()
                    ]
                ]);
            } else {
                // Get all results without pagination
                $keuangans = $query->get();
                
                return response()->json([
                    'status' => 'success',
                    'data' => $keuangans
                ]);
            }
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
        return response()->view('pages.keuangan.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        DB::beginTransaction();
        try {
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
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $keuangan = Keuangan::create($validator->validated());

            DB::commit();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Keuangan data created successfully',
                    'data' => $keuangan
                ], 201);
            }
            
            // Redirect to show page with success message
            return redirect()->route('keuangan.show', $keuangan->id)
                ->with('success', 'Keuangan data created successfully');
        } catch (Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create keuangan data',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to create keuangan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $keuangan = Keuangan::with('pekerjaan')->findOrFail($id);
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'data' => $keuangan
                ]);
            }
            
            return response()->view('pages.keuangan.show', [
                'keuangan' => $keuangan 
            ]);
        } catch (Exception $e) {
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                        ? 'Keuangan data not found' 
                        : 'Failed to retrieve keuangan data',
                    'error' => $e->getMessage()
                ], $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException ? 404 : 500);
            }
            
            return response()->view('pages.keuangan.index', [
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Keuangan data not found' 
                    : 'Failed to retrieve keuangan data',
            ], 404);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $keuangan = Keuangan::findOrFail($id);
            
            return response()->view('pages.keuangan.edit', [
                'keuangan' => $keuangan // Pass keuangan data to the view
            ]);
        } catch (Exception $e) {
            return response()->view('pages.keuangan.index', [
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Keuangan data not found' 
                    : 'Failed to retrieve keuangan data',
            ], 404);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Keuangan data not found'
                    ], 404);
                }
                
                return redirect()->route('keuangan.index')
                    ->with('error', 'Keuangan data not found');
            }

            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'sometimes|exists:pekerjaans,id',
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
                if ($request->wantsJson() || $request->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Validation failed',
                        'errors' => $validator->errors()
                    ], 422);
                }
                
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $keuangan->update($validator->validated());

            DB::commit();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Keuangan data updated successfully',
                    'data' => $keuangan
                ]);
            }
            
            // Redirect to show page with success message
            return redirect()->route('keuangan.show', $keuangan->id)
                ->with('success', 'Keuangan data updated successfully');
        } catch (Exception $e) {
            DB::rollBack();
            
            if ($request->wantsJson() || $request->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update keuangan data',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update keuangan data: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        DB::beginTransaction();
        try {
            $keuangan = Keuangan::find($id);
            
            if (!$keuangan) {
                if (request()->wantsJson() || request()->ajax()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Keuangan data not found'
                    ], 404);
                }
                
                return redirect()->route('keuangan.index')
                    ->with('error', 'Keuangan data not found');
            }

            $keuangan->delete();
            
            DB::commit();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Keuangan data deleted successfully'
                ]);
            }
            
            // Redirect to index page with success message
            return redirect()->route('keuangan.index')
                ->with('success', 'Keuangan data deleted successfully');
        } catch (Exception $e) {
            DB::rollBack();
            
            if (request()->wantsJson() || request()->ajax()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete keuangan data',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('keuangan.index')
                ->with('error', 'Failed to delete keuangan data: ' . $e->getMessage());
        }
    }
    
    /**
     * Get keuangan data by pekerjaan ID
     */
    public function getByPekerjaanId($pekerjaanId): JsonResponse
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
                'data' => $keuangan
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve keuangan data',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}