<?php

namespace SmartDato\Olc;

use JsonException;
use Saloon\Exceptions\Request\FatalRequestException;
use Saloon\Exceptions\Request\RequestException;
use Saloon\Http\Connector;
use SmartDato\Olc\DataObjects\ShipmentObject;
use SmartDato\Olc\Exceptions\GenericOlcException;
use SmartDato\Olc\Requests\CreateShipmentRequest;
use SmartDato\Olc\Requests\GetShipmentLabelRequest;

class Olc
{
    public function __construct(
        public Connector $connector = new OlcConnector,
    ) {
        //
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     * @throws GenericOlcException
     */
    public function createShipment(ShipmentObject $shipment)
    {
        $response = $this->connector->send(
            new CreateShipmentRequest(
                shipment: $shipment,
            )
        );

        if ($response->failed()) {
            throw new GenericOlcException($response->body());
        }

        return $response->json('data');
    }

    /**
     * @throws FatalRequestException
     * @throws RequestException
     * @throws JsonException
     * @throws GenericOlcException
     */
    public function getLabel(string $value): string
    {
        $response = $this->connector->send(
            new GetShipmentLabelRequest(
                value: $value,
            )
        );

        if ($response->failed()) {
            throw new GenericOlcException($response->body());
        }

        return $response->json('file');
    }
}
