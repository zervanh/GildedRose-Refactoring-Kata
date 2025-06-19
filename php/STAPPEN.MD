## Refactor keuze
Gekozen voor een simpele refactor van alleen de functionele code, op het moment van maken leek dit het meest logisch.

## Stappen
Code style fixes gedaan.

Huidige werking vastleggen doormiddel van unit tests in GildedRoseTest.php.

Edge cases vastleggen - Maximum quality van 50 - sellIn drempel van 2 bij minder dan 10 dagen en 3 bij minder dan 5 dagen voor de tickets.

Functionaliteit refactoren per getest geval in GildedRose.php.

Per gerefactored stukje code test opnieuw runnen om te zien dat de verwachte functionaliteit nog steeds naar behoren werkt.

Conjured items functionaliteit toevoegen en vastleggen in tests.

## Achteraf
Functionaliteit van de code had mooier opgeleverd kunnen worden door gebruik te maken van bijvoorbeeld een stategie per type product.
