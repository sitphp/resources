<?php

namespace SitPHP\Resources;


abstract class Resource
{


    /**
     * @var string
     */
    protected $path;

    function __construct(string $path){
        $this->path = $path;
    }

    function getPath(): string
    {
        return $this->path;
    }

    /**
     * Return path info
     *
     * @param int $options
     * @return mixed
     * @see http://php.net/manual/function.pathinfo.php
     */
    function getPathInfo(int $options = PATHINFO_DIRNAME | PATHINFO_BASENAME | PATHINFO_EXTENSION | PATHINFO_FILENAME)
    {
        return pathinfo($this->path, $options);
    }


}