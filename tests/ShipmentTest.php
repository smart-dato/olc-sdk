<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use SmartDato\Olc\DataObjects\AddressObject;
use SmartDato\Olc\DataObjects\ContentObject;
use SmartDato\Olc\DataObjects\ContentObjectCollection;
use SmartDato\Olc\DataObjects\ParcelObject;
use SmartDato\Olc\DataObjects\ParcelObjectCollection;
use SmartDato\Olc\DataObjects\ShipmentObject;
use SmartDato\Olc\OlcConnector;
use SmartDato\Olc\Requests\CreateShipmentRequest;
use SmartDato\Olc\Requests\GetShipmentLabelRequest;

it('can create a new shipment', function () {
    $connector = new OlcConnector(
        url: 'https://olc.test/',
        token: '...',
    );

    $connector->withMockClient(new MockClient([
        CreateShipmentRequest::class => MockResponse::fixture('shipment/create_multicollo'),
    ]));

    $response = $connector->send(
        new CreateShipmentRequest(
            new ShipmentObject(
                shipmentType: 'PARCEL',
                shippingService: 'EC',
                pickupAddress: new AddressObject(
                    warehouse: 'WH_1'
                ),
                deliveryAddress: new AddressObject(
                    personName: fake()->name,
                    companyName: fake()->company,
                    street: fake()->address,
                    city: fake()->city,
                    zipcode: fake()->postcode,
                    countryCode: fake()->countryCode,
                ),
                parcels: (new ParcelObjectCollection)
                    ->add(new ParcelObject(
                        weight: 2.5
                    ))
                    ->add(new ParcelObject(
                        weight: 1.23
                    ))
            )
        )
    );

    expect($response->status())
        ->toBe(201);

    ray($response->json());
});

it('can collect a label', function () {
    $connector = new OlcConnector(
        url: 'https://olc.test/',
        token: '...',
    );

    $connector->withMockClient(new MockClient([
        GetShipmentLabelRequest::class => MockResponse::fixture('shipment/label_multicollo'),
    ]));

    $response = $connector->send(
        new GetShipmentLabelRequest(
            value: 'OLS202400000001')
    );

    expect($response->status())
        ->toBe(200);

    ray($response->json());
});

it('includes content in the built payload', function () {
    $shipment = new ShipmentObject(
        shipmentType: 'PARCEL',
        shippingService: 'EC',
        pickupAddress: new AddressObject(warehouse: 'WH_1'),
        deliveryAddress: new AddressObject(
            personName: 'John Doe',
            street: '123 Main St',
            city: 'Anytown',
            zipcode: '12345',
            countryCode: 'DE',
        ),
        parcels: (new ParcelObjectCollection)->add(new ParcelObject(weight: 2.5)),
        content: (new ContentObjectCollection)
            ->add(new ContentObject(
                description: 'Cotton shirt',
                hsCode: '6109.10',
                quantity: 2,
                unitValue: 12.50,
                netWeight: 0.2,
                manufacturerCountry: 'CN',
                currency: 'EUR',
                invoiceNumber: 'INV-1',
                invoiceDate: '2026-09-23',
            )),
    );

    expect($shipment->build())
        ->toHaveKey('content')
        ->and($shipment->build()['content'])
        ->toBe([[
            'description' => 'Cotton shirt',
            'hsCode' => '6109.10',
            'quantity' => 2.0,
            'unitValue' => 12.50,
            'netWeight' => 0.2,
            'manufacturerCountry' => 'CN',
            'currency' => 'EUR',
            'invoice' => [
                'date' => '2026-09-23',
                'number' => 'INV-1',
            ],
        ]]);
});

it('omits content when none is given', function () {
    $shipment = new ShipmentObject(
        shipmentType: 'PARCEL',
        shippingService: 'EC',
        pickupAddress: new AddressObject(warehouse: 'WH_1'),
        deliveryAddress: new AddressObject(
            personName: 'John Doe',
            street: '123 Main St',
            city: 'Anytown',
            zipcode: '12345',
            countryCode: 'DE',
        ),
        parcels: (new ParcelObjectCollection)->add(new ParcelObject(weight: 2.5)),
    );

    expect($shipment->build())->not->toHaveKey('content');
});
