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
    public function store(Request $request)
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
                return redirect()->back()
                    ->withErrors($validator)
                    ->withInput();
            }

            $validated = $validator->validated();
            $validated['user_id'] = Auth::user()->id; // Assign the authenticated user ID

            if (!$validated['user_id']) {
                return redirect()->back()->with('error', 'Authentication required');
            }

            $pekerjaan = Pekerjaan::create($validated);

            DB::commit();
            
            // Check if request expects JSON response
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pekerjaan created successfully',
                    'data' => $pekerjaan
                ], 201);
            }
            
            // For web requests, redirect to the index page with success message
            return redirect()->route('pages.pekerjaan')
                ->with('success', 'Pekerjaan created successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to create pekerjaan',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to create pekerjaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): Response
    {
        try {
            $pekerjaan = Pekerjaan::findOrFail($id);

            return response()->view('pages.pekerjaan.show', [
                'pekerjaan' => $pekerjaan // Pass pekerjaan data to the view
            ]);
        } catch (Exception $e) {
            return response()->view('pages.pekerjaan', [
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Pekerjaan not found' 
                    : 'Failed to retrieve pekerjaan',
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
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pekerjaan not found'
                    ], 404);
                }
                
                return redirect()->route('pages.pekerjaan')
                    ->with('error', 'Pekerjaan not found');
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
                if ($request->expectsJson()) {
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

            $pekerjaan->update($validator->validated());

            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pekerjaan updated successfully',
                    'data' => $pekerjaan
                ]);
            }
            
            return redirect()->route('pages.pekerjaan')
                ->with('success', 'Pekerjaan updated successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to update pekerjaan',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->back()
                ->with('error', 'Failed to update pekerjaan: ' . $e->getMessage())
                ->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, string $id)
    {
        DB::beginTransaction();
        try {
            $pekerjaan = Pekerjaan::find($id);
            
            if (!$pekerjaan) {
                if ($request->expectsJson()) {
                    return response()->json([
                        'status' => 'error',
                        'message' => 'Pekerjaan not found'
                    ], 404);
                }
                
                return redirect()->route('pages.pekerjaan')
                    ->with('error', 'Pekerjaan not found');
            }

            $pekerjaan->delete();
            
            DB::commit();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Pekerjaan deleted successfully'
                ]);
            }
            
            return redirect()->route('pages.pekerjaan')
                ->with('success', 'Pekerjaan deleted successfully');
                
        } catch (Exception $e) {
            DB::rollBack();
            
            if ($request->expectsJson()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Failed to delete pekerjaan',
                    'error' => $e->getMessage()
                ], 500);
            }
            
            return redirect()->route('pages.pekerjaan')
                ->with('error', 'Failed to delete pekerjaan: ' . $e->getMessage());
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
                'pekerjaan' => $pekerjaan // Pass pekerjaan data to the view
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