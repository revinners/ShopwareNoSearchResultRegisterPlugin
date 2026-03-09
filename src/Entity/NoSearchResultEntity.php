<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\Entity;
use Shopware\Core\Framework\DataAbstractionLayer\EntityIdTrait;

class NoSearchResultEntity extends Entity
{
    use EntityIdTrait;

    protected string $phrase;

    protected int $count;

    protected \DateTimeInterface $firstSearchedAt;

    protected \DateTimeInterface $lastSearchedAt;

    public function getPhrase(): string
    {
        return $this->phrase;
    }

    public function setPhrase(string $phrase): void
    {
        $this->phrase = $phrase;
    }

    public function getCount(): int
    {
        return $this->count;
    }

    public function setCount(int $count): void
    {
        $this->count = $count;
    }

    public function getFirstSearchedAt(): \DateTimeInterface
    {
        return $this->firstSearchedAt;
    }

    public function setFirstSearchedAt(\DateTimeInterface $firstSearchedAt): void
    {
        $this->firstSearchedAt = $firstSearchedAt;
    }

    public function getLastSearchedAt(): \DateTimeInterface
    {
        return $this->lastSearchedAt;
    }

    public function setLastSearchedAt(\DateTimeInterface $lastSearchedAt): void
    {
        $this->lastSearchedAt = $lastSearchedAt;
    }
}

