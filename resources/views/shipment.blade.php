<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    @vite('resources/css/app.css')
    <title>Shipment Tool</title>
</head>
<body class="bg-gradient-to-r from-blue-500 to-blue-600 min-h-screen flex items-center justify-center p-4">
<div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden">
    <div class="bg-gray-100 p-6 border-b border-gray-200">
        <h1 class="text-3xl font-bold text-gray-800">Create Shipment</h1>
    </div>
    <div class="p-6">
        <form id="shipmentForm" action="{{ route('create.shipment') }}" method="POST" class="space-y-6">
            @csrf
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="order_nr">Order number</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="order_nr" type="number" name="order_nr" placeholder="Enter order number (958201)" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="weight">Weight (grams)</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="weight" type="number" name="weight" placeholder="Enter weight" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="product_id">Product ID (shipment category id)</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="product_id" type="number" name="product_id" value="2" required>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-1" for="product_combination_id">Product Combination ID (shipment category options id)</label>
                <input class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500" id="product_combination_id" type="number" name="product_combination_id" value="3" required>
            </div>
            <div>
                <button id="submitButton" type="submit" class="w-full bg-blue-600 text-white font-bold py-2 px-4 rounded-md transition duration-150 ease-in-out hover:bg-white hover:text-blue-600 border-2 border-transparent hover:border-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50 flex items-center justify-center">
                    <span id="buttonText">Create Shipment</span>
                    <svg id="loadingIcon" class="animate-spin ml-3 h-5 w-5 text-current hidden" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                        <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                        <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                </button>
            </div>
        </form>

        @if(isset($packing_slip_url))
            <div class="mt-8">
                <h2 class="text-2xl font-bold text-gray-800 mb-4">Packing Slip</h2>
                <div class="border border-gray-200 rounded-lg overflow-hidden">
                    <iframe src="{{ $packing_slip_url }}" width="100%" height="600px" class="border-0"></iframe>
                </div>
            </div>
        @endif
    </div>
</div>

<script>
    document.getElementById('shipmentForm').addEventListener('submit', function() {
        const button = document.getElementById('submitButton');
        const buttonText = document.getElementById('buttonText');
        const loadingIcon = document.getElementById('loadingIcon');

        button.disabled = true;
        buttonText.textContent = 'Creating Shipment...';
        loadingIcon.classList.remove('hidden');
    });
</script>
</body>
</html>
