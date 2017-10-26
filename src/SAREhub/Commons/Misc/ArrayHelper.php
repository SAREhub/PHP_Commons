<?php

namespace SAREhub\Commons\Misc;


use RecursiveArrayIterator;
use RecursiveIteratorIterator;

class ArrayHelper
{

    /**
     * /**
     * Flattens array into a one-dimensional array.
     * Each key in the returned one-dimensional array is the join of all keys leading to
     * each (non-traversable) value, in all dimensions, separated by a given separator.
     *
     * @param array $input
     * @param string $keySeparator
     * @return array
     */
    public static function flatten(array $input, $keySeparator = '.')
    {
        $iterator = new RecursiveIteratorIterator(new RecursiveArrayIterator($input),
            RecursiveIteratorIterator::SELF_FIRST);
        $path = [];
        $output = [];

        foreach ($iterator as $key => $value) {
            $path[$iterator->getDepth()] = $key;

            if (!is_array($value)) {
                $output[implode($keySeparator, array_slice($path, 0, $iterator->getDepth() + 1))] = $value;
            }
        }

        return $output;
    }

    public static function groupByKey(array $records, $key)
    {
        return self::groupBy($records, function ($record) use ($key) {
            return $record[$key];
        });
    }

    public static function groupBy(array $records, callable $keySelector)
    {
        $groups = [];
        foreach ($records as $record) {
            $groups[$keySelector($record)][] = $record;
        }
        return $groups;
    }
}