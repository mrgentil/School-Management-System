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

    /**
     * Télécharger un reçu en PDF
     */
    public function downloadReceipt($id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $receipt = $student->receipts()
            ->with(['payment', 'student.user'])
            ->findOrFail($id);

        // Générer le PDF
        $pdf = \PDF::loadView('pages.student.finance.receipt_pdf', compact('receipt'));
        
        // Nom du fichier
        $filename = 'recu_' . $receipt->id . '_' . date('Y-m-d') . '.pdf';
        
        // Télécharger le PDF
        return $pdf->download($filename);
    }

    /**
     * Imprimer un reçu
     */
    public function printReceipt($id)
    {
        $student = auth()->user()->student;
        
        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $receipt = $student->receipts()
            ->with(['payment', 'student.user'])
            ->findOrFail($id);

        // Afficher la vue d'impression
        return view('pages.student.finance.receipt_print', compact('receipt'));
    }

    /**
     * Imprimer la liste des paiements
     */
    public function printPayments(Request $request)
    {
        try {
            $user = auth()->user();

            // Récupérer l'étudiant (devrait exister grâce au middleware)
            $student = $user->student;

            if (!$student) {
                \Log::error("L'utilisateur {$user->id} n'a pas d'enregistrement étudiant");
                return redirect()->route('student.finance.payments')
                    ->with('error', 'Erreur de configuration du compte étudiant. Veuillez contacter l\'administration.');
            }

            // Récupérer les paramètres de filtre
            $selected_year = $request->input('year');
            $selected_status = $request->input('status');
            $range = $request->input('range', 'all');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

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

            // Appliquer les filtres de période
            if ($range === 'current_year') {
                $query->whereYear('created_at', date('Y'));
            } elseif ($range === 'last_year') {
                $query->whereYear('created_at', date('Y') - 1);
            } elseif ($range === 'custom' && $start_date && $end_date) {
                $query->whereBetween('created_at', [$start_date, $end_date]);
            }

            $payments = $query->get();

            return view('pages.student.finance.payments_print', [
                'payments' => $payments,
                'student' => $student,
                'range' => $range,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'selected_year' => $selected_year,
                'selected_status' => $selected_status
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans FinanceController@printPayments: ' . $e->getMessage());
            return redirect()->route('student.finance.payments')
                ->with('error', 'Une erreur est survenue lors de la génération de l\'impression.');
        }
    }

    /**
     * Imprimer la liste des reçus
     */
    public function printReceipts(Request $request)
    {
        try {
            $user = auth()->user();

            // Récupérer l'étudiant (devrait exister grâce au middleware)
            $student = $user->student;

            if (!$student) {
                \Log::error("L'utilisateur {$user->id} n'a pas d'enregistrement étudiant");
                return redirect()->route('student.finance.receipts')
                    ->with('error', 'Erreur de configuration du compte étudiant. Veuillez contacter l\'administration.');
            }

            // Récupérer les paramètres de filtre
            $selected_year = $request->input('year');
            $selected_month = $request->input('month');
            $range = $request->input('range', 'all');
            $status = $request->input('status', 'all');
            $start_date = $request->input('start_date');
            $end_date = $request->input('end_date');

            // Construire la requête des reçus
            $query = $student->receipts()
                ->with(['paymentRecord.payment'])
                ->orderBy('created_at', 'desc');

            // Appliquer les filtres
            if ($selected_year) {
                $query->whereYear('receipts.created_at', $selected_year);
            }

            if ($selected_month) {
                $query->whereMonth('receipts.created_at', $selected_month);
            }

            if ($status !== 'all') {
                $query->where('status', $status);
            }

            // Appliquer les filtres de période
            if ($range === 'current_month') {
                $query->whereYear('receipts.created_at', date('Y'))
                      ->whereMonth('receipts.created_at', date('m'));
            } elseif ($range === 'last_month') {
                $lastMonth = date('m', strtotime('last month'));
                $lastMonthYear = date('Y', strtotime('last month'));
                $query->whereYear('receipts.created_at', $lastMonthYear)
                      ->whereMonth('receipts.created_at', $lastMonth);
            } elseif ($range === 'current_year') {
                $query->whereYear('receipts.created_at', date('Y'));
            } elseif ($range === 'custom' && $start_date && $end_date) {
                $query->whereBetween('receipts.created_at', [$start_date, $end_date]);
            }

            $receipts = $query->get();

            return view('pages.student.finance.receipts_print', [
                'receipts' => $receipts,
                'student' => $student,
                'range' => $range,
                'status' => $status,
                'start_date' => $start_date,
                'end_date' => $end_date,
                'selected_year' => $selected_year,
                'selected_month' => $selected_month
            ]);

        } catch (\Exception $e) {
            Log::error('Erreur dans FinanceController@printReceipts: ' . $e->getMessage());
            return redirect()->route('student.finance.receipts')
                ->with('error', 'Une erreur est survenue lors de la génération de l\'impression.');
        }
    }

    /**
     * Télécharger tous les reçus de l'année en PDF
     */
    public function downloadAllReceipts(Request $request)
    {
        $student = auth()->user()->student;

        if (!$student) {
            return redirect()->route('dashboard')->with('error', 'Aucun profil étudiant trouvé pour votre compte.');
        }

        $year = $request->input('year', date('Y'));

        $receipts = $student->receipts()
            ->with(['payment'])
            ->whereYear('created_at', $year)
            ->orderBy('created_at', 'desc')
            ->get();

        if ($receipts->isEmpty()) {
            return back()->with('error', 'Aucun reçu trouvé pour cette année.');
        }

        // Générer le PDF avec tous les reçus
        $pdf = \PDF::loadView('pages.student.finance.receipts_all_pdf', compact('receipts', 'year', 'student'));

        // Nom du fichier
        $filename = 'reçus_' . $year . '_' . $student->user->name . '.pdf';

        // Télécharger le PDF
        return $pdf->download($filename);
    }
}
