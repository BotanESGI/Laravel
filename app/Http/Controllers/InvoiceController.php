<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Mpdf\Mpdf;

class InvoiceController extends Controller
{
    public function generateInvoice(Order $order)
    {
        // Charger les orderItems associés à la commande
        $order->load('orderItems.product');

        // Vérifier si des produits sont associés à la commande
        if ($order->orderItems->isEmpty()) {
            return response('No products found for this order.', 404);
        }

        // Spécifier le répertoire temporaire pour mpdf
        $config = [
            'tempDir' => storage_path('mpdf')
        ];

        // Création d'un nouveau PDF avec configuration
        $mpdf = new Mpdf($config);

        // Contenu de la facture
        $html = view('invoice', compact('order'))->render();

        // Écrire le contenu dans le PDF
        $mpdf->WriteHTML($html);

        // Télécharger le fichier PDF
        return $mpdf->Output('invoice_' . $order->id . '.pdf', 'I');
    }
}
