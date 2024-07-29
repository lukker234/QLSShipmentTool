<?php

namespace App\Http\Controllers;

use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use Spatie\PdfToImage\Pdf as SpatiePdf;

class ShipmentController extends Controller
{
    protected mixed $user;
    protected mixed $password;
    protected mixed $apiBaseUrl;
    protected mixed $companyId;
    protected mixed $brandId;
    protected mixed $productId;
    protected mixed $productCombinationId;

    public function __construct()
    {
        $this->user = env('QLS_API_USER');
        $this->password = env('QLS_API_PASSWORD');
        $this->apiBaseUrl = env('QLS_API_BASE_URL');
        $this->companyId = env('COMPANY_ID');
        $this->brandId = env('BRAND_ID');
        $this->productId = env('PRODUCT_ID');
        $this->productCombinationId = env('PRODUCT_COMBINATION_ID');
    }

    public function index(): View
    {
        return view('shipment');
    }

    public function createShipment(Request $request)
    {
        $order = [
            'number' => '#958201',
            'billing_address' => [
                'companyname' => null,
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
                'email' => 'email@example.com',
                'phone' => '0101234567',
            ],
            'delivery_address' => [
                'companyname' => '',
                'name' => 'John Doe',
                'street' => 'Daltonstraat',
                'housenumber' => '65',
                'address_line_2' => '',
                'zipcode' => '3316GD',
                'city' => 'Dordrecht',
                'country' => 'NL',
            ],
            'order_lines' => [
                [
                    'amount_ordered' => 2,
                    'name' => 'Jeans - Black - 36',
                    'sku' => 69205,
                    'ean' =>  '8710552295268',
                ],
                [
                    'amount_ordered' => 1,
                    'name' => 'Sjaal - Rood Oranje',
                    'sku' => 25920,
                    'ean' =>  '3059943009097',
                ]
            ]
        ];

        $response = Http::withBasicAuth($this->user, $this->password)
            ->post("{$this->apiBaseUrl}/company/{$this->companyId}/shipment/create", [
                'brand_id' => $this->brandId,
                'reference' => $order['number'],
                'weight' => 1000,
                'product_id' => $this->productId,
                'product_combination_id' => $this->productCombinationId,
                'cod_amount' => 0,
                'piece_total' => 1,
                'receiver_contact' => [
                    'companyname' => $order['delivery_address']['companyname'],
                    'name' => $order['delivery_address']['name'],
                    'street' => $order['delivery_address']['street'],
                    'housenumber' => $order['delivery_address']['housenumber'],
                    'postalcode' => $order['delivery_address']['zipcode'],
                    'locality' => $order['delivery_address']['city'],
                    'country' => $order['delivery_address']['country'],
                    'email' => $order['billing_address']['email'],
                ]
            ]);

        $labelData = $response->json();

        // Download het PDF verzendlabel vanaf de URL
        $pdfLabelUrl = $labelData['data']['labels']['a6'];
        $pdfContent = file_get_contents($pdfLabelUrl);

        $pdfLabelPath = storage_path('app/label.pdf');
        file_put_contents($pdfLabelPath, $pdfContent);

        try {
            $pdf = new SpatiePdf($pdfLabelPath);
            $pdf->saveImage(storage_path('app/label.png'));
            $labelImagePath = storage_path('app/label.png');
        } catch (Exception $e) {
            return response()->json(['error' => 'Could not convert PDF to image: ' . $e->getMessage()], 500);
        }

        $pdf = PDF::loadView('packing-slip', ['order' => $order, 'label' => $labelImagePath]);
        return $pdf->download('packing-slip.pdf');
    }
}
