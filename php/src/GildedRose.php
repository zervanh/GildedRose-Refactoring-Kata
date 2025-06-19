<?php

declare(strict_types=1);

namespace GildedRose;

final class GildedRose
{
    private const MAX_QUALITY = 50;

    /**
     * @param Item[] $items
     */
    public function __construct(
        private array $items
    ) {
    }

    public function updateQuality(): void
    {
        foreach ($this->items as $item) {
            if ($item->name === 'Sulfuras, Hand of Ragnaros') {
                continue;
            }

            $isBackstagePass = $this->assertIsBackstagePass($item);
            $isAgedBrie = $this->assertIsAgedBrie($item);

            if (($isBackstagePass === true || $isAgedBrie === true) && $item->quality < self::MAX_QUALITY) {
                ++$item->quality;

                if ($isBackstagePass === true && $item->quality < self::MAX_QUALITY) {
                    if ($item->sellIn < 11) {
                        ++$item->quality;
                    }
                    if ($item->sellIn < 6) {
                        ++$item->quality;
                    }
                }
            }

            if ($isBackstagePass === false && $isAgedBrie === false && $item->quality > 0) {
                --$item->quality;
            }

            --$item->sellIn;

            if ($item->sellIn < 0 && $item->quality !== 0) {
                if ($isBackstagePass === true) {
                    $item->quality -= $item->quality;
                    continue;
                }

                if ($isAgedBrie === true && $item->quality < self::MAX_QUALITY) {
                    ++$item->quality;
                    continue;
                }

                if ($isAgedBrie === false && $isBackstagePass === false && $item->quality > 0) {
                    --$item->quality;
                }
            }
        }
    }

    private function assertIsBackstagePass(Item $item): bool
    {
        return $item->name === 'Backstage passes to a TAFKAL80ETC concert';
    }

    private function assertIsAgedBrie(Item $item): bool
    {
        return $item->name === 'Aged Brie';
    }
}
