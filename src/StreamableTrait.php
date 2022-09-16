<?php

namespace SitPHP\Resources;

use Exception;

trait StreamableTrait
{
    protected $handle;
    protected $filters = [];

    /**
     * Return handle
     *
     * @throws Exception
     */
    function open(string $open_mode = 'r+', bool $use_include_path = false, $context = null){

        if($this->handle === null){
            $this->handle = fopen($this->path, $open_mode, $use_include_path, $context);
        }
        return $this->handle;
    }

    /**
     * Close file pointer
     *
     * @return bool
     * @see http://php.net/manual/function.fclose.php
     * @throws Exception
     */
    function close(): ?bool
    {
        if($this->handle === null){
            return null;
        }
        $success = fclose($this->handle);
        if($success){
            $this->handle = null;
        }
        return $success;
    }

    function getResourceType(): string
    {
        $this->checkHandle(__METHOD__);
        return get_resource_type($this->handle);
    }

    /**
     * Outputs a file
     *
     * @param int|null $length
     * @return false|int
     * @throws Exception
     * @see http://php.net/manual/function.fread.php
     */
    function read(int $length = null)
    {
        $this->checkHandle(__METHOD__);
        return fread($this->handle, $length);
    }

    /**
     * Return one byte
     *
     * @return bool|string
     * @see http://php.net/manual/function.fgetc.php
     * @throws Exception
     */
    function readByte()
    {
        $this->checkHandle(__METHOD__);
        return fgetc($this->handle);
    }

    /**
     * Return line
     *
     * @param int|null $bytes
     * @return bool|string
     * @see http://php.net/manual/function.fgets.php
     * @throws Exception
     */
    function readLine(int $bytes = null)
    {
        $this->checkHandle(__METHOD__);
        return isset($bytes) ? fgets($this->handle, $bytes) : fgets($this->handle);
    }


