<?php


namespace SAREhub\Commons\Secret;

/**
 * Returns same value as input
 */
class SimpleSecretValueProvider implements SecretValueProvider
{
    public function get(string $secretName): string
    {
        return $secretName;
    }
}