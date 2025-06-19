<?php

declare(strict_types=1);

namespace Tests;

use GildedRose\GildedRose;
use GildedRose\Item;
use PHPUnit\Framework\TestCase;

class GildedRoseTest extends TestCase
{
    public function testItCanUpdateSellInValueOfItems(): void
    {
        $item = new Item('foo', 2, 10);
        $gildedRose = new GildedRose([$item]);
        $gildedRose->updateQuality();
        $this->assertEquals(1, $item->sellIn);
    }

    public function updateQualityProvider(): array
    {
        return [
            'decrease quality is normal before max sell days' => [
                'items' => [
                    new Item('foo', 2, 10),
                    new Item('foo', 5, 12),
                ],
                'expectedResults' => [9, 11],
            ],
            'decrease quality is double after max sell days' => [
                'items' => [
                    new Item('foo', 0, 10),
                    new Item('foo', -1, 4),
                ],
                'expectedResults' => [8, 2],
            ],
        ];
    }

    /**
     * @dataProvider updateQualityProvider
     */
    public function testItCanUpdateQualityValueOfItems(array $items, array $expectedResults): void
    {
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($expectedResults as $expectedResult) {
            $item = array_shift($items);
            $this->assertEquals($expectedResult, $item->quality);
        }
    }

    public function testItAssertsQualityIsNotNegative(): void
    {
        $item = new Item('foo', 2, 0);
        $gildedRose = new GildedRose([$item]);
        $gildedRose->updateQuality();

        $this->assertEquals(0, $item->quality);
    }

    public function testQualityOfItemsNeverGoesAboveSetValue(): void
    {
        $item = new Item('Aged Brie', 1, 50);
        $gildedRose = new GildedRose([$item]);
        $gildedRose->updateQuality();

        $this->assertEquals(50, $item->quality);
    }

    public function agedBrieProvider(): array
    {
        return [
            'quality increases in normal rate' => [
                'items' => [
                    new Item('Aged Brie', 1, 1),
                    new Item('Aged Brie', 5, 4),
                ],
                'expectedResults' => [2, 5],
            ],
            'quality increases in double the rate' => [
                'items' => [
                    new Item('Aged Brie', 0, 2),
                    new Item('Aged Brie', -1, 4)
                ],
                'expectedResults' => [4, 6],
            ],
        ];
    }

    /**
     * @dataProvider agedBrieProvider
     */
    public function testAgedBrieIncreasesInQualityWhenSellInDecreases(array $items, array $expectedResults): void
    {
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($expectedResults as $expectedResult) {
            $item = array_shift($items);
            $this->assertEquals($expectedResult, $item->quality);
        }
    }

    public function testSulfurasIsNotEffectedByTime(): void
    {
        $item = new Item('Sulfuras, Hand of Ragnaros', 5, 80);
        $gildedRose = new GildedRose([$item]);
        $gildedRose->updateQuality();

        $this->assertEquals(5, $item->sellIn);
        $this->assertEquals(80, $item->quality);
    }

    public function backstagePassesProvider(): array
    {
        return [
            'increase quality by 1' => [
                'items' => [
                    new Item('Backstage passes to a TAFKAL80ETC concert', 11, 2),
                ],
                'expectedResults' => [3],
            ],
            'increase quality by 2' => [
                'items' => [
                    new Item('Backstage passes to a TAFKAL80ETC concert', 10, 4),
                    new Item('Backstage passes to a TAFKAL80ETC concert', 6, 6)
                ],
                'expectedResults' => [6, 8],
            ],
            'increase quality by 3' => [
                'items' => [
                    new Item('Backstage passes to a TAFKAL80ETC concert', 5, 4),
                    new Item('Backstage passes to a TAFKAL80ETC concert', 1, 6)
                ],
                'expectedResults' => [7, 9],
            ],
            'quality decreases to zero after concert day' => [
                'items' => [
                    new Item('Backstage passes to a TAFKAL80ETC concert', 0, 10),
                    new Item('Backstage passes to a TAFKAL80ETC concert', -1, 10),
                ],
                'expectedResults' => [0],
            ]
        ];
    }

    /**
     * @dataProvider backstagePassesProvider
     */
    public function testBackstagePasses(array $items, array $expectedResults): void
    {
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($expectedResults as $expectedResult) {
            $item = array_shift($items);
            $this->assertEquals($expectedResult, $item->quality);
        }
    }

    public function conjuredItemsProvider(): array
    {
        return [
            'quality decreases double the rate for conjured item' => [
                'items' => [
                    new Item('Conjured Mana Cake', 2, 10),
                    new Item('Conjured Carrot', 5, 6),
                ],
                'expectedResults' => [8, 4],
            ],
        ];
    }

    /**
     * @dataProvider conjuredItemsProvider
     */
    public function testConjuredItemsDecreaseDoubleTheRateOfNormalItems(array $items, array $expectedResults): void
    {
        $gildedRose = new GildedRose($items);
        $gildedRose->updateQuality();

        foreach ($expectedResults as $expectedResult) {
            $item = array_shift($items);
            $this->assertEquals($expectedResult, $item->quality);
        }
    }
}
