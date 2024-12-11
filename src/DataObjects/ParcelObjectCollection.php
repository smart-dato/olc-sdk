<?php

namespace SmartDato\Olc\DataObjects;

use Illuminate\Support\Collection;
use SmartDato\Olc\Contracts\DataObject;

class ParcelObjectCollection implements DataObject
{
    /**
     * @var Collection<ParcelObject>
     */
    protected Collection $parcels;

    public function __construct()
    {
        $this->parcels = collect();
    }

    public function add(ParcelObject $parcel): ParcelObjectCollection
    {
        $this->parcels->push($parcel);

        return $this;
    }

    public function build(): array
    {
        $data = [];
        foreach ($this->parcels as $parcel) {
            $data[] = $parcel->build();
        }

        return $data;
    }
}
