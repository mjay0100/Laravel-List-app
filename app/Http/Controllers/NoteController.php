<?php

namespace App\Http\Controllers;

use App\Models\Note;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

use App\Models\Notebook;

class NoteController extends Controller
{
    public function index()
    {
        $user_id = Auth::id();
        $notes = Note::where('user_id', $user_id)->paginate(2);

        return view('notes.index', compact('notes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $notebooks = Notebook::where('user_id', Auth::id())->get();
        return view('notes.create', compact('notebooks'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);
        Note::create([
            'user_id' => Auth::id(),
            'title' => $request->title,
            'text' => $request->text,
            'uuid' => Str::uuid()->toString(), // Generate and assign UUID
            'notebook_id' => $request->notebook_id
        ]);
        return redirect()->route('notes.index')->with('success', 'Note created successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Note $note)
    {
        // Check if the note belongs to the authenticated user
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Pass the note to the view
        return view('notes.show', ['note' => $note]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        // Fetch the user's notebooks
        $notebooks = Notebook::where('user_id', Auth::id())->get();

        // Pass both notebooks and the note to the edit view
        return view('notes.edit', compact('notebooks', 'note'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Note $note)
    {
        if ($note->user_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        // Validate the request data
        $request->validate([
            'title' => 'required',
            'text' => 'required',
        ]);

        // Update the note
        $note->update([
            'title' => $request->title,
            'text' => $request->text,
            'notebook_id' => $request->notebook_id,
        ]);

        // Redirect with a success message
        return to_route('notes.index')->with('success', 'Note updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Note $note)
    {
        // Check if the authenticated user owns the note
        if (Auth::id() !== $note->user_id) {
            abort(403, 'Unauthorized action.');
        }

        // Delete the note
        $note->delete();

        // Redirect back to the notes index with a success message
        return to_route('notes.index')->with('success', 'Note deleted successfully!');
    }
}
