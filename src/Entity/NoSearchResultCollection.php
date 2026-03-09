<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityCollection;

/**
 * @extends EntityCollection<NoSearchResultEntity>
 */
class NoSearchResultCollection extends EntityCollection
{
    protected function getExpectedClass(): string
    {
        return NoSearchResultEntity::class;
    }
}

