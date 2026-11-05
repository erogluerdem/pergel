<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\View\View;

class MessageController extends Controller
{
    /**
     * Display a listing of user messages or a friendly fallback when Message model is not available.
     */
    public function index(Request $request): View
    {
        try {
            if (class_exists(\App\Models\Message::class)) {
                $messages = \App\Models\Message::where('user_id', auth()->id() ?? 0)
                    ->orderBy('created_at', 'desc')
                    ->paginate(20);

                return view('messages.index', compact('messages'));
            }
        } catch (\Throwable $e) {
            // fall through to placeholder view
        }

        return view('messages.index');
    }

    /**
     * Mark a message as read
     */
    public function markAsRead(Request $request, $id)
    {
        try {
            if (class_exists(\App\Models\Message::class)) {
                $msg = \App\Models\Message::where('id', $id)->where('user_id', auth()->id() ?? 0)->first();
                if ($msg) {
                    $msg->is_read = true;
                    $msg->save();
                    return redirect()->back()->with('success', 'Mesaj okundu olarak işaretlendi.');
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return redirect()->back()->with('error', 'Mesaj bulunamadı.');
    }

    /**
     * Delete a message
     */
    public function destroy(Request $request, $id)
    {
        try {
            if (class_exists(\App\Models\Message::class)) {
                $msg = \App\Models\Message::where('id', $id)->where('user_id', auth()->id() ?? 0)->first();
                if ($msg) {
                    $msg->delete();
                    return redirect()->route('messages.index')->with('success', 'Mesaj silindi.');
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }
        return redirect()->back()->with('error', 'Mesaj silinemedi.');
    }

    /**
     * Show reply form
     */
    public function replyForm(Request $request, $id)
    {
        try {
            if (class_exists(\App\Models\Message::class)) {
                $msg = \App\Models\Message::find($id);
                return view('messages.reply', compact('msg'));
            }
        } catch (\Throwable $e) {
        }
        return redirect()->back()->with('error', 'Yanıt formu açılamadı.');
    }

    /**
     * Store reply
     */
    public function storeReply(Request $request, $id)
    {
        $request->validate(['body' => 'required|string']);
        try {
            if (class_exists(\App\Models\Message::class)) {
                $parent = \App\Models\Message::find($id);
                if ($parent) {
                    $reply = \App\Models\Message::create([
                        'user_id' => $parent->from_user_id ?? auth()->id(),
                        'from_user_id' => auth()->id(),
                        'subject' => 'RE: ' . ($parent->subject ?? ''),
                        'body' => $request->body,
                        'is_read' => false,
                    ]);
                    return redirect()->route('messages.index')->with('success', 'Yanıt gönderildi.');
                }
            }
        } catch (\Throwable $e) {
        }
        return redirect()->back()->with('error', 'Yanıt gönderilemedi.');
    }
}
