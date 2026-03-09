<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Migration;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Migration\MigrationStep;

class Migration1741521600CreateNoSearchResultTable extends MigrationStep
{
    public function getCreationTimestamp(): int
    {
        return 1741521600;
    }

    public function update(Connection $connection): void
    {
        $connection->executeStatement('
            CREATE TABLE IF NOT EXISTS `revinners_no_search_result` (
                `id`                BINARY(16)   NOT NULL,
                `phrase`            VARCHAR(500) NOT NULL,
                `count`             INT UNSIGNED NOT NULL DEFAULT 1,
                `first_searched_at` DATETIME(3)  NOT NULL,
                `last_searched_at`  DATETIME(3)  NOT NULL,
                `created_at`        DATETIME(3)  NOT NULL,
                `updated_at`        DATETIME(3)  NULL,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uniq_phrase` (`phrase`(255))
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        ');
    }

    public function updateDestructive(Connection $connection): void
    {
        $connection->executeStatement('DROP TABLE IF EXISTS `revinners_no_search_result`');
    }
}

