<?php

use Saloon\Enums\Method;
use Saloon\Http\Faking\MockClient;
use Saloon\Http\Faking\MockResponse;
use SmartDato\Olc\Exceptions\GenericOlcException;
use SmartDato\Olc\Olc;
use SmartDato\Olc\Requests\DeleteShipmentLabelRequest;

it('deletes the label of a shipment by its key', function () {
    $olc = new Olc(url: 'https://olc.test/', token: '...');
    $mockClient = new MockClient([
        DeleteShipmentLabelRequest::class => MockResponse::make('', 204),
    ]);
    $olc->connector->withMockClient($mockClient);

    $olc->deleteLabel('OLS202400000001');

    $mockClient->assertSent(fn ($request): bool => $request instanceof DeleteShipmentLabelRequest
        && $request->getMethod() === Method::DELETE
        && $request->resolveEndpoint() === '/services/v2/customers/shipments/sk/OLS202400000001/labels');
});

it('reports why OLC refused to delete a label', function () {
    $olc = new Olc(url: 'https://olc.test/', token: '...');
    $olc->connector->withMockClient(new MockClient([
        DeleteShipmentLabelRequest::class => MockResponse::make(['message' => 'Label cannot be deleted. The shipment was already picked up.'], 422),
    ]));

    expect(fn () => $olc->deleteLabel('OLS202400000001'))
        ->toThrow(GenericOlcException::class, 'Label cannot be deleted. The shipment was already picked up.');
});
