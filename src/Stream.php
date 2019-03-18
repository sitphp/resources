<?php

namespace SitPHP\Resources;


class Stream
{

    // Internal properties
    protected $handle;

    // User properties
    protected $path;
    protected $mode;
    protected $context;

    /**
     * Resource constructor.
     *
     * @param string $path
     * @param string|null $open_mode
     * @param null $context
     */
    function __construct(string $path, string $open_mode = null, $context = null)
    {
        $this->path = $path;
        $this->mode = $open_mode;
        $this->context = $context;
    }

    /**
     * Return handle
     *
     * @return resource
     * @throws \Exception
     */
    function getHandle(){
        if(!isset($this->handle)){
            if(!$handle = fopen($this->path, $this->mode, $this->context)){
                throw new \Exception('Could not open resource "'.$this->path.'"');
            }
            $this->handle = $handle;
        }
        return $this->handle;
    }

    /**
     * Return file name
     *
     * @return string
     */
    function getPath(){
        return $this->path;
    }

    function getMode(){
        return $this->mode;
    }

    function getContext(){
        return $this->context;
    }

    /*
     * Type methods
     */
    /**
     * Return file type
     *
     * @return string
     * @see http://php.net/manual/function.filetype.php
     */
    function getType()
    {
        if (preg_match('#^#', $this->path)) {
            $type = $this->resolveStatType();
        } else {
            $type = filetype($this->path);
        }
        return $type;
    }
    function resolveStatType(){
        $mode = $this->getStat()['mode'] & 0170000;
        if ($mode == 0010000) {
            $type = 'fifo';
        } else if ($mode == 0020000) {
            $type = 'char';
        } else if ($mode == 0040000) {
            $type = 'dir';
        } else if ($mode == 0060000) {
            $type = 'block';
        } else if ($mode == 0100000) {
            $type = 'file';
        } else if ($mode == 0120000) {
            $type = 'link';
        } else if ($mode == 0140000) {
            $type = 'socket';
        } else {
            $type = 'unknown';
        }
        return $type;
    }

    /**
     * Check if is named pipe
     *
     * @return bool
     */
    function isPipe()
    {
        return $this->getType() == 'fifo';
    }

    /**
     * Check if is character device
     * @return bool
     */
    function isChar()
    {
        return $this->getType() == 'char';
    }

    /**
     * Check if is dir
     *
     * @return bool
     * @see http://php.net/manual/function.is-dir.php
     */
    function isDir()
    {
        return $this->getType() == 'dir';
    }

    /**
     * CHeck if is standard file
     *
     * @return bool
     * @see http://php.net/manual/function.is-file.php
     */
    function isFile()
    {
        return $this->getType() == 'file';
    }

    /**
     * Check if is link
     *
     * @return bool
     * @see http://php.net/manual/function.is-link.php
     */
    function isLink()
    {
        return $this->getType() == 'link';
    }

    function isBlock(){
        return $this->getType() == 'block';
    }

    /**
     * Check if stream or url is a tty
     *
     * @return bool
     * @see http://php.net/manual/function.stream-isatty.php
     * @throws \Exception
     */
    function isatty()
    {
        if(version_compare(phpversion(), '7.2', '<')){
            return @posix_isatty($this->getHandle());
        }
        return stream_isatty($this->getHandle());
    }


    /**
     * Check if stream is local
     *
     * @return bool
     */
    function isLocal()
    {
        return stream_is_local($this->path);
    }

    function getResourceType()
    {
        return get_resource_type($this->getHandle());
    }

    /**
     * Return file stats
     *
     * @return array
     * @see http://php.net/manual/function.fstat.php
     * @throws \Exception
     */
    function getStat()
    {
        return fstat($this->getHandle());
    }

    /**
     * Clear file stat cache
     *
     * @param bool $clear_realpath_cache
     * @see http://php.net/manual/function.clearstatcache.php
     */
    function clearStatCache($clear_realpath_cache = false)
    {
        clearstatcache($clear_realpath_cache, $this->path);
    }

    /*
    * Read methods
    */

    /**
     * Return bytes of given length
     *
     * @param int $length
     * @return bool|string
     * @see http://php.net/manual/function.fread.php
     * @throws \Exception
     */
    function read(int $length)
    {
        return fread($this->getHandle(), $length);
    }

    /**
     * Return one byte
     *
     * @return bool|string
     * @see http://php.net/manual/function.fgetc.php
     * @throws \Exception
     */
    function readByte()
    {
        return fgetc($this->getHandle());
    }

    /**
     * Return line
     *
     * @param int|null $bytes
     * @return bool|string
     * @see http://php.net/manual/function.fgets.php
     * @throws \Exception
     */
    function readLine(int $bytes = null)
    {
        return isset($bytes) ? fgets($this->getHandle(), $bytes) : fgets($this->getHandle());
    }



    /*
     * Write methods
     */
    /**
     * Write message to file
     *
     * @param string $message
     * @return bool|int
     * @see http://php.net/manual/function.fwrite.php
     * @throws \Exception
     */
    function put(string $message)
    {
        return fwrite($this->getHandle(), $message);
    }

    function getContents(int $maxlength = -1, int $offset = -1)
    {
        return stream_get_contents($this->getHandle(), $maxlength, $offset);
    }

    /**
     * Check if pointer is at end of file
     *
     * @return bool
     * @see http://php.net/manual/function.feof.php
     * @throws \Exception
     */
    function isEndOfFile()
    {
        return feof($this->getHandle());
    }

    /**
     * Flush the output to file
     *
     * @return bool
     * @see http://php.net/manual/function.fflush.php
     * @throws \Exception
     */
    function flush()
    {
        return fflush($this->getHandle());
    }

    /**
     * Write remaining data to output buffer
     *
     * @return bool|int
     * @see http://php.net/manual/function.fpassthru.php
     * @throws \Exception
     */
    function passThru()
    {
        return fpassthru($this->getHandle());
    }

    /**
     * Move pointer to offset position
     *
     * @param int $offset
     * @param int $whence
     * @return int
     * @see http://php.net/manual/function.fseek.php
     * @throws \Exception
     */
    function setPointerPosition(int $offset, int $whence = SEEK_SET)
    {
        return fseek($this->getHandle(), $offset, $whence);
    }

    /**
     * Return file pointer position
     *
     * @return bool|int
     * @see http://php.net/manual/function.ftell.php
     * @throws \Exception
     */
    function getPointerPosition()
    {
        return ftell($this->getHandle());
    }

    /**
     * Rewind the position of file pointer
     *
     * @return bool
     * @see http://php.net/manual/function.rewind.php
     * @throws \Exception
     */
    function rewind()
    {
        return rewind($this->getHandle());
    }

    /**
     * Close file pointer
     *
     * @return bool
     * @see http://php.net/manual/function.fclose.php
     * @throws \Exception
     */
    function close()
    {
        return fclose($this->getHandle());
    }

}