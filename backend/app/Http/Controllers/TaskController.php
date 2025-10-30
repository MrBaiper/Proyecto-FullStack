<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class TaskController extends Controller
{
    //GET /api/tasks
    public function index()
    {
        $tasks = Task::with('project')->get();

        return response()->json([
            'message' => 'Lista de tareas obtenida correctamente',
            'data' => $tasks
        ], 200);
    }

    //POST /api/tasks
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'status' => 'required|string|in:pendiente,en_proceso,completada',
            'project_id' => 'required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors()
            ], 422);
        }

        $task = Task::create($validator->validated());

        return response()->json([
            'message' => 'Tarea creada correctamente',
            'data' => $task
        ], 201);
    }

    //GET /api/tasks/{id}
    public function show(string $id)
    {
        $task = Task::with('project')->find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        return response()->json([
            'message' => 'Tarea obtenida correctamente',
            'data' => $task
        ], 200);
    }

    //PUT /api/tasks/{id}
    public function update(Request $request, string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $validator = Validator::make($request->all(), [
            'title' => 'sometimes|required|string|max:255',
            'status' => 'sometimes|required|string|in:pendiente,en_proceso,completada',
            'project_id' => 'sometimes|required|exists:projects,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Error de validacion',
                'errors' => $validator->errors()
            ], 422);
        }

        $task->update($validator->validated());

        return response()->json([
            'message' => 'Tarea actualizada correctamente',
            'data' => $task
        ], 200);
    }

    //DELETE /api/tasks/{id}
    public function destroy(string $id)
    {
        $task = Task::find($id);

        if (!$task) {
            return response()->json([
                'message' => 'Tarea no encontrada'
            ], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Tarea eliminada correctamente'
        ], 200);
    }
}
