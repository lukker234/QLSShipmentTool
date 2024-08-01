# QLSShipmentTool

QLSShipmentTool is a Laravel-based application designed to streamline the creation and management of shipments using the QLS API. This tool allows users to create shipments, download shipping labels, and generate packing slips.

## Features

- Create shipments with specified product IDs and combinations.
- Download and convert shipping labels from PDF to PNG.
- Generate packing slips with order details and shipping labels.

## Requirements

- PHP 8.0 or higher
- Laravel 9.x
- Composer
- Node.js and NPM
- A QLS API account with the necessary credentials

## Installation

1. Clone the repository:
    ```bash
    git clone https://github.com/lukker234/QLSShipmentTool.git
    cd QLSShipmentTool
    ```

2. Install dependencies:
    ```bash
    composer install
    npm install
    ```

3. Copy the example environment file and configure it:
    ```bash
    cp .env.example .env
    ```

4. Generate an application key:
    ```bash
    php artisan key:generate
    ```

5. Update the `.env` file with your QLS API credentials and other necessary configurations:
    ```dotenv
    QLS_API_USER=your_api_user
    QLS_API_PASSWORD=your_api_password
    QLS_API_BASE_URL=https://api.qls.com
    COMPANY_ID=your_company_id
    BRAND_ID=your_brand_id
    ```

6. Run the migrations:
    ```bash
    php artisan migrate
    ```

7. Build the frontend assets:
    ```bash
    npm run build
    ```

## Usage

To start the development server, run:
```bash
php artisan serve
```

Access the application at `http://localhost:8000`.

## Configuration

Ensure the following environment variables are set in your `.env` file:

- `QLS_API_USER`: Your QLS API username.
- `QLS_API_PASSWORD`: Your QLS API password.
- `QLS_API_BASE_URL`: Base URL for the QLS API.
- `COMPANY_ID`: Your company ID registered with QLS.
- `BRAND_ID`: Your brand ID registered with QLS.

## Contributing

Contributions are welcome! Please follow these steps:

1. Fork the repository.
2. Create your feature branch (`git checkout -b feature/new-feature`).
3. Commit your changes (`git commit -am 'Add new feature'`).
4. Push to the branch (`git push origin feature/new-feature`).
5. Create a new Pull Request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.

## Acknowledgments

- [Laravel](https://laravel.com)
- [Spatie PDF to Image](https://github.com/spatie/pdf-to-image)
- [Barryvdh DomPDF](https://github.com/barryvdh/laravel-dompdf)
