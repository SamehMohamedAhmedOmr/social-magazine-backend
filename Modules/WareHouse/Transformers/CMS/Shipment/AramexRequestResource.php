<?php

namespace Modules\WareHouse\Transformers\CMS\Shipment;

class AramexRequestResource
{
    public function setShipmentBody($client_object, $user_object, $address_object, $order_object)
    {
        $items = [];
        $item_descriptions = '';

        foreach ($order_object->orderItems as $item) {
            $items[] =
                [
                    'PackageType' => config('warehouse.shipments.aramex.package_type'),
                    'Quantity' => $item->quantity,
                    'Weight' => [
                        'Value' => $item->product && isset($item->product->weight) ? $item->product->weight : 0.1,
                        'Unit' => 'Kg',
                    ],
                    'Comments' => $item->product->currentLanguage->name ?? '',
                    'Reference' => ''
                ];

            $item_descriptions .= $item->quantity . " " . $item->product->currentLanguage->name . " ,";
        }

        $item_descriptions = trim($item_descriptions, ',');
        $item_descriptions = trim($item_descriptions, ' ');

        $total_price = $order_object->total_price + $order_object->shipping_price + $order_object->vat - $order_object->discount;
        return [
            'Shipments' => [
                'Shipment' => [
                    'Shipper' => [
                        'Reference1' => 'Ref No. ' . $order_object->id,
                        'Reference2' => '',
                        'AccountNumber' => config('warehouse.shipments.aramex.account_number'),
                        'PartyAddress' => [
                            'Line1' => config('warehouse.shipments.aramex.pickup_address.address'),
                            'Line2' => '',
                            'Line3' => '',
                            'City' => config('warehouse.shipments.aramex.pickup_address.zone'),
                            'StateOrProvinceCode' => config('warehouse.shipments.aramex.pickup_address.district'),
                            'PostCode' => '',
                            'CountryCode' => config('warehouse.shipments.aramex.account_country_code')
                        ],
                        'Contact' => [
                            'Department' => config('warehouse.shipments.aramex.contact.department'),
                            'PersonName' => config('warehouse.shipments.aramex.contact.person_name'),
                            'Title' => config('warehouse.shipments.aramex.contact.title'),
                            'CompanyName' => config('warehouse.shipments.aramex.contact.company_name'),
                            'PhoneNumber1' => config('warehouse.shipments.aramex.contact.phone'),
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => config('warehouse.shipments.aramex.contact.cell_phone'),
                            'EmailAddress' => config('warehouse.shipments.aramex.contact.email'),
                            'Type' => ''
                        ],
                    ],
                    'Consignee' => [
                        'Reference1' => $order_object->paymentMethod->payment_method == 'Cash on delivery'
                            ? 'Money Paid: 0'. config('warehouse.shipments.aramex.account_currency_code')
                            : "Money Paid: {$total_price}". config('warehouse.shipments.aramex.account_currency_code'),
                        'Reference2' => $order_object->paymentMethod->payment_method == 'Cash on delivery'
                            ? "Money Collect: {$total_price}". config('warehouse.shipments.aramex.account_currency_code')
                            : 'Money Collect: 0'. config('warehouse.shipments.aramex.account_currency_code')
                        ,
                        'AccountNumber' => '',
                        'PartyAddress' => [
                            'Line1' => $address_object->district->currentLanguage
                                ? $address_object->district->currentLanguage->name : 'Main',
                            'Line2' => $address_object->street ?? '',
                            'Line3' => $address_object->nearest_landmark ?? '',
                            'City' => isset($address_object->city) ? $address_object->city : 'Cairo',
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => config('warehouse.shipments.aramex.account_country_code')
                        ],

                        'Contact' => [
                            'Department' => '',
                            'PersonName' => $user_object->name,
                            'Title' => $address_object->district->currentLanguage
                                ? $address_object->district->currentLanguage->name : 'Main',
                            'CompanyName' => config('warehouse.shipments.aramex.contact.company_name'),
                            'PhoneNumber1' => $client_object->phone ?? '',
                            'CellPhone' => $client_object->phone ?? '',
                            'EmailAddress' => $user_object->email ?? '',
                        ],
                    ],
                    'ThirdParty' => [
                        'Reference1' => '',
                        'Reference2' => '',
                        'AccountNumber' => '',
                        'PartyAddress' => [
                            'Line1' => '',
                            'Line2' => '',
                            'Line3' => '',
                            'City' => '',
                            'StateOrProvinceCode' => '',
                            'PostCode' => '',
                            'CountryCode' => ''
                        ],
                        'Contact' => [
                            'Department' => '',
                            'PersonName' => '',
                            'Title' => '',
                            'CompanyName' => '',
                            'PhoneNumber1' => '',
                            'PhoneNumber1Ext' => '',
                            'PhoneNumber2' => '',
                            'PhoneNumber2Ext' => '',
                            'FaxNumber' => '',
                            'CellPhone' => '',
                            'EmailAddress' => '',
                            'Type' => ''
                        ],
                    ],
                    'Reference1' => '',
                    'Reference2' => '',
                    'Reference3' => '',
                    'ForeignHAWB' => '',
                    'TransportType' => 0,
                    'ShippingDateTime' => time(),
                    'DueDate' => time(),
                    'PickupLocation' => '',
                    'PickupGUID' => '',
                    'Comments' => 'Total products ' . count($items),
                    'AccountingInstrcutions' => '',
                    'OperationsInstructions' => '',
                    'Details' => [
                        'Dimensions' => [
                            'Length' => 10,
                            'Width' => 10,
                            'Height' => 10,
                            'Unit' => 'cm',
                        ],
                        'ActualWeight' => [
                            'Value' => 0.1,
                            'Unit' => 'Kg'
                        ],
                        'ProductGroup' => 'EXP',
                        'ProductType' => 'PDX',
                        'PaymentType' => 'P',
                        'PaymentOptions' => '',
                        'Services' => 'CODS',
                        'NumberOfPieces' => 1,
                        'DescriptionOfGoods' => $item_descriptions,
                        'GoodsOriginCountry' => config('warehouse.shipments.aramex.account_country_code'),
                        'CashOnDeliveryAmount' => [
                            'Value' => $total_price,
                            'CurrencyCode' => config('warehouse.shipments.aramex.account_currency_code')
                        ],
                        'InsuranceAmount' => [
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ],
                        'CollectAmount' => [
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ],
                        'CashAdditionalAmount' => [
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ],
                        'CashAdditionalAmountDescription' => '',
                        'CustomsValueAmount' => [
                            'Value' => 0,
                            'CurrencyCode' => ''
                        ],
                        'Items' => $items,
                    ],
                ],
            ],
            'ClientInfo' => [
                'AccountCountryCode' => config('warehouse.shipments.aramex.account_country_code'),
                'AccountEntity' => config('warehouse.shipments.aramex.account_entity'),
                'AccountNumber' => config('warehouse.shipments.aramex.account_number'),
                'AccountPin' => config('warehouse.shipments.aramex.account_pin'),
                'UserName' => config('warehouse.shipments.aramex.account_username'),
                'Password' => config('warehouse.shipments.aramex.account_password'),
                'Version' => config('warehouse.shipments.aramex.version')
            ],
            'Transaction' => [
                'Reference1' => '001',
                'Reference2' => '',
                'Reference3' => '',
                'Reference4' => '',
                'Reference5' => '',
            ],
            'LabelInfo' => [
                'ReportID' => 9201,
                'ReportType' => 'URL',
            ],
        ];
    }

    public function setTrackingBody($shipment_id)
    {
        return [
            'ClientInfo' => [
                'AccountCountryCode' => config('warehouse.shipments.aramex.account_country_code'),
                'AccountEntity' => config('warehouse.shipments.aramex.account_entity'),
                'AccountNumber' => config('warehouse.shipments.aramex.account_number'),
                'AccountPin' => config('warehouse.shipments.aramex.account_pin'),
                'UserName' => config('warehouse.shipments.aramex.account_username'),
                'Password' => config('warehouse.shipments.aramex.account_password'),
                'Version' => config('warehouse.shipments.aramex.version')
            ],
            'Transaction' => [
                'Reference1' => '001'
            ],
            'Shipments' => [
                $shipment_id
            ],
        ];
    }
}
