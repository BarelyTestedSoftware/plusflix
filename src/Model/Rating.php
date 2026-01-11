<?php

namespace App\Model;

class Rating
{
    public int $id;
    public int $value;
    public Show $show;

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'value' => $this->value,
            'show_id' => $this->show->id,
        ];
    }
}
