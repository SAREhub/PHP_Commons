<?php


namespace SAREhub\Commons\Secret;


class SecretValueNotFoundException extends \Exception
{
    private $secretName;

    /**
     *
     * @param string $secretName
     */
    public function __construct(string $secretName)
    {
        $this->secretName = $secretName;
    }


}