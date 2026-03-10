# RevinnersNoSearchResultRegister

Plugin Shopware 6.6, który zapisuje w bazie danych frazy wyszukiwania, dla których nie znaleziono żadnych wyników, wraz z licznikiem wyszukiwań.

## Funkcjonalności

- Automatyczne przechwytywanie fraz wyszukiwania bez wyników
- Zliczanie, ile razy dana fraza była wyszukiwana
- Zapis daty pierwszego i ostatniego wyszukiwania
- Moduł w panelu administracyjnym (lista fraz posortowana wg liczby wyszukiwań)

## Instalacja

### 1. Skopiuj plugin do Shopware

```bash
cp -r RevinnersNoSearchResultRegister /var/www/html/custom/plugins/
```

### 2. Zarejestruj i zainstaluj plugin

```bash
bin/console plugin:refresh
bin/console plugin:install --activate RevinnersNoSearchResultRegister
bin/console cache:clear
```

### 3. Uruchom migracje (wykonywane automatycznie przy instalacji)

Migracja tworzy tabelę `revinners_no_search_result` z kolumnami:

| Kolumna            | Typ          | Opis                           |
|--------------------|--------------|--------------------------------|
| id                 | BINARY(16)   | UUID (klucz główny)            |
| phrase             | VARCHAR(500) | Fraza wyszukiwania (UNIQUE)    |
| count              | INT UNSIGNED | Liczba wyszukiwań              |
| first_searched_at  | DATETIME(3)  | Data pierwszego wyszukiwania   |
| last_searched_at   | DATETIME(3)  | Data ostatniego wyszukiwania   |
| created_at         | DATETIME(3)  | Data utworzenia rekordu        |
| updated_at         | DATETIME(3)  | Data ostatniej aktualizacji    |

### 4. Zbuduj panel administracyjny (opcjonalnie)

```bash
bin/console bundle:dump
bin/build-administration.sh
```

## Struktura pluginu

```
RevinnersNoSearchResultRegister/
├── composer.json
└── src/
    ├── RevinnersNoSearchResultRegister.php   # Bootstrap pluginu
    ├── Migration/
    │   └── Migration1741521600CreateNoSearchResultTable.php
    ├── Entity/
    │   ├── NoSearchResultDefinition.php      # Definicja DAL
    │   ├── NoSearchResultEntity.php          # Encja
    │   └── NoSearchResultCollection.php      # Kolekcja
    ├── Subscriber/
    │   └── SearchResultSubscriber.php        # Nasłuchiwanie zdarzeń
    ├── Service/
    │   └── NoSearchResultLogger.php          # Logika zapisu (atomowy UPSERT)
    └── Resources/
        ├── config/
        │   ├── services.xml                  # Rejestracja serwisów DI
        │   └── routes.xml                    # Rejestracja tras
        └── app/administration/src/
            ├── main.js
            └── module/revinners-no-search-result/
                ├── index.js
                ├── snippet/
                │   ├── en-GB.json
                │   └── pl-PL.json
                └── page/revinners-no-search-result-list/
                    ├── index.js
                    └── revinners-no-search-result-list.html.twig
```

## Jak to działa

1. Shopware emituje zdarzenie `ProductSearchResultEvent` po każdym wyszukiwaniu produktów.
2. `SearchResultSubscriber` sprawdza, czy liczba wyników wynosi 0.
3. Jeśli tak, przekazuje frazę do `NoSearchResultLogger`.
4. Logger wykonuje atomowy `INSERT ... ON DUPLICATE KEY UPDATE`, co zapewnia poprawność danych nawet przy równoległych żądaniach.
