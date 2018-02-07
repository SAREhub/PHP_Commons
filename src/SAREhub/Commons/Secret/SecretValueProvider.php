<?php


namespace SAREhub\Commons\Secret;

/**
 * Unified interface for getting secrets values like password, access token and other sensitive data
 */
interface SecretValueProvider
{
    /**
     * Returns value of given secret
     * @param string $secretName
     * @throws SecretValueNotFoundException When value of secret not found
     * @return string
     */
    public function get(string $secretName): string;
}