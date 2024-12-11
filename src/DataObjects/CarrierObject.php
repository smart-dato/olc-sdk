<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class CarrierObject implements DataObject
{
    public function __construct(
        public ?string $agent = null,
        public ?string $account = null,
        public ?string $service = null,
    ) {}

    public function build(): array
    {
        $body = [];

        if ($this->agent !== null) {
            return [
                'shippingAgent' => $this->agent,
            ];
        }

        if ($this->account !== null) {
            return [
                'shippingAgentAccount' => $this->account,
            ];
        }

        if ($this->service !== null) {
            return [
                'shippingAgentService' => $this->service,
            ];
        }

        return $body;
    }
}
