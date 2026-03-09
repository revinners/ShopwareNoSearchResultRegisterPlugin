<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Service;

use Doctrine\DBAL\Connection;
use Shopware\Core\Framework\Uuid\Uuid;

readonly class NoSearchResultLogger
{
    public function __construct(private Connection $connection)
    {
    }

    public function log(string $phrase): void
    {
        $phrase = mb_substr(trim($phrase), 0, 500);

        if ($phrase === '') {
            return;
        }

        $now = (new \DateTime())->format('Y-m-d H:i:s.000');

        // Atomowe: wstaw nowy rekord lub zwiększ licznik przy duplikacie frazy
        $this->connection->executeStatement(
            'INSERT INTO `revinners_no_search_result`
                (`id`, `phrase`, `count`, `first_searched_at`, `last_searched_at`, `created_at`)
             VALUES
                (:id, :phrase, 1, :now, :now, :now)
             ON DUPLICATE KEY UPDATE
                `count`            = `count` + 1,
                `last_searched_at` = :now,
                `updated_at`       = :now',
            [
                'id'     => Uuid::randomBytes(),
                'phrase' => $phrase,
                'now'    => $now,
            ]
        );
    }
}

