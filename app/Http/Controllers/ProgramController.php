<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = Program::query();
            
            // Filter by status if provided
            if ($request->has('status')) {
                $query->where('status_program', $request->status);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'pekerjaan_id', 'created_at', 'updated_at'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results
            $perPage = $request->input('per_page', 15);
            $programs = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $programs,
                'message' => 'Programs retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve programs: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['status' => 'error', 'message' => 'Program not found'], 404);
        }
        return response()->json([
            'status' => 'success',
            'data' => $program,
            'message' => 'Program retrieved successfully'
        ], 200);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pekerjaan_id' => 'required|exists:pekerjaan,id',
            'pelaksanaan_program' => 'required|string',
            'status_program' => 'required|string',
            'realisasi_program' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $program = Program::create($validator->validated());
            
            return response()->json([
                'status' => 'success',
                'data' => $program,
                'message' => 'Program created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['status' => 'error', 'message' => 'Program not found'], 404);
        }

        $validated = $request->validate([
            'pekerjaan_id' => 'integer',
            'pelaksanaan_program' => 'string',
            'status_program' => 'string',
            'realisasi_program' => 'string',
        ]);

        $program->update($validated);
        return response()->json([
            'status' => 'success',
            'data' => $program,
            'message' => 'Program updated successfully'
        ], 200);
    }

    public function destroy($id)
    {
        $program = Program::find($id);
        if (!$program) {
            return response()->json(['status' => 'error', 'message' => 'Program not found'], 404);
        }

        $program->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'Program deleted successfully'
        ], 200);
    }
    
    public function getByPekerjaanId($pekerjaanId)
    {
        $programs = Program::where('pekerjaan_id', $pekerjaanId)->get();
        
        return response()->json([
            'status' => 'success',
            'data' => $programs,
            'message' => 'Programs retrieved successfully'
        ], 200);
    }
}
