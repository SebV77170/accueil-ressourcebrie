<?php
namespace App\Domain\Sites\Repositories;

use App\Domain\Sites\Repositories\SiteRepository;
use App\Domain\Sites\Entities\Site as SiteEntity;
use App\Models\Site as SiteModel;

class EloquentSiteRepository implements SiteRepository
{
    public function all(): array
    {
        return SiteModel::orderBy('categorie')
            ->orderBy('nom')
            ->get()
            ->map(fn ($m) => $this->toEntity($m))
            ->toArray();
    }

    public function find(int $id): ?SiteEntity
    {
        $model = SiteModel::find($id);
        return $model ? $this->toEntity($model) : null;
    }

    public function create(SiteEntity $site): SiteEntity
    {
        $model = SiteModel::create([
            'nom' => $site->nom,
            'url' => $site->url,
            'category_id' => $site->categoryId,
            'description' => $site->description,
        ]);

        return $this->toEntity($model);
    }

    public function update(SiteEntity $site): SiteEntity
    {
        $model = SiteModel::findOrFail($site->id);
        $model->update([
            'nom' => $site->nom,
            'url' => $site->url,
            'category_id' => $site->categoryId,
            'description' => $site->description,
        ]);

        return $this->toEntity($model);
    }

    public function delete(int $id): void
    {
        SiteModel::destroy($id);
    }

    public function categories(): array
    {
        return SiteModel::select('categorie')
            ->distinct()
            ->orderBy('categorie')
            ->pluck('categorie')
            ->toArray();
    }

    private function toEntity(SiteModel $model): SiteEntity
    {
        return new SiteEntity(
            $model->id,
            $model->nom,
            $model->url,
            $model->category_id,
            $model->description
        );
    }
}
