<?php
namespace App\Domain\Tasks\Entities;

class Task
{
    public array $comments = [];
    public int $commentsCount = 0;
    public array $subTasks = [];
    public int $subTasksCount = 0;
    public int $completedSubTasksCount = 0;
    public array $responsablesNoms = [];

    public function __construct(
        public ?int $id,
        public ?int $categoryId,
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
            throw new \DomainException("Impossible d'archiver une tâche non terminée.");
        }

        $this->estArchivee = true;
    }
}
