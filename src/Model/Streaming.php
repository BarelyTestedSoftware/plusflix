<?php

namespace App\Model;

class Streaming
{
    public int $id;
    public string $name;
    public Media $logoImage;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'logo_image' => $this->logoImage->toArray(),
        ];
    }
}
