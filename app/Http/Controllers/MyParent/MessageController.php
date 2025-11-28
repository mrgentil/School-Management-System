<?php

namespace App\Http\Controllers\MyParent;

use App\Http\Controllers\Controller;
use App\Http\Traits\MessageTrait;
use App\Models\User;
use App\Models\StudentRecord;
use App\Helpers\Qs;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    use MessageTrait;

    protected $routePrefix = 'parent.messages';
    protected $viewPrefix = 'pages.parent.messages';

    public function __construct()
    {
        $this->middleware('my_parent');
    }

    /**
     * Liste des messages
     */
    public function index(Request $request)
    {
        $filter = $request->get('filter', 'inbox');
        $messages = $this->getMessages(Auth::id(), $filter);

        return view('partials.messages.index', [
            'messages' => $messages,
            'filter' => $filter,
            'routePrefix' => $this->routePrefix,
            'canSendToAll' => false,
        ]);
    }

    /**
     * Formulaire de nouveau message
     */
    public function create()
    {
        // Récupérer les enseignants des enfants du parent
        $parent = Auth::user();
        $children = StudentRecord::where('my_parent_id', $parent->id)
            ->where('session', Qs::getCurrentSession())
            ->with(['my_class'])
            ->get();

        // Enseignants disponibles (tous les teachers)
        $teachers = User::where('user_type', 'teacher')->orderBy('name')->get();

        // Admins
        $admins = User::whereIn('user_type', ['admin', 'super_admin'])->orderBy('name')->get();

        return view('pages.parent.messages.create', [
            'teachers' => $teachers,
            'admins' => $admins,
            'children' => $children,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    /**
     * Envoyer un message
     */
    public function store(Request $request)
    {
        $request->validate([
            'recipient_id' => 'required|exists:users,id',
            'subject' => 'required|string|max:255',
            'content' => 'required|string',
        ]);

        $message = $this->createMessage(
            Auth::id(),
            [$request->recipient_id],
            $request->subject,
            $request->content
        );

        return redirect()->route('parent.messages.index')
            ->with('flash_success', 'Message envoyé avec succès');
    }

    /**
     * Voir un message
     */
    public function show($id)
    {
        $message = $this->getMessage($id, Auth::id());

        if (!$message) {
            return redirect()->route('parent.messages.index')
                ->with('flash_danger', 'Message non trouvé');
        }

        // Marquer comme lu
        $this->markAsRead($id, Auth::id());

        // Récupérer les réponses
        $replies = $this->getReplies($id);

        return view('partials.messages.show', [
            'message' => $message,
            'replies' => $replies,
            'routePrefix' => $this->routePrefix,
        ]);
    }

    /**
     * Répondre à un message
     */
    public function reply(Request $request, $id)
    {
        $request->validate([
            'content' => 'required|string',
        ]);

        $this->createReply($id, Auth::id(), $request->content);

        return redirect()->route('parent.messages.show', $id)
            ->with('flash_success', 'Réponse envoyée');
    }
}
