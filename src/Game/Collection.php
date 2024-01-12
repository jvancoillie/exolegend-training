<?php

namespace App\Game;

/**
 * @psalm-template TKey of array-key
 * @psalm-template T
 */
class Collection
{
    private $elements = [];

    public function __construct(array $elements = [])
    {
        $this->elements = $elements;
    }

    /**
     * @return array<Tkey, T>
     */
    public function toArray(): array
    {
        return $this->elements;
    }

    public function first()
    {
        return reset($this->elements);
    }

    public function last()
    {
        return end($this->elements);
    }

    /**
     * Creates a new instance from the specified elements.
     *
     * This method is provided for derived classes to specify how a new
     * instance should be created when constructor semantics have changed.
     *
     * @param array $elements elements
     * @psalm-param array<K,V> $elements
     *
     * @return static
     * @psalm-return static<K,V>
     *
     * @psalm-template K of array-key
     * @psalm-template V
     */
    protected function createFrom(array $elements): Collection
    {
        return new static($elements);
    }

    public function remove($key)
    {
        if (!isset($this->elements[$key]) && !array_key_exists($key, $this->elements)) {
            return null;
        }

        $removed = $this->elements[$key];
        unset($this->elements[$key]);

        return $removed;
    }

    public function removeElement($element): bool
    {
        $key = array_search($element, $this->elements, true);

        if (false === $key) {
            return false;
        }

        unset($this->elements[$key]);

        return true;
    }

    public function containsKey($key): bool
    {
        return isset($this->elements[$key]) || array_key_exists($key, $this->elements);
    }

    public function contains($element): bool
    {
        return in_array($element, $this->elements, true);
    }

    public function exists(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @psalm-param TMaybeContained $element
     *
     * @return int|string|false
     * @psalm-return (TMaybeContained is T ? TKey|false : false)
     *
     * @template TMaybeContained
     */
    public function indexOf($element)
    {
        return array_search($element, $this->elements, true);
    }

    public function get($key)
    {
        return $this->elements[$key] ?? null;
    }

    public function getKeys(): array
    {
        return array_keys($this->elements);
    }

    public function getValues(): array
    {
        return array_values($this->elements);
    }

    /**
     * @return int<0, max>
     */
    public function count(): int
    {
        return count($this->elements);
    }

    public function set($key, $value)
    {
        $this->elements[$key] = $value;
    }

    /**
     * @psalm-suppress InvalidPropertyAssignmentValue
     *
     * This breaks assumptions about the template type, but it would
     * be a backwards-incompatible change to remove this method
     */
    public function add($element)
    {
        $this->elements[] = $element;
    }

    public function isEmpty(): bool
    {
        return empty($this->elements);
    }

    public function diff(Collection $collection): Collection
    {
        return $this->createFrom(array_diff($this->elements, $collection->toArray()));
    }

    public function usort(\Closure $func)
    {
        $elements = $this->elements;

        usort($elements, $func);

        return $this->createFrom($elements);
    }

    /**
     * @psalm-param Closure(T):U $func
     *
     * @return static
     * @psalm-return static<TKey, U>
     *
     * @psalm-template U
     */
    public function map(\Closure $func): Collection
    {
        return $this->createFrom(array_map($func, $this->elements));
    }

    public function reduce(\Closure $func, $initial = null)
    {
        return array_reduce($this->elements, $func, $initial);
    }

    /**
     * @return static
     * @psalm-return static<TKey,T>
     */
    public function filter(\Closure $p): Collection
    {
        return $this->createFrom(array_filter($this->elements, $p, ARRAY_FILTER_USE_BOTH));
    }

    public function findFirst(\Closure $p)
    {
        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                return $element;
            }
        }

        return null;
    }

    public function forAll(\Closure $p): bool
    {
        foreach ($this->elements as $key => $element) {
            if (!$p($key, $element)) {
                return false;
            }
        }

        return true;
    }

    public function partition(\Closure $p): array
    {
        $matches = $noMatches = [];

        foreach ($this->elements as $key => $element) {
            if ($p($key, $element)) {
                $matches[$key] = $element;
            } else {
                $noMatches[$key] = $element;
            }
        }

        return [$this->createFrom($matches), $this->createFrom($noMatches)];
    }

    public function __toString()
    {
        return self::class.'@'.spl_object_hash($this);
    }

    public function clear()
    {
        $this->elements = [];
    }

    public function slice($key, $length = null): array
    {
        return array_slice($this->elements, $key, $length, true);
    }
}
