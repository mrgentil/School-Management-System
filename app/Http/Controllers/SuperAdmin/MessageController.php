<?php

namespace App\Http\Controllers\SuperAdmin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Message;
use App\Models\MessageRecipient;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function index()
    {
        // Messages envoyés par les admins
        $messages = Message::where('sender_id', auth()->id())
            ->with(['recipients.recipient'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('pages.super_admin.messages.index', compact('messages'));
    }

    public function create()
    {
        // Tous les utilisateurs sauf l'admin connecté
        $users = User::where('id', '!=', auth()->id())
            ->orderBy('name')
            ->get()
            ->groupBy('user_type');

        return view('pages.super_admin.messages.create', compact('users'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'recipient_type' => 'required|in:all,role,individual',
            'role' => 'required_if:recipient_type,role',
            'recipients' => 'required_if:recipient_type,individual|array',
            'recipients.*' => 'exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        DB::beginTransaction();
        
        try {
            // Créer le message
            $message = Message::create([
                'sender_id' => auth()->id(),
                'subject' => $request->subject,
                'content' => $request->content,
                'message' => $request->content,
            ]);

            // Déterminer les destinataires
            $recipientIds = [];
            
            if ($request->recipient_type === 'all') {
                // Tous les utilisateurs sauf l'admin
                $recipientIds = User::where('id', '!=', auth()->id())->pluck('id')->toArray();
            } elseif ($request->recipient_type === 'role') {
                // Tous les utilisateurs d'un rôle spécifique
                $recipientIds = User::where('user_type', $request->role)
                    ->where('id', '!=', auth()->id())
                    ->pluck('id')
                    ->toArray();
            } else {
                // Utilisateurs sélectionnés individuellement
                $recipientIds = $request->recipients;
            }

            // Ajouter les destinataires
            foreach ($recipientIds as $recipientId) {
                MessageRecipient::create([
                    'message_id' => $message->id,
                    'recipient_id' => $recipientId,
                    'is_read' => false,
                ]);
            }

            DB::commit();
            
            return redirect()->route('super_admin.messages.index')
                ->with('success', '✅ Message envoyé à ' . count($recipientIds) . ' destinataire(s) !');
                
        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Erreur envoi message admin: ' . $e->getMessage());
            return back()->with('error', '❌ Erreur: ' . $e->getMessage())->withInput();
        }
    }

    public function show($id)
    {
        $message = Message::with(['sender', 'recipients.recipient'])
            ->where('sender_id', auth()->id())
            ->findOrFail($id);

        return view('pages.super_admin.messages.show', compact('message'));
    }

    public function destroy($id)
    {
        try {
            $message = Message::where('sender_id', auth()->id())->findOrFail($id);
            $message->delete();

            return redirect()->route('super_admin.messages.index')
                ->with('success', '✅ Message supprimé avec succès.');
        } catch (\Exception $e) {
            return back()->with('error', '❌ Erreur lors de la suppression.');
        }
    }
}
