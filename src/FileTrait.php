<?php

namespace SitPHP\Resources;


trait FileTrait
{
    protected $path;
    protected $handle;
    abstract function checkHandle(string $method);

    /**
     * @param string $data
     * @param int $flags
     * @param null $context
     * @return bool
     * @see http://php.net/manual/fr/function.file-put-contents.php
     */
    function putContent(string $data = '', int $flags = 0, $context = null): bool
    {
        return file_put_contents($this->path, $data, $flags, $context);
    }

    /**
     * Return file content
     *
     * @param bool $use_include_path
     * @param Resource $context
     * @param int $offset
     * @param int|null $maxlen
     * @return false|string
     * @see http://php.net/manual/function.file-get-contents.php
     */
    function getContents(bool $use_include_path = FALSE, $context = null, int $offset = 0, int $maxlen = null)
    {
        return file_get_contents($this->path, $use_include_path, $context, $offset, $maxlen);
    }

    /**
     *  Write content to output buffer
     *
     * @param bool $use_include_path
     * @param $context
     * @return false|int
     * @see http://php.net/manual/function.readfile.php
     */
    function print(bool $use_include_path = false, $context = null) {
        return readfile($this->path, $use_include_path, $context);
    }


}