<?php declare(strict_types=1);

namespace Revinners\NoSearchResultRegister\Service;

/**
 * Wykrywa „śmieciowe" frazy wyszukiwania (SQL injection, XSS, payloady botów), których nie
 * warto zapisywać w rejestrze fraz bez wyników. Loggery są bezpieczne (prepared statements) –
 * ten filtr służy WYŁĄCZNIE higienie danych: bez niego tabela `revinners_no_search_result`
 * zapełnia się payloadami skanerów (np. „... union all select ...”), które zawsze zwracają
 * zero wyników i zaśmiecają listę realnych braków w ofercie.
 *
 * Konserwatywny z założenia – każda reguła wymaga jednoznacznej sygnatury ataku, by nie
 * odrzucić nietypowego, ale prawdziwego numeru części.
 */
class JunkQueryFilter
{
    /** @var list<string> */
    private const SIGNATURES = [
        '/\bunion\b[\s\S]{0,40}\bselect\b/',
        '/\bselect\b[\s\S]{0,80}\bfrom\b/',
        '/\b(insert|update|delete|drop|truncate)\b[\s\S]{0,40}\b(into|from|table)\b/',
        '/\bconcat\s*\(/',
        '/0x[0-9a-f]{4,}/',
        '/\b(sleep|benchmark|pg_sleep|waitfor\s+delay)\s*\(?/',
        '/\binformation_schema\b|\bsysobjects\b|@@version/',
        '/\b(or|and)\b\s+[\'"\d][\s\S]{0,10}=[\s\S]{0,10}[\'"\d]/',
        '/;[\s\S]{0,10}\b(select|insert|update|delete|drop)\b/',
        '/--\s|\/\*|\*\//',
        '/<\s*script\b|javascript:|<\s*img\b|<\s*svg\b/',
        '/\bon(error|load|click|mouseover)\s*=/',
    ];

    public function isJunk(string $phrase): bool
    {
        if ($phrase === '') {
            return false;
        }

        $normalized = mb_strtolower(rawurldecode(rawurldecode($phrase)));

        foreach (self::SIGNATURES as $pattern) {
            if (preg_match($pattern, $normalized) === 1) {
                return true;
            }
        }

        return false;
    }
}
