<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Program;
use Illuminate\Http\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Exception;

class ProgramController extends Controller
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
        
        return response()->view('pages.program.index', [
            'status' => 'success',
            'data' => $data['data'] ?? []
        ]);
    }

    /**
     * Get list of programs with pagination.
     */
    public function list(): JsonResponse
    {
        try {
            $programs = Program::with('pekerjaan')->orderBy('updated_at', 'desc')->get();
            return response()->json([
                'status' => 'success',
                'data' => $programs,
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
        return response()->view('pages.program.create');
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
                'pelaksanaan_program' => 'required|string',
                'status_program' => 'required|string',
                'realisasi_program' => 'required|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $program = Program::create([
                'pekerjaan_id' => $request->pekerjaan_id,
                'pelaksanaan_program' => $request->pelaksanaan_program,
                'status_program' => $request->status_program,
                'realisasi_program' => $request->realisasi_program,
            ]);

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Program created successfully',
                'data' => $program
            ], 201);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create program',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id): Response
    {
        try {
            $program = Program::findOrFail($id);
            
            return response()->view('pages.program.edit', [
                'data' => $program,
            ]);
        } catch (Exception $e) {
            // Redirect back to index with error message
            return response()->view('pages.program.index', [
                'status' => 'error',
                'message' => 'Program not found',
                'error' => $e->getMessage()
            ], 404);    
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id): JsonResponse
    {
        try {
            $program = Program::with('pekerjaan')->findOrFail($id);
            
            return response()->json([
                'status' => 'success',
                'data' => $program
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => $e instanceof \Illuminate\Database\Eloquent\ModelNotFoundException 
                    ? 'Program not found' 
                    : 'Failed to retrieve program',
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
            $program = Program::find($id);
            
            if (!$program) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found'
                ], 404);
            }

            $validator = Validator::make($request->all(), [
                'pekerjaan_id' => 'sometimes|exists:pekerjaans,id',
                'pelaksanaan_program' => 'sometimes|string',
                'status_program' => 'sometimes|string',
                'realisasi_program' => 'sometimes|string',
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Validation failed',
                    'errors' => $validator->errors()
                ], 422);
            }

            $program->update($request->all());

            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Program updated successfully',
                'data' => $program
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update program',
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
            $program = Program::find($id);
            
            if (!$program) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Program not found'
                ], 404);
            }

            $program->delete();
            
            DB::commit();
            return response()->json([
                'status' => 'success',
                'message' => 'Program deleted successfully'
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete program',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}