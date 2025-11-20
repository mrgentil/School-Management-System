<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\PaymentRecord;
use App\Models\Receipt;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Barryvdh\DomPDF\Facade\Pdf;

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
            $years = \App\Models\PaymentRecord::selectRaw('year')
                ->where('student_id', $user->id)
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
            $query = \App\Models\PaymentRecord::where('student_id', $user->id)
                ->with('payment')
                ->orderBy('created_at', 'desc');

            // Appliquer les filtres
            if ($selected_year) {
                $query->where('year', $selected_year);
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
        $userId = auth()->id();
        
        // Récupérer les années disponibles depuis les reçus de cet étudiant
        $years = \App\Models\Receipt::join('payment_records', 'receipts.pr_id', '=', 'payment_records.id')
            ->where('payment_records.student_id', $userId)
            ->selectRaw('YEAR(receipts.created_at) as year')
            ->distinct()
            ->orderBy('year', 'desc')
            ->pluck('year');

        // Si aucune année n'est trouvée, utiliser l'année en cours
        if ($years->isEmpty()) {
            $years = collect([date('Y')]);
        }

        // Construire la requête des reçus
        $query = \App\Models\Receipt::join('payment_records', 'receipts.pr_id', '=', 'payment_records.id')
            ->where('payment_records.student_id', $userId)
            ->with(['paymentRecord.payment'])
            ->select('receipts.*')
            ->orderBy('receipts.created_at', 'desc');

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

    public function showReceipt($id = null)
    {
        // Si l'ID est vide, le récupérer depuis l'URL directement
        if (empty($id)) {
            $segments = request()->segments();
            $id = end($segments); // Dernier segment de l'URL
        }
        
        $userId = auth()->id();
        
        // Récupérer le reçu en vérifiant qu'il appartient bien à l'étudiant
        $receipt = \App\Models\Receipt::join('payment_records', 'receipts.pr_id', '=', 'payment_records.id')
            ->where('payment_records.student_id', $userId)
            ->where('receipts.id', $id)
            ->with('paymentRecord.payment')
            ->select('receipts.*')
            ->firstOrFail();

        return view('pages.student.finance.receipt_show', compact('receipt'));
    }

    /**
     * Télécharger un reçu en PDF
     */
    public function downloadReceipt($id = null)
    {
        // Si l'ID est vide, le récupérer depuis l'URL
        if (empty($id)) {
            $segments = request()->segments();
            // L'ID est l'avant-dernier segment (avant "download")
            $id = $segments[count($segments) - 2];
        }
        
        $userId = auth()->id();
        
        // Récupérer le reçu
        $receipt = \App\Models\Receipt::join('payment_records', 'receipts.pr_id', '=', 'payment_records.id')
            ->where('payment_records.student_id', $userId)
            ->where('receipts.id', $id)
            ->with(['paymentRecord.payment', 'paymentRecord.student.student_record.my_class.academicSection', 'paymentRecord.student.student_record.my_class.option', 'paymentRecord.student.student_record.section'])
            ->select('receipts.*')
            ->firstOrFail();

        // Générer le nom du fichier PDF
        $fileName = 'Recu_' . $receipt->ref_no . '_' . date('Y-m-d') . '.pdf';

        // Générer et télécharger le PDF
        $pdf = PDF::loadView('pages.student.finance.receipt_pdf', compact('receipt'));
        
        return $pdf->download($fileName);
    }

    /**
     * Imprimer un reçu
     */
    public function printReceipt($id = null)
    {
        // Si l'ID est vide, le récupérer depuis l'URL
        if (empty($id)) {
            $segments = request()->segments();
            // L'ID est l'avant-dernier segment (avant "print")
            $id = $segments[count($segments) - 2];
        }
        
        $userId = auth()->id();
        
        // Récupérer le reçu
        $receipt = \App\Models\Receipt::join('payment_records', 'receipts.pr_id', '=', 'payment_records.id')
            ->where('payment_records.student_id', $userId)
            ->where('receipts.id', $id)
            ->with('paymentRecord.payment')
            ->select('receipts.*')
            ->firstOrFail();

        $student = auth()->user()->student;

        // Afficher la vue d'impression
        return view('pages.student.finance.receipt_show', compact('receipt', 'student'));
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
