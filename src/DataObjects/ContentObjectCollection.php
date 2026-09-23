<?php

namespace SmartDato\Olc\DataObjects;

use SmartDato\Olc\Contracts\DataObject;

class ContentObjectCollection implements DataObject
{
    /**
     * @var array<ContentObject>
     */
    protected array $content;

    public function __construct()
    {
        $this->content = [];
    }

    public function add(ContentObject $content): ContentObjectCollection
    {
        $this->content[] = $content;

        return $this;
    }

    public function build(): array
    {
        $data = [];
        foreach ($this->content as $content) {
            $data[] = $content->build();
        }

        return $data;
    }
}
