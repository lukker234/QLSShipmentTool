<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="{{ mix('css/app.css') }}" rel="stylesheet">
    <title>Shipment Tool</title>
</head>
<body class="bg-gray-100 h-screen antialiased leading-none">
<div class="flex justify-center items-center h-full">
    <div class="bg-white p-8 rounded-lg shadow-lg w-1/2">
        <h1 class="text-2xl font-bold mb-4">Create Shipment</h1>
        <form action="{{ route('create.shipment') }}" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="weight">Weight (grams)</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="weight" type="number" name="weight" placeholder="Enter weight" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="product_id">Product ID</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="product_id" type="number" name="product_id" value="2" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2" for="product_combination_id">Product Combination ID</label>
                <input class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" id="product_combination_id" type="number" name="product_combination_id" value="3" required>
            </div>
            <div class="flex items-center justify-between">
                <button class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline" type="submit">
                    Create Shipment
                </button>
            </div>
        </form>
    </div>
</div>
</body>
</html>
