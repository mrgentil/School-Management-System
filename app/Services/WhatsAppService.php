<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    protected $apiUrl;
    protected $token;
    protected $phoneNumberId;

    public function __construct()
    {
        $this->token = config('services.whatsapp.token');
        $this->phoneNumberId = config('services.whatsapp.phone_number_id');
        $this->apiUrl = "https://graph.facebook.com/v18.0/{$this->phoneNumberId}/messages";
    }

    /**
     * Vérifier si WhatsApp est configuré
     */
    public function isConfigured(): bool
    {
        return !empty($this->token) && !empty($this->phoneNumberId);
    }

    /**
     * Envoyer un message texte simple
     */
    public function sendMessage(string $phone, string $message): array
    {
        if (!$this->isConfigured()) {
            return ['success' => false, 'error' => 'WhatsApp non configuré'];
        }

        $phone = $this->formatPhone($phone);

        try {
            $response = Http::withToken($this->token)
                ->withoutVerifying() // Désactiver vérification SSL pour dev
                ->post($this->apiUrl, [
                    'messaging_product' => 'whatsapp',
                    'to' => $phone,
                    'type' => 'text',
                    'text' => [
                        'body' => $message
                    ]
                ]);

            if ($response->successful()) {
                Log::info("WhatsApp envoyé à {$phone}");
                return ['success' => true, 'data' => $response->json()];
            }

            Log::error("Erreur WhatsApp: " . $response->body());
            return ['success' => false, 'error' => $response->json()];

        } catch (\Exception $e) {
            Log::error("Exception WhatsApp: " . $e->getMessage());
            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    /**
     * Envoyer une notification de bulletin publié
     */
    public function sendBulletinNotification(string $phone, array $data): array
    {
        $message = "📋 *BULLETIN SCOLAIRE DISPONIBLE*\n\n";
        $message .= "🏫 {$data['school_name']}\n\n";
        $message .= "👤 Élève: *{$data['student_name']}*\n";
        $message .= "📚 Classe: {$data['class_name']}\n";
        $message .= "📅 Période: {$data['type_label']}\n";
        $message .= "🗓️ Année: {$data['year']}\n\n";
        $message .= "✅ Le bulletin est maintenant disponible.\n\n";
        $message .= "👉 *Cliquez ici pour consulter :*\n";
        $message .= "{$data['url']}";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Envoyer un rappel de paiement
     */
    public function sendPaymentReminder(string $phone, array $data): array
    {
        $message = "💰 *RAPPEL DE PAIEMENT*\n\n";
        $message .= "🏫 {$data['school_name']}\n\n";
        $message .= "👤 Élève: *{$data['student_name']}*\n";
        $message .= "📚 Classe: {$data['class_name']}\n";
        $message .= "💵 Montant dû: {$data['amount']}\n";
        $message .= "📅 Échéance: {$data['due_date']}\n\n";
        $message .= "Merci de régulariser la situation.";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Envoyer une alerte d'absence
     */
    public function sendAbsenceAlert(string $phone, array $data): array
    {
        $message = "⚠️ *ALERTE ABSENCE*\n\n";
        $message .= "🏫 {$data['school_name']}\n\n";
        $message .= "👤 Élève: *{$data['student_name']}*\n";
        $message .= "📅 Date: {$data['date']}\n";
        $message .= "📚 Cours: {$data['subject']}\n\n";
        $message .= "Votre enfant a été absent aujourd'hui.";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Formater le numéro de téléphone (format international)
     */
    protected function formatPhone(string $phone): string
    {
        // Supprimer tous les caractères non numériques
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Si commence par 0, remplacer par le code pays RDC (243)
        if (str_starts_with($phone, '0')) {
            $phone = '243' . substr($phone, 1);
        }

        // Si ne commence pas par un code pays, ajouter 243 (RDC)
        if (strlen($phone) == 9) {
            $phone = '243' . $phone;
        }

        return $phone;
    }
}
