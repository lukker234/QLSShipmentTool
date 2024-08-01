<?php

namespace App\Http\Controllers;

use App\Services\OrderService;
use Barryvdh\DomPDF\Facade\Pdf;
use Exception;
use Illuminate\Http\Client\Response;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
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

    protected OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->user = config('services.qls_api.user');
        $this->password = config('services.qls_api.password');
        $this->apiBaseUrl = config('services.qls_api.base_url');
        $this->companyId = config('services.company.id');
        $this->brandId = config('services.brand.id');

        $this->orderService = $orderService;
    }

    public function index(): View
    {
        return view('shipment');
    }

    public function createShipment(Request $request): View|JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'order_nr' => 'required|string',
            'weight' => 'required|integer',
            'product_id' => 'required|integer',
            'product_combination_id' => 'required|integer',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        try {
            $orderData = $request->only(['order_nr', 'weight', 'product_id', 'product_combination_id']);
            $order = $this->orderService->getOrderData();

            $response = $this->sendShipmentRequest($order, $orderData);
            $labelData = $response->json();

            $pdfLabelPath = $this->downloadLabelPdf($labelData['data']['labels']['a6']);
            $labelImagePath = $this->convertPdfToImage($pdfLabelPath);

            $pdfPath = $this->generatePackingSlip($order, $orderData, $labelImagePath);
            $pdfUrl = route('show.packing-slip', ['filename' => basename($pdfPath)]);

            return view('shipment', ['packing_slip_url' => $pdfUrl]);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    private function sendShipmentRequest(array $order, array $orderData): Response
    {
        return Http::withBasicAuth($this->user, $this->password)
            ->post("{$this->apiBaseUrl}/company/{$this->companyId}/shipment/create", [
                'brand_id' => $this->brandId,
                'reference' => $orderData['order_nr'],
                'weight' => $orderData['weight'],
                'product_id' => $orderData['product_id'],
                'product_combination_id' => $orderData['product_combination_id'],
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

    private function generatePackingSlip(array $order, array $orderData, string $labelImagePath): string
    {
        $pdf = PDF::loadView('packing-slip', [
            'order' => $order,
            'orderData' => $orderData,
            'label' => $labelImagePath,
        ]);

        $orderId = $orderData['order_nr'] ?? $order['number'];
        $pdfFileName = 'packing-slip-' . $orderId . '.pdf';
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
