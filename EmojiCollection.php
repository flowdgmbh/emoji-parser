<?php

namespace Flowd\EmojiParser;

class EmojiCollection implements \Iterator, \ArrayAccess
{
    private $emojis = [];

    public function add(Emoji $emoji)
    {
        $this->emojis[] = $emoji;
        return $this;
    }

    public function rewind()
    {
        \reset($this->emojis);
    }

    public function current(): Emoji
    {
        return \current($this->emojis);
    }

    public function key()
    {
        return \key($this->emojis);
    }

    public function next()
    {
        return next($this->emojis);
    }

    public function valid()
    {
        $key = \key($this->emojis);
        return ($key !== null && $key !== false);
    }

    public function offsetExists($offset)
    {
        return \array_key_exists($offset, $this->emojis);
    }

    public function offsetGet($offset): ?Emoji
    {
        return $this->emojis[$offset] ?? null;
    }

    public function offsetSet($offset, $value)
    {
        if (!$value instanceof Emoji) {
            throw new \InvalidArgumentException('EmojiCollection does only allow Emoji items');
        }
        $this->emojis[$offset] = $value;
    }

    public function offsetUnset($offset)
    {
        if (\array_key_exists($offset, $this->emojis)) {
            unset($this->emojis[$offset]);
        }
    }
}
