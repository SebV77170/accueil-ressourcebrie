<?php
namespace App\Domain\Sites\Entities;

class Site
{
    public function __construct(
        public ?int $id,
        public string $nom,
        public string $url,
        public string $categorie,
        public ?string $description = null,
    ) {}
}
