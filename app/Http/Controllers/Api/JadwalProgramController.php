<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\JadwalProgram;
use App\Models\Program;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;
use Carbon\Carbon;

class JadwalProgramController extends Controller
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
        
        return response()->view('pages.jadwal-program.index', [
            'status' => 'success',
            'data' => $data['data'] ?? []
        ]);
    }

    /**
     * Get list of jadwal programs with pagination.
     */
    public function list(): JsonResponse
    {
        try {
            $jadwalPrograms = JadwalProgram::with('program.pekerjaan')
                ->orderBy('updated_at', 'desc')
                ->get();
                
            return response()->json([
                'status' => 'success',
                'data' => $jadwalPrograms,
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
        // Get all programs for the dropdown selection
        $programs = Program::all();
        
        return response()->view('pages.jadwal-program.create', [
            'programs' => $programs
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): JsonResponse
    {
        DB::beginTransaction();
        try {
            $validator = Validator::make($request->all(), [
                'program_id' => 'required|exists:programs,id',
                'desain' => 'required|date',
                'verifikasi' => 'required|date|after_or_equal:desain',
                'pbj' => 'required|date|after_or_equal:verifikasi',
                'pelaksanaan' => 'required|date|after_or_equal:pbj',
                'catatan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $jadwalProgram = JadwalProgram::create([
                'program_id' => $request->program_id,
                'desain' => Carbon::parse($request->desain),
                'verifikasi' => Carbon::parse($request->verifikasi),
                'pbj' => Carbon::parse($request->pbj),
                'pelaksanaan' => Carbon::parse($request->pelaksanaan),
                'catatan' => $request->catatan,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal program created successfully',
                'data' => $jadwalProgram
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create jadwal program',
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
            $jadwalProgram = JadwalProgram::with('program.pekerjaan')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalProgram
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Jadwal program not found' 
                    : 'Failed to retrieve jadwal program',
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
            $jadwalProgram = JadwalProgram::findOrFail($id);
            $programs = Program::all();
            
            return response()->view('pages.jadwal-program.edit', [
                'data' => $jadwalProgram,
                'programs' => $programs
            ]);
        } catch (Exception $e) {
            // Redirect back to index with error message
            return response()->view('pages.jadwal-program.index', [
                'status' => 'error',
                'message' => 'Jadwal program not found',
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
            $jadwalProgram = JadwalProgram::find($id);
            
            if (!$jadwalProgram) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal program not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'program_id' => 'sometimes|exists:programs,id',
                'desain' => 'sometimes|date',
                'verifikasi' => 'sometimes|date|after_or_equal:desain',
                'pbj' => 'sometimes|date|after_or_equal:verifikasi',
                'pelaksanaan' => 'sometimes|date|after_or_equal:pbj',
                'catatan' => 'nullable|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            // Update the fields
            if ($request->has('program_id')) {
                $jadwalProgram->program_id = $request->program_id;
            }
            
            if ($request->has('desain')) {
                $jadwalProgram->desain = Carbon::parse($request->desain);
            }
            
            if ($request->has('verifikasi')) {
                $jadwalProgram->verifikasi = Carbon::parse($request->verifikasi);
            }
            
            if ($request->has('pbj')) {
                $jadwalProgram->pbj = Carbon::parse($request->pbj);
            }
            
            if ($request->has('pelaksanaan')) {
                $jadwalProgram->pelaksanaan = Carbon::parse($request->pelaksanaan);
            }
            
            if ($request->has('catatan')) {
                $jadwalProgram->catatan = $request->catatan;
            }
            
            $jadwalProgram->save();

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal program updated successfully',
                'data' => $jadwalProgram
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update jadwal program',
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
            $jadwalProgram = JadwalProgram::find($id);
            
            if (!$jadwalProgram) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Jadwal program not found'
                ], 404);
            }

            $jadwalProgram->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal program deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete jadwal program',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}