<?php
namespace App\Domain\Sites\Repositories;

use App\Domain\Sites\Entities\Site;

interface SiteRepository
{
    public function all(): array;
    public function find(int $id): ?Site;
    public function create(Site $site): Site;
    public function update(Site $site): Site;
    public function delete(int $id): void;
    public function categories(): array;
}
