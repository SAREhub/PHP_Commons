<?php

namespace SAREhub\Commons\Zmq\PublishSubscribe;


class SubscriptionTopic
{

    const PART_SEPARATOR = '.';
    const STAR_PART = '*';
    const HASH_PART = '#';

    private $topic;
    private $topicParts;

    public function __construct(string $topic)
    {
        $this->topic = $topic;
        $this->topicParts = self::explodeTopicParts($this->topic);
    }

    public function match(string $other)
    {
        if ($this->topic === $other) { // for optimalization check topic is exactly same
            return true;
        }

        $otherParts = self::explodeTopicParts($other);

        if (count($this->topicParts) !== count($otherParts)) { // check topic parts count
            return false;
        }

        foreach ($this->topicParts as $i => $part) {
            if (!self::matchPart($part, $otherParts[$i])) {
                return false;
            }
        }

        return true;
    }

    public static function matchPart(string $part, string $other)
    {
        return self::isWildcardPart($part) || $part === $other;
    }

    public static function isStarPart(string $part)
    {

    }

    public static function isHashPart(string $part)
    {

    }

    public static function isWildcardPart(string $part)
    {
        return $part == self::STAR_PART;
    }

    public static function explodeTopicParts(string $topic)
    {
        return explode(self::PART_SEPARATOR, $topic);
    }

    public function __toString()
    {
        return $this->topic;
    }
}