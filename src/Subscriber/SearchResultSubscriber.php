<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Subscriber;

use Revinners\NoSearchResultRegister\Service\NoSearchResultLogger;
use Shopware\Core\Content\Product\Events\ProductSearchResultEvent;
use Shopware\Core\System\SalesChannel\Entity\SalesChannelEntityIdSearchResultLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchResultSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly NoSearchResultLogger $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductSearchResultEvent::class => 'onProductSearchResult'
        ];
    }

    public function onProductSearchResult(ProductSearchResultEvent $event): void
    {
        // Jeśli wyniki wyszukiwania są puste, zapisz frazę
        if ($event->getResult()->getTotal() > 0) {
            return;
        }
        $phrase = $event->getRequest()->query->get('search', '');
        if (!is_string($phrase) || trim($phrase) === '') {
            return;
        }
        $this->logger->log($phrase);
    }
}
