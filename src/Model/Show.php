<?php

namespace App\Model;

class Show
{
    public int $id;
    public string $title;
    public string $description;
    public int $type; // 1 - movie, 2 - series
    public string $productionDate;
    public int $numberOfEpisodes;
    public Media $coverImage;
    public Media $backgroundImage;
    public Person $director;
    /** @var Person[] */
    public array $actors;
    /** @var Streaming[] */
    public array $streamings;
    /** @var Category[] */
    public array $categories;
    // Average rating from reviews
    public float $rating;
    public int $numberOfRatings;
}
