<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

/**
 * A single goods line for the customs declaration.
 *
 * Every field is optional on the API side. manufacturerCountry and currency are
 * validated against the carrier's own country and currency tables, so an unknown
 * code is rejected rather than ignored.
 */
class ContentObject implements DataObject
{
    public function __construct(
        public ?string $code = null,
        public ?string $barcode = null,
        public ?string $description = null,
        public ?string $specification = null,
        public ?string $brandname = null,

        /** Harmonized System code, e.g. 6109.10 */
        public ?string $hsCode = null,
        public ?float $quantity = null,
        public ?string $category = null,
        public ?string $unitType = null,
        public ?float $unitValue = null,

        /** Kilograms. */
        public ?float $netWeight = null,
        public ?float $grossWeight = null,

        /** UN number for dangerous goods. */
        public ?string $unNumber = null,

        /** ISO 3166-1 alpha-2, e.g. CN */
        public ?string $manufacturerCountry = null,

        /** ISO 4217, e.g. EUR */
        public ?string $currency = null,
        public ?string $purposeOfShipment = null,
        public ?string $packagingTypeCode = null,
        public ?string $packagingGroup = null,

        /** ADR classified dangerous goods. */
        public ?bool $adr = null,

        /** ADR limited quantity. */
        public ?bool $adrLq = null,
        public ?float $amount = null,

        /** Y-m-d */
        public ?string $invoiceDate = null,
        public ?string $invoiceNumber = null,
        public ?string $invoiceImage = null,
    ) {}

    public function build(): array
    {
        $invoice = array_filter([
            'date' => $this->invoiceDate,
            'number' => $this->invoiceNumber,
            'image' => $this->invoiceImage,
        ], static fn ($item): bool => $item !== null);

        return array_filter([
            'code' => $this->code,
            'barcode' => $this->barcode,
            'description' => $this->description,
            'specification' => $this->specification,
            'brandname' => $this->brandname,

            'hsCode' => $this->hsCode,
            'quantity' => $this->quantity,
            'category' => $this->category,
            'unitType' => $this->unitType,
            'unitValue' => $this->unitValue,

            'netWeight' => $this->netWeight,
            'grossWeight' => $this->grossWeight,

            'unNumber' => $this->unNumber,
            'manufacturerCountry' => $this->manufacturerCountry,
            'currency' => $this->currency,
            'purposeOfShipment' => $this->purposeOfShipment,
            'packagingTypeCode' => $this->packagingTypeCode,
            'packagingGroup' => $this->packagingGroup,

            'adr' => $this->adr,
            'adrLq' => $this->adrLq,
            'amount' => $this->amount,

            'invoice' => $invoice === [] ? null : $invoice,
        ], static fn ($item): bool => $item !== null);
    }
}
