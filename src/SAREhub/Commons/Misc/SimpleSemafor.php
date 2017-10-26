<?php

namespace SAREhub\Commons\Misc;

/**
 * Semafor implementation based on file exists
 */
class SimpleSemafor
{

    /** @var string */
    private $filePath;

    public function __construct($filePath)
    {
        $this->filePath = $filePath;
    }

    /**
     * Try create semafor file
     * @return bool When successful locked
     */
    public function lock()
    {
        if (!$this->isLocked()) {
            $file = fopen($this->getFilePath(), 'w');
            if ($file) {
                fwrite($file, getmypid());
                fclose($file);
                return true;
            }
        }
        return false;
    }

    /**
     * Removing semafor file
     */
    public function unlock()
    {
        if ($this->isLocked()) {
            unlink($this->getFilePath());
        }
    }

    /**
     * Checks lock
     * @return bool
     */
    public function isLocked()
    {
        clearstatcache(true, $this->getFilePath());
        return file_exists($this->getFilePath());
    }

    /**
     * @return string
     */
    public function getFilePath()
    {
        return $this->filePath;
    }
}