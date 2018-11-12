<?php
declare(strict_types=1);

namespace Flowd\EmojiParser\Tests;

use Flowd\EmojiParser\Emoji;
use Flowd\EmojiParser\EmojiCollection;
use Flowd\EmojiParser\Parser;
use PHPUnit\Framework\TestCase;

class EmojiTest extends TestCase
{
    public function testRemoveSkinTone(): void
    {
        $expectedEmoji = new Emoji(['1F44D']);
        $emojiWithSkinColor = new Emoji(['1F44D', '1F3FD']);
        $this->assertEquals($expectedEmoji, $emojiWithSkinColor->removeSkinTone());
    }

    /**
     * @dataProvider getComponentsDataProvider
     * @param EmojiCollection $expected
     * @param Emoji $emoji
     */
    public function testGetComponents(EmojiCollection $expected, Emoji $emoji): void
    {
        $this->assertEquals($expected, $emoji->getComponents());
    }

    public function getComponentsDataProvider(): array
    {
        return [
            'Combination of two Emojis will return a collection of both single Emojis' => [
                (new EmojiCollection())->add(new Emoji(['1F469']))->add(new Emoji(['1F466'])),
                new Emoji(['1F469', '200D', '1F466'])
            ],
            'Skin color gets removed' => [
                (new EmojiCollection())->add(new Emoji(['1F44A'])),
                new Emoji(['1F44A', '1F3FB'])
            ],
        ];
    }

    /**
     * @dataProvider isFlagDataProvider
     * @param $expected
     * @param Emoji $emoji
     */
    public function testIsFlag($expected, Emoji $emoji)
    {
        $this->assertSame($expected, $emoji->isFlag());
    }

    public function isFlagDataProvider(): array
    {
        return [
            'Ascension Island' => [
                true,
                new Emoji(['1F1E6', '1F1E8'])
            ],
            'Aruba' => [
                true,
                new Emoji(['1F1E6', '1F1FC'])
            ],
            'Zimbabwe' => [
                true,
                new Emoji(['1F1FF', '1F1FC'])
            ],
            'Women and Boy' => [
                false,
                new Emoji(['1F469', '1F466'])
            ]
        ];
    }

    /**
     * @dataProvider getUnicodeStringDataProvider
     * @param string $expected
     * @param Emoji $emoji
     */
    public function testGetUnicodeString(string $expected, Emoji $emoji): void
    {
        $this->assertEquals($expected, $emoji->getUnicodeString());
    }

    public function getUnicodeStringDataProvider(): array
    {
        return [
            [
                '👩',
                new Emoji(['1F469'])
            ],
            [
                '👦',
                new Emoji(['1F466'])
            ],
            [
                '👩👦',
                new Emoji(['1F469', '1F466'])
            ],
            [
                '👩‍👦',
                new Emoji(['1F469', '200D', '1F466'])
            ],
            [
                '👍🏽',
                new Emoji(['1F44D', '1F3FD'])
            ],
            [
                '🙆🏾‍♂️',
                new Emoji(['1F646', '1F3FE', '200D', '2642', 'FE0F'])
            ],
        ];
    }
}
