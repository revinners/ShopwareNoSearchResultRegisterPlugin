<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Entity;

use Shopware\Core\Framework\DataAbstractionLayer\EntityDefinition;
use Shopware\Core\Framework\DataAbstractionLayer\Field\CreatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\DateTimeField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\PrimaryKey;
use Shopware\Core\Framework\DataAbstractionLayer\Field\Flag\Required;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IdField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\IntField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\StringField;
use Shopware\Core\Framework\DataAbstractionLayer\Field\UpdatedAtField;
use Shopware\Core\Framework\DataAbstractionLayer\FieldCollection;

class NoSearchResultDefinition extends EntityDefinition
{
    public const ENTITY_NAME = 'revinners_no_search_result';

    public function getEntityName(): string
    {
        return self::ENTITY_NAME;
    }

    public function getEntityClass(): string
    {
        return NoSearchResultEntity::class;
    }

    public function getCollectionClass(): string
    {
        return NoSearchResultCollection::class;
    }

    protected function defineFields(): FieldCollection
    {
        return new FieldCollection([
            (new IdField('id', 'id'))->addFlags(new PrimaryKey(), new Required()),
            (new StringField('phrase', 'phrase', 500))->addFlags(new Required()),
            (new IntField('count', 'count'))->addFlags(new Required()),
            (new DateTimeField('first_searched_at', 'firstSearchedAt'))->addFlags(new Required()),
            (new DateTimeField('last_searched_at', 'lastSearchedAt'))->addFlags(new Required()),
            new CreatedAtField(),
            new UpdatedAtField(),
        ]);
    }
}

