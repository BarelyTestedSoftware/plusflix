<?php

namespace App\Controller;

use App\Exception\NotFoundException;
use App\Model\Person;
use App\Service\Router;

class PersonController
{
    public function getAll(): array
    {
        return ['persons' => Person::findAll()];
    }

    public function get(int $id): array
    {
        $person = Person::find($id);
        if (!$person) {
            throw new NotFoundException("Osoba o ID $id nie istnieje");
        }
        return ['person' => $person];
    }

    public function store(array $data, Router $router): int
    {
        if (!isset($data['name']) || empty(trim($data['name']))) {
            throw new \InvalidArgumentException("Imię i nazwisko nie mogą być puste.");
        }

        if (isset($data['type']) && !in_array((int)$data['type'], [0, 1])) {
            throw new \InvalidArgumentException("Nieprawidłowy typ osoby.");
        }
        
        $person = Person::fromArray($data);

        $person->save();
        $router->redirect('/admin/person');

        return $person->getId();
    }

    public function edit(int $id): array
    {
        $person = Person::find($id);
        if (!$person) {
            throw new NotFoundException("Osoba o ID $id nie istnieje");
        }
        return ['person' => $person];
    }

    public function update(int $id, array $data, Router $router): void
    {
        $person = Person::find($id);
        if (!$person) {
            throw new NotFoundException("Osoba o ID $id nie istnieje");
        }
        $person->fill($data);

        $person->save();
        $router->redirect('/admin/person');
    }

    public function delete(int $id, Router $router): void
    {
        $person = Person::find($id);
        if (!$person) {
            throw new NotFoundException("Osoba o ID $id nie istnieje");
        }
        $person->delete();
        $router->redirect('/admin/person');
    }
}