    /**
     * Check if file is a tty
     *
     * @return bool
     * @see http://php.net/manual/function.stream-isatty.php
     * @throws Exception
     */
    function isatty(): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_isatty($this->handle);
    }


    /**
     * Write message to file
     *
     * @param string $message
     * @return bool|int
     * @see http://php.net/manual/function.fwrite.php
     * @throws Exception
     */
    function write(string $message)
    {
        $this->checkHandle(__METHOD__);
        return fwrite($this->handle, $message);
    }


    /*
     * CSV file methods
     */
    /**
     * Write array in CSV format to file
     *
     * @param array $fields
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape_char
     * @return bool|int
     * @see http://php.net/manual/function.fputcsv.php
     * @throws Exception
     */
    function writeCSV(array $fields, string $delimiter = ",", string $enclosure = '"', string $escape_char = "\\")
    {
        $this->checkHandle(__METHOD__);
        return fputcsv($this->handle, $fields, $delimiter, $enclosure, $escape_char);
    }


    /**
     * Return CSV line
     *
     * @param int $length
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape
     * @return array|false|null
     * @see http://php.net/manual/function.fgetcsv.php
     * @throws Exception
     */
    function readCSV(int $length = 0, string $delimiter = ",", string $enclosure = '"', string $escape = "\\")
    {
        $this->checkHandle(__METHOD__);
        return fgetcsv($this->handle, $length, $delimiter, $enclosure, $escape);
    }


    /**
     * Check if pointer is at end of file
     *
     * @return bool
     * @see http://php.net/manual/function.feof.php
     * @throws Exception
     */
    function isEndOfFile(): bool
    {
        $this->checkHandle(__METHOD__);
        return feof($this->handle);
    }

    /**
     * Flush the output to file
     *
     * @return bool
     * @see http://php.net/manual/function.fflush.php
     * @throws Exception
     */
    function flush(): bool
    {
        $this->checkHandle(__METHOD__);
        return fflush($this->handle);
    }


    /**
     * Write remaining data to output buffer
     *
     * @return bool|int
     * @see http://php.net/manual/function.fpassthru.php
     * @throws Exception
     */
    function passThru()
    {
        $this->checkHandle(__METHOD__);
        return fpassthru($this->handle);
    }


    /**
     * Move pointer to offset position
     *
     * @param int $offset
     * @param int $whence
     * @return int
     * @see http://php.net/manual/function.fseek.php
     * @throws Exception
     */
    function seek(int $offset, int $whence = SEEK_SET): int
    {
        $this->checkHandle(__METHOD__);
        return fseek($this->handle, $offset, $whence);
    }

    /**
     * Rewind the position of file pointer
     *
     * @return bool
     * @see http://php.net/manual/function.rewind.php
     * @throws Exception
     */
    function rewind(): bool
    {
        $this->checkHandle(__METHOD__);
        return rewind($this->handle);
    }

    /**
     * Return file pointer position
     *
     * @return bool|int
     * @see http://php.net/manual/function.ftell.php
     * @throws Exception
     */
    function tell()
    {
        $this->checkHandle(__METHOD__);
        return ftell($this->handle);
    }

    /**
     * Return parsed input from stream according to a format
     *
     * @param string $format
     * @param mixed ...$values
     * @return mixed
     * @see http://php.net/manual/function.fscanf.php
     * @throws Exception
     */
    function parse(string $format, ...$values)
    {
        $this->checkHandle(__METHOD__);
        return fscanf($this->handle, $format, ...$values);
    }

    /**
     * Check if file is local
     *
     * @return bool
     */
    function isLocal(): bool
    {
        return stream_is_local($this->path);
    }

    function getStreamContents(int $maxlength = -1, int $offset = -1)
    {
        $this->checkHandle(__METHOD__);
        return stream_get_contents($this->handle, $maxlength, $offset);
    }

    function setTimeOut(int $seconds, int $microseconds = 0): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_set_timeout($this->handle, $seconds, $microseconds);
    }

    function setReadBuffer(int $size): int
    {
        $this->checkHandle(__METHOD__);
        return stream_set_read_buffer($this->handle, $size);
    }

    function setWriteBuffer(int $size): int
    {
        $this->checkHandle(__METHOD__);
        return stream_set_write_buffer($this->handle, $size);
    }

    function setChunkSize(int $size){
        $this->checkHandle(__METHOD__);
        return stream_set_chunk_size($this->handle, $size);
    }

    /**
     * Lock stream
     *
     * @param int $operation
     * @return bool
     * @see http://php.net/manual/function.flock.php
     * @throws Exception
     */
    function lock(int $operation): bool
    {
        $this->checkHandle(__METHOD__);
        return flock($this->handle, $operation);
    }

    /**
     * Lock stream
     *
     * @param int $operation
     * @return bool
     * @see http://php.net/manual/function.flock.php
     * @throws Exception
     */
    function unlock(): bool
    {
        $this->checkHandle(__METHOD__);
        return flock($this->handle, LOCK_UN);
    }

    /**
     * @throws Exception
     */
    function setBlocking(bool $blocking): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_set_blocking($this->handle, $blocking);
    }

    function supportsLock(): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_supports_lock($this->handle);
    }

    function getMetadata(){
        $this->checkHandle(__METHOD__);
        return stream_get_meta_data($this->handle);
    }

    function getLine(int $length, string $ending = ""){
        $this->checkHandle(__METHOD__);
        return stream_get_line($this->handle, $length, $ending);
    }

    function copyToStream($stream_handle, int $length, int $offset = null){
        $this->checkHandle(__METHOD__);
        return stream_copy_to_stream($this->handle, $stream_handle, $length, $offset);
    }

    function appendFilter(string $filtername, int $read_write = STREAM_FILTER_READ, $params = null){
        $this->checkHandle(__METHOD__);
        $filter = stream_filter_append($this->handle, $filtername, $read_write, $params);
        $this->filters[$filtername] = $filter;
        return $filter;
    }

    function prependFilter(string $filtername, int $read_write = STREAM_FILTER_READ, $params = null){
        $this->checkHandle(__METHOD__);
        $filter = stream_filter_prepend($this->handle, $filtername, $read_write, $params);
        $this->filters[$filtername] = $filter;
        return $filter;
    }

    function getFilter(string $filtername){
        return $this->filters[$filtername] ?? null;
    }

    function removeFilter(string $filtername): bool{
        $filter = $this->filters[$filtername] ?? null;
        if($filter === null){
            return false;
        }
        stream_filter_remove($filter);
        unset($this->filters[$filtername]);
        return  true;
    }

    protected function checkHandle(string $method){
        if($this->handle === null){
            throw new \LogicException('Method "'.$method.'" requires a file pointer. Run "open" method first for that.');
        }
    }
}