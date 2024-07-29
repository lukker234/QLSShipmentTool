<?php

namespace App\Services;

class OrderService
{
    private array $order = [
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
                'ean' => '8710552295268',
            ],
            [
                'amount_ordered' => 1,
                'name' => 'Sjaal - Rood Oranje',
                'sku' => 25920,
                'ean' => '3059943009097',
            ]
        ]
    ];

    /**
     * Get the order data
     *
     * @return array
     */
    public function getOrderData(): array
    {
        return $this->order;
    }
}
