<?php
declare(strict_types=1);

namespace Flowd\EmojiParser;

class Emoji
{
    protected $hexCodePoints = [];

    public function __construct(array $hexCodePoints)
    {
        $this->hexCodePoints = \array_map('strtoupper', $hexCodePoints);
    }

    public function removeSkinTone(): self
    {
        $skinTone = $this->getSkinTone();
        if ($this->getSkinTone() !== null) {
            $this->hexCodePoints = \array_diff($this->hexCodePoints, [$skinTone]);
        }
        return $this;
    }

    public function getSkinTone(): ?string
    {
        return Parser::getSkinToneFromEmoji($this);
    }

    public function isFlag()
    {
        if (\count($this->hexCodePoints) !== 2) {
            return false;
        }
        $decimalCodePoint1 = \hexdec($this->hexCodePoints[0]);
        $decimalCodePoint2 = \hexdec($this->hexCodePoints[1]);
        $decimalFlagStart = \hexdec('1F1E6');
        $decimalFlagEnd = \hexdec('1F1FF');
        if (
            $decimalCodePoint1 >= $decimalFlagStart
            && $decimalCodePoint1 <= $decimalFlagEnd
            && $decimalCodePoint2 >= $decimalFlagStart
            && $decimalCodePoint2 <= $decimalFlagEnd
        ) {
            return true;
        }

        return false;
    }

    /**
     * An Emoji may consist of multiple Emojies combined by the combine character.
     * This methods returns a new EmojiCollection containing all printable emojis without
     * the combine characters and skin tone modifiers.
     *
     * @return EmojiCollection
     */
    public function getComponents(): EmojiCollection
    {
        $emojiCollection = new EmojiCollection();
        foreach ($this->hexCodePoints as $codePoint) {
            if (Parser::codePointIsEmoji($codePoint)) {
                $emojiCollection->add(new Emoji([$codePoint]));
            }
        }
        return $emojiCollection;
    }

    public function __toString()
    {
        return $this->getUnicodeString();
    }

    public function getUnicodeString(): string
    {
        return implode(
            '',
            \array_map(
                function ($e) {
                    return \mb_chr(\hexdec($e), 'utf-8');
                },
                $this->getHexCodePoints()
            )
        );
    }

    public function getHexCodePoints(): array
    {
        return $this->hexCodePoints;
    }
}
