<?php
namespace Monoless\Arrays;

use ArrayAccess;
use InvalidArgumentException;
use Iterator;

class FixedUnsignedIntegerArray implements Iterator, ArrayAccess
{
    const BINARY_FORMAT = 'I';

    private $position = 0;
    private $length = 0;
    private $binaryLength = 0;
    private $data = "";

    public function __construct($length)
    {
        $null = $this->convertBinary(null);

        $this->position = 0;
        $this->length = $length;
        // because binary length is machine dependency
        $this->binaryLength = strlen($null);
        $this->data = str_repeat($null, $length);
    }

    private function getEntry($position)
    {
        $position = $position * $this->binaryLength;
        $unpack = unpack(self::BINARY_FORMAT, substr($this->data, $position, $this->binaryLength));
        $result = false !== $unpack ? $unpack[1] : null;
        if (0 === $result) return null;
        return --$result;
    }

    private function setEntry($position, $value)
    {
        $position = $position * $this->binaryLength;
        $this->data = substr_replace($this->data, $this->convertBinary($value), $position, $this->binaryLength);
    }

    private function convertBinary($value)
    {
        if (null !== $value) ++$value;
        return pack(self::BINARY_FORMAT, $value);
    }

    public function rewind()
    {
        $this->position = 0;
    }

    public function current()
    {
        return $this->getEntry($this->position);
    }

    public function key()
    {
        return $this->position;
    }

    public function next() {
        ++$this->position;
    }

    public function valid()
    {
        return $this->offsetExists($this->position);
    }

    public function offsetSet($offset, $value) {
        if (is_integer($offset) && $offset < $this->length && $offset >= 0) {
            $this->setEntry($offset, $value);
        } else {
            throw new InvalidArgumentException('overflow array offset');
        }
    }

    public function offsetExists($offset) {
        return ($offset < $this->length && $offset >= 0);
    }

    public function offsetUnset($offset) {
        $this->setEntry($offset, null);
    }

    public function offsetGet($offset) {
        return $this->offsetExists($offset) ? $this->getEntry($offset) : null;
    }

    public function size()
    {
        return $this->length;
    }

    public function __debugInfo()
    {
        return [
            'length' => $this->length,
        ];
    }
}