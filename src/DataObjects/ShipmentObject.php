<?php

namespace SmartDato\Olc\DataObjects;

use Illuminate\Support\Collection;
use SmartDato\Olc\Contracts\DataObject;

class ShipmentObject implements DataObject
{
    public function __construct(
        public string $shipmentType,
        public string $shippingService,

        public AddressObject $pickupAddress,
        public AddressObject $deliveryAddress,
        public ParcelObjectCollection $parcels,

        public ?string $reference_1 = null,
        public ?string $reference_2 = null,
        public ?string $comment = null,

        public ?ProductObject $products = null,
        public ?AddressObject $billingAddress = null,
        public ?CashOnDeliveryObject $cashOnDelivery = null,
        public ?float $insurance = null,
        public ?Collection $content = null,
        public ?CarrierObject $carrierObject = null,
    ) {}

    public function build(): array
    {
        return array_filter([
            'shipmentType' => $this->shipmentType,
            'shippingService' => $this->shippingService,
            'reference1' => $this->reference_1,
            'reference2' => $this->reference_2,
            'comment' => $this->comment,

            'pickupAddress' => $this->pickupAddress->build(),
            'deliveryAddress' => $this->deliveryAddress->build(),

            'parcels' => $this->parcels->build(),

            'products' => $this->products?->build(),
            'cashOnDelivery' => $this->cashOnDelivery?->build(),
            'carrier' => $this->carrierObject?->build(),
        ], static function ($item) {
            return $item !== null;
        });

    }
}
