<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\AppNote;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class NotesController extends Controller
{
    private function currentUserId(): int|string|null
    {
        return Auth::user()->userid ?? Auth::id();
    }

    public function index(Request $request)
    {
        $query = AppNote::query()
            ->where('created_by', $this->currentUserId())
            ->orderByDesc('id');

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($builder) use ($search) {
                $builder->where('title', 'like', '%' . $search . '%')
                    ->orWhere('content', 'like', '%' . $search . '%');
            });
        }

        return response()->json([
            'status' => true,
            'message' => 'Notes retrieved successfully',
            'data' => $query->get(),
        ]);
    }

    public function show($id)
    {
        $note = AppNote::where('id', $id)
            ->where('created_by', $this->currentUserId())
            ->firstOrFail();

        return response()->json([
            'status' => true,
            'message' => 'Note retrieved successfully',
            'data' => $note,
        ]);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'centerid' => 'required|integer',
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $note = AppNote::create([
            'centerid' => $request->centerid,
            'created_by' => $this->currentUserId(),
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Note created successfully',
            'data' => $note,
        ], 201);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer',
            'content' => 'required|string',
            'title' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation failed ',
                'errors' => $validator->errors(),
            ], 422);
        }

        $note = AppNote::where('id', $request->id)
            ->where('created_by', $this->currentUserId())
            ->first();

        if (! $note) {
            return response()->json([
                'status' => false,
                'message' => 'Note not found.',
            ], 404);
        }

        $note->update([
            'title' => $request->title,
            'content' => $request->content,
        ]);

        return response()->json([
            'status' => true,
            'message' => 'Note updated successfully',
            'data' => $note->fresh(),
        ]);
    }

    public function destroy($id)
    {
        $note = AppNote::where('id', $id)
            ->where('created_by', $this->currentUserId())
            ->firstOrFail();
        $note->delete();

        return response()->json([
            'status' => true,
            'message' => 'Note deleted successfully',
        ]);
    }
}