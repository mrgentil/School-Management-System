<?php

namespace App\Http\Controllers\SupportTeam;

use App\Http\Controllers\Controller;
use App\Services\WhatsAppService;
use App\Helpers\Qs;
use Illuminate\Http\Request;

class WhatsAppTestController extends Controller
{
    public function __construct()
    {
        $this->middleware('teamSA');
    }

    /**
     * Page de test WhatsApp
     */
    public function index()
    {
        $whatsapp = new WhatsAppService();
        $isConfigured = $whatsapp->isConfigured();
        
        return view('pages.support_team.whatsapp_test', compact('isConfigured'));
    }

    /**
     * Envoyer un message de test
     */
    public function send(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string|max:1000',
        ]);

        $whatsapp = new WhatsAppService();

        if (!$whatsapp->isConfigured()) {
            return back()->with('flash_danger', 'WhatsApp n\'est pas configuré. Vérifiez les variables WHATSAPP_TOKEN et WHATSAPP_PHONE_NUMBER_ID dans le fichier .env');
        }

        $result = $whatsapp->sendMessage($request->phone, $request->message);

        if ($result['success']) {
            return back()->with('flash_success', '✅ Message WhatsApp envoyé avec succès au ' . $request->phone);
        } else {
            $error = is_array($result['error']) ? json_encode($result['error']) : $result['error'];
            return back()->with('flash_danger', '❌ Erreur: ' . $error);
        }
    }

    /**
     * Tester une notification de bulletin
     */
    public function testBulletin(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
        ]);

        $whatsapp = new WhatsAppService();

        if (!$whatsapp->isConfigured()) {
            return back()->with('flash_danger', 'WhatsApp n\'est pas configuré.');
        }

        $result = $whatsapp->sendBulletinNotification($request->phone, [
            'student_name' => 'Jean Test',
            'class_name' => '6ème A',
            'type_label' => 'Période 1',
            'year' => Qs::getCurrentSession(),
            'school_name' => Qs::getSetting('system_name') ?? 'Mon École',
            'url' => url('/'),
        ]);

        if ($result['success']) {
            return back()->with('flash_success', '✅ Notification bulletin envoyée avec succès !');
        } else {
            $error = is_array($result['error']) ? json_encode($result['error']) : $result['error'];
            return back()->with('flash_danger', '❌ Erreur: ' . $error);
        }
    }
}
