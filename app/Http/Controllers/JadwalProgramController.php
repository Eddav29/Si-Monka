<?php

namespace App\Http\Controllers;

use App\Models\JadwalProgram;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class JadwalProgramController extends Controller
{
    public function index(Request $request)
    {
        try {
            $query = JadwalProgram::query()->with('program');
            
            // Filter by program_id if provided
            if ($request->has('program_id')) {
                $query->where('program_id', $request->program_id);
            }
            
            // Sort by field
            $sortBy = $request->input('sort_by', 'created_at');
            $sortOrder = $request->input('sort_order', 'desc');
            
            // Validate sort column exists
            $allowedSortColumns = ['id', 'program_id', 'created_at', 'updated_at'];
            $sortBy = in_array($sortBy, $allowedSortColumns) ? $sortBy : 'created_at';
            
            $query->orderBy($sortBy, $sortOrder);
            
            // Paginate results
            $perPage = $request->input('per_page', 15);
            $jadwalPrograms = $query->paginate($perPage);
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalPrograms,
                'message' => 'Jadwal program retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'program_id' => 'required|exists:programs,id',
            'desain' => 'nullable|string',
            'verifikasi' => 'nullable|string',
            'pbj' => 'nullable|string',
            'pelaksanaan' => 'nullable|string',
            'catatan' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'errors' => $validator->errors(),
                'message' => 'Validation failed'
            ], 422);
        }

        try {
            $jadwalProgram = JadwalProgram::create($validator->validated());
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalProgram,
                'message' => 'Jadwal program created successfully'
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $jadwalProgram = JadwalProgram::with('program')->find($id);
            
            if (!$jadwalProgram) {
                return response()->json(['status' => 'error', 'message' => 'Jadwal program not found'], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalProgram,
                'message' => 'Jadwal program retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $jadwalProgram = JadwalProgram::find($id);
            
            if (!$jadwalProgram) {
                return response()->json(['status' => 'error', 'message' => 'Jadwal program not found'], 404);
            }

            $validated = $request->validate([
                'program_id' => 'exists:programs,id',
                'desain' => 'nullable|string',
                'verifikasi' => 'nullable|string',
                'pbj' => 'nullable|string',
                'pelaksanaan' => 'nullable|string',
                'catatan' => 'nullable|string',
            ]);

            $jadwalProgram->update($validated);
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalProgram->fresh(),
                'message' => 'Jadwal program updated successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to update jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $jadwalProgram = JadwalProgram::find($id);
            
            if (!$jadwalProgram) {
                return response()->json(['status' => 'error', 'message' => 'Jadwal program not found'], 404);
            }

            $jadwalProgram->delete();
            
            return response()->json([
                'status' => 'success',
                'message' => 'Jadwal program deleted successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to delete jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getByProgramId($programId)
    {
        try {
            $jadwalProgram = JadwalProgram::where('program_id', $programId)->first();
            
            if (!$jadwalProgram) {
                return response()->json([
                    'status' => 'error', 
                    'message' => 'Jadwal program not found for this program'
                ], 404);
            }
            
            return response()->json([
                'status' => 'success',
                'data' => $jadwalProgram,
                'message' => 'Jadwal program retrieved successfully'
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve jadwal program: ' . $e->getMessage()
            ], 500);
        }
    }
}
