<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Subscriber;

use Revinners\NoSearchResultRegister\Service\NoSearchResultLogger;
use Shopware\Core\Content\Product\Events\ProductSearchResultEvent;
use Shopware\Storefront\Page\Suggest\SuggestPageLoadedEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpFoundation\RequestStack;

class SearchResultSubscriber implements EventSubscriberInterface
{
    private const array FILTER_QUERY_KEYS = [
        'manufacturer',
        'properties',
        'price',
        'rating',
        'shipping-free',
        'p',
        'only-aggregations'
    ];

    public function __construct(
        private readonly NoSearchResultLogger $logger,
        private readonly RequestStack $requestStack,
    ) {
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

        if ($this->hasActiveFilters($event->getRequest()->query->all())) {
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

        $request = $this->requestStack->getCurrentRequest();
        if ($request !== null && $this->hasActiveFilters($request->query->all())) {
            return;
        }

        $phrase = $page->getSearchTerm();
        if (trim($phrase) === '') {
            return;
        }

        $this->logger->log($phrase);
    }

    private function hasActiveFilters(array $query): bool
    {
        foreach (self::FILTER_QUERY_KEYS as $key) {
            if (!array_key_exists($key, $query)) {
                continue;
            }

            $value = $query[$key];
            if (is_array($value)) {
                if ($value !== []) {
                    return true;
                }

                continue;
            }

            if (is_string($value) && trim($value) !== '') {
                return true;
            }
        }

        return false;
    }
}
