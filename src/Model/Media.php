<?php

namespace App\Model;

class Media
{
    public int $id;
    public string $src;
    public string $alt;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'src' => $this->src,
            'alt' => $this->alt,
        ];
    }
}
