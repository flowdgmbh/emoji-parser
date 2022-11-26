<?php

namespace Flowd\EmojiParser;

class EmojiCollection implements \Iterator, \ArrayAccess, \Countable
{
    private $emojis = [];

    public function add(Emoji $emoji)
    {
        $this->emojis[] = $emoji;
        return $this;
    }

    public function rewind(): void
    {
        \reset($this->emojis);
    }

    public function current(): Emoji
    {
        return \current($this->emojis);
    }

    public function key(): mixed
    {
        return \key($this->emojis);
    }

    public function next(): void
    {
        next($this->emojis);
    }

    public function valid(): bool
    {
        $key = \key($this->emojis);
        return $key !== null;
    }

    public function offsetExists($offset): bool
    {
        return \array_key_exists($offset, $this->emojis);
    }

    public function offsetGet($offset): ?Emoji
    {
        return $this->emojis[$offset] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        if (!$value instanceof Emoji) {
            throw new \InvalidArgumentException('EmojiCollection does only allow Emoji items');
        }
        $this->emojis[$offset] = $value;
    }

    public function offsetUnset($offset): void
    {
        if (\array_key_exists($offset, $this->emojis)) {
            unset($this->emojis[$offset]);
        }
    }

    public function count(): int
    {
        return count($this->emojis);
    }
}
