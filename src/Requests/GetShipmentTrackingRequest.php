<?php

namespace SmartDato\Olc\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;
use SmartDato\Olc\Enums\ShipmentReferenceTyp;

class GetShipmentTrackingRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    public function __construct(
        protected string $value,
        protected ShipmentReferenceTyp $type = ShipmentReferenceTyp::KEY,
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return "/services/v2/customers/shipments/{$this->type->value}/{$this->value}/tracking/events";
    }
}
