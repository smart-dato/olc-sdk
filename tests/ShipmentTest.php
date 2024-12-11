<?php

use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use SmartDato\Olc\DataObjects\AddressObject;
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
        CreateShipmentRequest::class => MockResponse::fixture('shipment.create.multicollo'),
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
        GetShipmentLabelRequest::class => MockResponse::fixture('shipment.label.multicollo'),
    ]));

    $response = $connector->send(
        new GetShipmentLabelRequest(
            value: 'OLS202400000001')
    );

    expect($response->status())
        ->toBe(200);

    ray($response->json());
});
