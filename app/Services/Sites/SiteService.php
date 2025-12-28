<?php
namespace App\Services\Sites;

use App\Domain\Sites\Repositories\SiteRepository;
use App\Domain\Sites\Entities\Site;

class SiteService
{
    public function __construct(
        private SiteRepository $repository
    ) {}

    public function list(): array
    {
        return $this->repository->all();
    }

    public function categories(): array
    {
        return $this->repository->categories();
    }

    public function create(array $data): Site
    {
        return $this->repository->create(
            new Site(
                null,
                $data['nom'],
                $data['url'],
                $data['categorie'],
                $data['description'] ?? null
            )
        );
    }

    public function update(int $id, array $data): Site
    {
        return $this->repository->update(
            new Site(
                $id,
                $data['nom'],
                $data['url'],
                $data['categorie'],
                $data['description'] ?? null
            )
        );
    }

    public function delete(int $id): void
    {
        $this->repository->delete($id);
    }
}
