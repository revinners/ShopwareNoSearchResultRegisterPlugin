<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Service;

/**
 * Czyści frazę z URL-i i parametrów śledzących (utm_*, gclid, ...) doklejonych przy kopiowaniu
 * linków. Dzięki temu warianty „...assy" i „...assy?utm_source=chatgpt.com" agregują się do
 * jednej pozycji w rejestrze fraz bez wyników, zamiast tworzyć osobne, fałszywe wpisy.
 *
 * Pojedynczy znak zapytania (np. „jaki olej do exc?") jest zachowywany – usuwamy tylko
 * sekwencje wyglądające jak parametry URL (`?klucz=wartość`).
 */
class QueryNormalizer
{
    public function normalize(string $term): string
    {
        $term = preg_replace('#\bhttps?://\S+#i', ' ', $term) ?? $term;
        $term = preg_replace('/[?&#][a-z0-9_]+=\S*/i', ' ', $term) ?? $term;
        $term = preg_replace('/\b(?:utm_[a-z]+|gclid|fbclid|mc_[a-z]+|igshid|yclid)=\S*/i', ' ', $term) ?? $term;
        $term = preg_replace('/\s+/', ' ', $term) ?? $term;

        return trim($term);
    }
}
