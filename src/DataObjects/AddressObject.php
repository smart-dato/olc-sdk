<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class AddressObject implements DataObject
{
    public function __construct(
        public string $personName = '',
        public string $companyName = '',
        public string $street = '',
        public string $city = '',
        public string $zipcode = '',
        public string $countryCode = '',
        public ?string $email = '',
        public ?string $phone = '',
        public ?string $warehouse = null,
    ) {}

    public function build(): array
    {
        if ($this->warehouse) {
            return [
                'warehouse' => $this->warehouse,
            ];
        }

        return [
            'personName' => $this->personName,
            'companyName' => $this->companyName,
            'street' => $this->street,
            'city' => $this->city,
            'zipcode' => $this->zipcode,
            'countryCode' => $this->countryCode,
            'email' => $this->email ?? '',
            'phone' => $this->phone ?? '',
        ];
    }
}
