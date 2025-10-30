<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ProjectController extends Controller
{
    //GET /api/projects
    public function index()
    {
        $projects = Project::with('tasks')->get();
        return response()->json([
            'mesage' => 'Lista de proyectos obtenida correctamente',
            'data' => $projects
        ], 200);
    }

    //POST /api/projects
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $project = Project::create($validator->validated());

        return response()->json([
            'message' => 'Proyecto creado correctamente',
            'data' => $project
        ], 201);
    }

    //GET /api/projects/{id}
    public function show(string $id)
    {
        $project = Project::with('tasks')->find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        return response()->json([
            'message' => 'Proyecto obtenido correctamente',
            'data' => $project
        ], 200);
    }

    //PUT /api/projects/{id}
    public function update(Request $request, string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        $project->update($validator->validated());

        return response()->json([
            'message' => 'Proyecto actualizado correctamente',
            'data' => $project
        ], 200);
    }

    //DELETE /api/projects/{id}
    public function destroy(string $id)
    {
        $project = Project::find($id);

        if (!$project) {
            return response()->json([
                'message' => 'Proyecto no encontrado'
            ], 404);
        }

        $project->delete();

        return response()->json([
            'message' => 'Proyecto eliminado correctamente'
        ], 200);
    }
}
