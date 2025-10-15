<?php

namespace App\Http\Controllers;

use App\Models\Departement;
use App\Models\Employer;
use App\Models\User;
use Illuminate\Http\Request;
use App\Models\Configuration;
use Carbon\Carbon;
use Exception;



class AppController extends Controller
{
    public function index()
    {
        $totalDepartements = Departement::count();
        $totalEmployers = Employer::count();
        $totalAdministrateurs = User::count();
        $defaultPaymentDate = null;
        $paymentNotification = "";

        $currentDate = Carbon::now()->day;

        // Récupère la configuration de type PAYMENT_DATE
        $defaultPaymentDate = Configuration::where('type', 'PAYMENT_DATE')->first();

        if ($defaultPaymentDate) {
            $defaultPaymentDate = $defaultPaymentDate->value;
            $convertedPaymentDate = intval($defaultPaymentDate);

            if ($currentDate < $convertedPaymentDate) {
                $paymentNotification = "Le paiement doit avoir lieu le " . $defaultPaymentDate . " de ce mois.";
            } else {
                $nextMonth = Carbon::now()->addMonth();
                $nextMonthName = $nextMonth->translatedFormat('F'); // Pour avoir le mois en français

                $paymentNotification = "Le paiement doit avoir lieu le " . $defaultPaymentDate . " du mois de " . $nextMonthName . ".";
            }
        }

        return view('dashboard', compact(
            'totalDepartements',
            'totalEmployers',
            'totalAdministrateurs',
            'paymentNotification'
        ));
    }

    // API Methods
    public function apiStats()
    {
        try {
            $stats = [
                'total_departements' => Departement::count(),
                'total_employers' => Employer::count(),
                'total_administrateurs' => User::count(),
                'payment_notification' => $this->getPaymentNotification()
            ];

            return response()->json([
                'success' => true,
                'data' => $stats,
                'message' => 'Statistiques récupérées avec succès'
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de la récupération des statistiques: ' . $e->getMessage()
            ], 500);
        }
    }

    private function getPaymentNotification()
    {
        $defaultPaymentDate = null;
        $paymentNotification = "";

        $currentDate = Carbon::now()->day;

        // Récupère la configuration de type PAYMENT_DATE
        $defaultPaymentDate = Configuration::where('type', 'PAYMENT_DATE')->first();

        if ($defaultPaymentDate) {
            $defaultPaymentDate = $defaultPaymentDate->value;
            $convertedPaymentDate = intval($defaultPaymentDate);

            if ($currentDate < $convertedPaymentDate) {
                $paymentNotification = "Le paiement doit avoir lieu le " . $defaultPaymentDate . " de ce mois.";
            } else {
                $nextMonth = Carbon::now()->addMonth();
                $nextMonthName = $nextMonth->translatedFormat('F'); // Pour avoir le mois en français

                $paymentNotification = "Le paiement doit avoir lieu le " . $defaultPaymentDate . " du mois de " . $nextMonthName . ".";
            }
        }

        return $paymentNotification;
    }
}
