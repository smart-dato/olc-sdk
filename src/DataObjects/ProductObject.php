<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class ProductObject implements DataObject
{
    public function __construct(
        public bool $gdo = false,
        public bool $b2cDelivery = false,
        public bool $deliveryByAppointment = false,
        public bool $liftingPlatform = false,
        public bool $palletSwap = false,
        public bool $phonePreDeliveryAlert = false,

        public bool $scheduledDelivery = false,
        public ?string $scheduledDeliveryAt = null,

    ) {}

    public function build(): array
    {
        return array_filter([
            'gdo' => $this->gdo,

            'b2cDelivery' => $this->b2cDelivery,
            'deliveryByAppointment' => $this->deliveryByAppointment,
            'liftingPlatform' => $this->liftingPlatform,
            'palletSwap' => $this->palletSwap,
            'phonePreDeliveryAlert' => $this->phonePreDeliveryAlert,

            'scheduledDelivery' => $this->scheduledDelivery,
            'scheduledDeliveryAt' => $this->scheduledDeliveryAt,
        ], static function ($item) {
            return ! is_null($item) && $item !== false;
        });
    }
}
