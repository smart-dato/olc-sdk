<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class ParcelObject implements DataObject
{
    public function __construct(
        public float $weight,
        public float $width = 0.0,
        public float $height = 0.0,
        public float $length = 0.0,
        public ?string $type = null,
        public ?string $reference = null,
    ) {}

    public function build(): array
    {
        return [
            'reference' => $this->reference,
            'weight' => $this->weight,

            'width' => $this->width,
            'height' => $this->height,
            'length' => $this->length,

            'type' => $this->type,
        ];
    }
}
