<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Subscriber;

use Revinners\NoSearchResultRegister\Service\NoSearchResultLogger;
use Shopware\Core\Content\Product\Events\ProductSearchResultEvent;
use Shopware\Storefront\Page\Suggest\SuggestPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class SearchResultSubscriber implements EventSubscriberInterface
{
    public function __construct(private readonly NoSearchResultLogger $logger)
    {
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ProductSearchResultEvent::class => 'onProductSearchResult',
            SuggestPageLoadedEvent::class => 'onSuggestPageLoaded',
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

    public function onSuggestPageLoaded(SuggestPageLoadedEvent $event): void
    {
        $page = $event->getPage();
        if ($page->getSearchResult()->getTotal() > 0) {
            return;
        }

        $phrase = $page->getSearchTerm();
        if (trim($phrase) === '') {
            return;
        }

        $this->logger->log($phrase);
    }
}
