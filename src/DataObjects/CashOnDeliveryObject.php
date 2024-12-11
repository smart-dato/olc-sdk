<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class CashOnDeliveryObject implements DataObject
{
    public function __construct(
        public float $amount,
        public string $type,
        public string $currency = 'euro',
    ) {}

    public function build(): array
    {
        return [
            'type' => $this->type,
            'amount' => $this->amount,
            'currency' => $this->currency,
        ];
    }
}
