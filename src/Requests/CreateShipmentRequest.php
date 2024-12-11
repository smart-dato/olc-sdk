<?php

namespace SmartDato\Olc\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;
use SmartDato\Olc\DataObjects\ShipmentObject;

class CreateShipmentRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::POST;

    public function __construct(
        protected ShipmentObject $shipment,
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/services/v2/customers/shipments';
    }

    protected function defaultBody(): array
    {
        return $this->shipment->build();
    }
}
