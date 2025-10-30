<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Finance\Payment;
use App\Models\Finance\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class FinanceController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function dashboard()
    {
        // Rediriger directement vers la page des paiements
        return redirect()->route('student.finance.payments');
    }

    public function payments(Request $request)
    {
        try {
            $user = auth()->user();
            
            // Récupérer l'étudiant (devrait exister grâce au middleware)
            $student = $user->student;
            
            if (!$student) {
                \Log::error("L'utilisateur {$user->id} n'a pas d'enregistrement étudiant");
                return redirect()->route('dashboard')
                    ->with('error', 'Erreur de configuration du compte étudiant. Veuillez contacter l\'administration.');
            }

            // Récupérer les années disponibles pour le filtre
            $years = \App\Models\PaymentRecord::selectRaw('YEAR(created_at) as year')
                ->where('student_id', $student->id)
                ->distinct()
                ->orderBy('year', 'desc')
                ->pluck('year');

            // Si aucune année n'est trouvée, utiliser l'année en cours
            if ($years->isEmpty()) {
                $years = collect([date('Y')]);
            }

            // Récupérer les paramètres de filtre
            $selected_year = $request->input('year');
            $selected_status = $request->input('status');

            // Construire la requête des paiements
            $query = $student->paymentRecords()
                ->with('payment')
                ->orderBy('created_at', 'desc');

            // Appliquer les filtres
            if ($selected_year) {
                $query->whereYear('created_at', $selected_year);
            }

            if ($selected_status) {
                if ($selected_status === 'paid') {
                    $query->where('balance', '<=', 0);
                } elseif ($selected_status === 'partial') {
                    $query->where('amt_paid', '>', 0)
                          ->where('balance', '>', 0);
                } elseif ($selected_status === 'unpaid') {
                    $query->where('amt_paid', '<=', 0);
                }
            }

            $payments = $query->paginate(10);

            return view('pages.student.finance.payments', [
                'payments' => $payments,
                'years' => $years,
                'selected_year' => $selected_year,
                'selected_status' => $selected_status
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans FinanceController@payments: ' . $e->getMessage());
            return redirect()->route('dashboard')
                ->with('error', 'Une erreur est survenue lors de la récupération des paiements.');
        }
    }

    public function paymentsList()
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $payments = $student->payments()
            ->with('receipt')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('pages.student.finance.payments', compact('payments'));
    }

    public function receipts(Request $request)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        // Récupérer les années disponibles depuis les reçus
        $years = \App\Models\Receipt::selectRaw('YEAR(created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Si aucune année n'est trouvée, utiliser l'année en cours
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        // Filtrer par année si spécifié
        $query = $student->receipts()
            ->with('payment')
            ->orderBy('created_at', 'desc');

        if ($request->has('year') && $request->year != '') {
            $query->whereYear('receipts.created_at', $request->year);
        }

        if ($request->has('month') && $request->month != '') {
            $query->whereMonth('receipts.created_at', $request->month);
        }

        $receipts = $query->paginate(15);

        return view('pages.student.finance.receipts', [
            'receipts' => $receipts,
            'years' => $years,
            'selected_year' => $request->input('year'),
            'selected_month' => $request->input('month')
        ]);
    }

    public function showReceipt($id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $receipt = $student->receipts()
            ->with('payment')
            ->findOrFail($id);

        return view('pages.student.finance.show_receipt', compact('receipt'));
    }
}
