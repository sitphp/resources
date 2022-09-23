<?php

namespace SitPHP\Resources;

class Pipe extends FileResource
{
    use StreamableTrait;

    /**
     * @param string $path
     * @param int $permissions
     * @return bool
     */
    static function create(string $path, int $permissions = 0644)
    {
        if (file_exists($path)) {
            return false;
        }
        return posix_mkfifo($path, $permissions);
    }

    /**
     * Check if file exists
     *
     * @param string $path
     * @return bool
     * @see http://php.net/manual/function.exists.php
     */
    static function isValid(string $path): bool
    {
        if (!file_exists($path)) {
            return false;
        }
        $file = new File($path);
        if (!$file->isPipe()) {
            return false;
        }
        return true;
    }


    /**
     * @param string $path
     * @param int $permissions
     */
    function __construct(string $path, int $permissions = 0644)
    {
        if (!file_exists($path)) {
            self::create($path, $permissions);
        } else {
            $file = new File($path);
            if (!$file->isPipe()) {
                throw new \InvalidArgumentException('Invalid path : path to pipe file expected.');
            }
        }
        $this->path = $path;
        parent::__construct($path);
    }

    /**
     * @param $context
     * @return bool
     * @throws \Exception
     */
    function delete($context = null): bool
    {
        $this->close();
        return parent::delete($context);
    }
}