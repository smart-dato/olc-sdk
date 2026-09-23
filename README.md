# OLC Laravel SDK

[![Latest Version on Packagist](https://img.shields.io/packagist/v/smart-dato/olc-sdk.svg?style=flat-square)](https://packagist.org/packages/smart-dato/olc-sdk)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/smart-dato/olc-sdk/run-tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/smart-dato/olc-sdk/actions?query=workflow%3Arun-tests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/smart-dato/olc-sdk/code-style.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/smart-dato/olc-sdk/actions?query=workflow%3A%22Code+style%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/smart-dato/olc-sdk.svg?style=flat-square)](https://packagist.org/packages/smart-dato/olc-sdk)

A Laravel package for the OLC v2 customer API, built on [Saloon](https://docs.saloon.dev). Create shipments, fetch labels and read tracking events.

For plain PHP projects without Laravel, use [smart-dato/php-olc-sdk](https://github.com/smart-dato/php-olc-sdk).

## Requirements

- PHP 8.3+
- Laravel 10 – 13

## Installation

```bash
composer require smart-dato/olc-sdk
```

Publish the config file:

```bash
php artisan vendor:publish --tag="olc-sdk-config"
```

```dotenv
OLC_URL=https://your-olc-host
OLC_TOKEN=your-api-token
```

> **Pass the URL and token explicitly for now.** The config is published as `olc-sdk`, but the connector reads `config('olc.base_url')` and `config('olc.token')`, so the config fallback — and therefore the `Olc` facade — does not pick these values up.

## Usage

```php
use SmartDato\Olc\Olc;

$olc = new Olc(
    url: config('olc-sdk.base_url'),
    token: config('olc-sdk.token'),
);
```

### Create a shipment

```php
use SmartDato\Olc\DataObjects\AddressObject;
use SmartDato\Olc\DataObjects\ContentObject;
use SmartDato\Olc\DataObjects\ContentObjectCollection;
use SmartDato\Olc\DataObjects\ParcelObject;
use SmartDato\Olc\DataObjects\ParcelObjectCollection;
use SmartDato\Olc\DataObjects\ShipmentObject;

$shipment = new ShipmentObject(
    shipmentType: 'PARCEL',
    shippingService: 'EC',
    pickupAddress: new AddressObject(warehouse: 'WH_1'),
    deliveryAddress: new AddressObject(
        personName: 'John Doe',
        companyName: 'Acme Inc.',
        street: '123 Main St',
        city: 'Anytown',
        zipcode: '12345',
        countryCode: 'DE',
    ),
    parcels: (new ParcelObjectCollection)
        ->add(new ParcelObject(weight: 2.5, width: 10, height: 10, length: 10))
        ->add(new ParcelObject(weight: 1.23)),
    reference_1: 'order-1001',
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

$data = $olc->createShipment($shipment);
```

`shipmentType` and `shippingService` must be keys configured for your OLC account; `PARCEL` and `EC` are the values used in the tests.

`ShipmentObject` also accepts `reference_2`, `comment`, `products` (`ProductObject` — delivery options such as `b2cDelivery` or `scheduledDelivery`), `cashOnDelivery` (`CashOnDeliveryObject`) and `carrierObject` (`CarrierObject`). Null values are left out of the request.

`content` is optional and describes the goods, e.g. for customs. Each `ContentObject` maps to one item; every field is optional and only the ones you set are sent. `invoiceDate`, `invoiceNumber` and `invoiceImage` are grouped into an `invoice` object.

`createShipment()` returns the `data` field of the response.

### Labels and tracking

Both look a shipment up by its OLC shipment key by default:

```php
$label = $olc->getLabel('OLS000000000001');          // the `file` field of the response
$events = $olc->getTrackingEvents('OLS000000000001'); // the decoded JSON response
```

To look up by one of your own references, send the request through the connector with a `ShipmentReferenceTyp` (`KEY`, `REFERENCE1`, `REFERENCE2`):

```php
use SmartDato\Olc\Enums\ShipmentReferenceTyp;
use SmartDato\Olc\Requests\GetShipmentLabelRequest;

$response = $olc->connector->send(
    new GetShipmentLabelRequest('order-1001', ShipmentReferenceTyp::REFERENCE1)
);
```

### Errors

`createShipment()`, `getLabel()` and `getTrackingEvents()` throw `GenericOlcException` with the response body when OLC returns an error status.

## Testing

```bash
composer test
```

The suite replays recorded responses with Saloon's `MockClient`, so it needs no credentials or network access.

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [SmartDato](https://github.com/smart-dato)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
