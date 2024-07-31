<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use RuntimeException;
use Spatie\PdfToImage\Pdf as SpatiePdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class ShipmentController extends Controller
{
    protected mixed $user;
    protected mixed $password;
    protected mixed $apiBaseUrl;
    protected mixed $companyId;
    protected mixed $brandId;
    protected mixed $productId;
    protected mixed $productCombinationId;

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->user = env('QLS_API_USER');
        $this->password = env('QLS_API_PASSWORD');
        $this->apiBaseUrl = env('QLS_API_BASE_URL');
        $this->companyId = env('COMPANY_ID');
        $this->brandId = env('BRAND_ID');
        $this->productId = env('PRODUCT_ID');
        $this->productCombinationId = env('PRODUCT_COMBINATION_ID');

        $this->orderService = $orderService;
    }

    public function index(): View
    {
        return view('shipment');
    }

    public function createShipment(): View|JsonResponse
    {
        try {
            $order = $this->orderService->getOrderData();
            $response = $this->sendShipmentRequest($order);
            $labelData = $response->json();

            $pdfLabelPath = $this->downloadLabelPdf($labelData['data']['labels']['a6']);
            $labelImagePath = $this->convertPdfToImage($pdfLabelPath);

            $pdfPath = $this->generatePackingSlip($order, $labelImagePath);
            $pdfUrl = route('show.packing-slip', ['filename' => basename($pdfPath)]);

            return view('shipment', ['packing_slip_url' => $pdfUrl]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function sendShipmentRequest(array $order): \Illuminate\Http\Client\Response
    {
        return Http::withBasicAuth($this->user, $this->password)
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
                ],
            ]);
    }

    private function downloadLabelPdf(string $url): string
    {
        $pdfContent = file_get_contents($url);
        $pdfLabelPath = storage_path('app/label.pdf');
        file_put_contents($pdfLabelPath, $pdfContent);

        return $pdfLabelPath;
    }

    private function convertPdfToImage(string $pdfLabelPath): string
    {
        try {
            $pdf = new SpatiePdf($pdfLabelPath);
            $pdf->saveImage(storage_path('app/label.png'));

            return storage_path('app/label.png');
        } catch (Exception $e) {
            throw new RuntimeException('Could not convert PDF to image: ' . $e->getMessage());
        }
    }

    private function generatePackingSlip(array $order, string $labelImagePath): string
    {
        $pdf = PDF::loadView('packing-slip', [
            'order' => $order,
            'label' => $labelImagePath,
        ]);

        $pdfFileName = 'packing-slip-' . time() . '.pdf';
        $pdfPath = Storage::disk('public')->path($pdfFileName);
        $pdf->save($pdfPath);

        return $pdfPath;
    }

    public function showPackingSlip($filename): BinaryFileResponse
    {
        $path = Storage::disk('public')->path($filename);

        if (!Storage::disk('public')->exists($filename)) {
            abort(404);
        }

        return response()->file($path);
    }

}
