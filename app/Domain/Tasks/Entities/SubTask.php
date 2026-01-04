<?php
namespace App\Domain\Tasks\Entities;

class SubTask
{
    public array $comments = [];
    public int $commentsCount = 0;

    public function __construct(
        public ?int $id,
        public int $taskId,
        public string $titre,
        public ?string $description,
        public ?array $responsables,
        public ?string $commentaire,
        public bool $estTerminee,
        public bool $estArchivee,
        public ?\DateTime $dateEffectuee,
        public \DateTime $dateCreation,
    ) {}

    public function complete(): void
    {
        $this->estTerminee = true;
        $this->dateEffectuee = new \DateTime();
    }

    public function uncomplete(): void
    {
        $this->estTerminee = false;
        $this->dateEffectuee = null;
    }

    public function archive(): void
    {
        if (! $this->estTerminee) {
            throw new \DomainException("Impossible d'archiver une sous-tâche non terminée.");
        }

        $this->estArchivee = true;
    }
}
