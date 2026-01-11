<?php

namespace App\Model;

class Person
{
    public int $id;
    public string $name;
    public int $type; // 0 for actor, 1 for director

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
        ];
    }
}
