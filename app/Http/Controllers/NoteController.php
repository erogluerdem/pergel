<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class NoteController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (class_exists(\App\Models\Note::class)) {
                $notes = \App\Models\Note::where('user_id', auth()->id() ?? 0)
                    ->orderBy('updated_at', 'desc')
                    ->paginate(20);

                return view('notes.index', compact('notes'));
            }
        } catch (\Throwable $e) {
            // fallthrough
        }

        return view('notes.index');
    }

    public function create()
    {
        return view('notes.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            if (class_exists(\App\Models\Note::class)) {
                \App\Models\Note::create([
                    'user_id' => auth()->id() ?? 0,
                    'title' => $request->title,
                    'body' => $request->body,
                ]);
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('notes.index')->with('success', 'Not oluşturuldu.');
    }

    public function edit($id)
    {
        try {
            $note = class_exists(\App\Models\Note::class) ? \App\Models\Note::where('id', $id)->where('user_id', auth()->id() ?? 0)->first() : null;
        } catch (\Throwable $e) {
            $note = null;
        }
        return view('notes.edit', compact('note'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        try {
            if (class_exists(\App\Models\Note::class)) {
                $note = \App\Models\Note::where('id', $id)->where('user_id', auth()->id() ?? 0)->first();
                if ($note) {
                    $note->update($request->only('title', 'body'));
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return redirect()->route('notes.index')->with('success', 'Not güncellendi.');
    }

    public function destroy($id)
    {
        try {
            if (class_exists(\App\Models\Note::class)) {
                $note = \App\Models\Note::where('id', $id)->where('user_id', auth()->id() ?? 0)->first();
                if ($note) $note->delete();
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return redirect()->route('notes.index')->with('success', 'Not silindi.');
    }
}
