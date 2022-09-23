<?php

namespace SitPHP\Resources;

use Exception;

trait StreamableTrait
{
    /**
     * @var
     */
    protected $handle;
    /**
     * @var array
     */
    protected $filters = [];

    /**
     * Return handle
     *
     * @throws Exception
     */
    function open(string $open_mode = 'r+', bool $use_include_path = false, $context = null)
    {

        if ($this->handle === null) {
            $this->handle = fopen($this->path, $open_mode, $use_include_path, $context);
        }
        return $this->handle;
    }

    /**
     * Close stream pointer
     *
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.fclose.php
     */
    function close(): ?bool
    {
        if ($this->handle === null) {
            return null;
        }
        $success = fclose($this->handle);
        if ($success) {
            $this->handle = null;
        }
        return $success;
    }

    /**
     * @return string
     */
    function getResourceType(): string
    {
        $this->checkHandle(__METHOD__);
        return get_resource_type($this->handle);
    }

    /**
     * @return mixed
     */
    function getHandle()
    {
        return $this->handle;
    }

    /**
     * Read
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
     * @throws Exception
     * @see http://php.net/manual/function.fgetc.php
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
     * @throws Exception
     * @see http://php.net/manual/function.fgets.php
     */
    function readLine(int $bytes = null)
    {
        $this->checkHandle(__METHOD__);
        return isset($bytes) ? fgets($this->handle, $bytes) : fgets($this->handle);
    }


    /**
     * Check if stream is a tty
     *
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.stream-isatty.php
     */
    function isatty(): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_isatty($this->handle);
    }


    /**
     * Write message to stream
     *
     * @param string $message
     * @return bool|int
     * @throws Exception
     * @see http://php.net/manual/function.fwrite.php
     */
    function write(string $message)
    {
        $this->checkHandle(__METHOD__);
        return fwrite($this->handle, $message);
    }

    /**
     * Write array in CSV format to streal
     *
     * @param array $fields
     * @param string $delimiter
     * @param string $enclosure
     * @param string $escape_char
     * @return bool|int
     * @throws Exception
     * @see http://php.net/manual/function.fputcsv.php
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
     * @throws Exception
     * @see http://php.net/manual/function.fgetcsv.php
     */
    function readCSV(int $length = 0, string $delimiter = ",", string $enclosure = '"', string $escape = "\\")
    {
        $this->checkHandle(__METHOD__);
        return fgetcsv($this->handle, $length, $delimiter, $enclosure, $escape);
    }


    /**
     * Check if pointer is at end of stream
     *
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.feof.php
     */
    function isEndOfFile(): bool
    {
        $this->checkHandle(__METHOD__);
        return feof($this->handle);
    }

    /**
     * Flush the output to stream
     *
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.fflush.php
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
     * @throws Exception
     * @see http://php.net/manual/function.fpassthru.php
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
     * @throws Exception
     * @see http://php.net/manual/function.fseek.php
     */
    function seek(int $offset, int $whence = SEEK_SET): int
    {
        $this->checkHandle(__METHOD__);
        return fseek($this->handle, $offset, $whence);
    }

    /**
     * Rewind the position of stream pointer
     *
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.rewind.php
     */
    function rewind(): bool
    {
        $this->checkHandle(__METHOD__);
        return rewind($this->handle);
    }

    /**
     * Return stream pointer position
     *
     * @return bool|int
     * @throws Exception
     * @see http://php.net/manual/function.ftell.php
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
     * @throws Exception
     * @see http://php.net/manual/function.fscanf.php
     */
    function parse(string $format, ...$values)
    {
        $this->checkHandle(__METHOD__);
        return fscanf($this->handle, $format, ...$values);
    }

    /**
     * Truncate stream
     *
     * @param int $size
     * @return bool
     * @throws \Exception
     * @see http://php.net/manual/function.ftruncate.php
     */
    function truncate(int $size): bool
    {
        $this->checkHandle(__METHOD__);
        return ftruncate($this->handle, $size);
    }

    /**
     * Check if stream is local
     *
     * @return bool
     */
    function isLocal(): bool
    {
        return stream_is_local($this->path);
    }

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
    function print(bool $use_include_path = false, $context = null)
    {
        return readfile($this->path, $use_include_path, $context);
    }

    /**
     * @param int $maxlength
     * @param int $offset
     * @return false|string
     */
    function getStreamContents(int $maxlength = -1, int $offset = -1)
    {
        $this->checkHandle(__METHOD__);
        return stream_get_contents($this->handle, $maxlength, $offset);
    }

    /**
     * @param int $seconds
     * @param int $microseconds
     * @return bool
     */
    function setTimeOut(int $seconds, int $microseconds = 0): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_set_timeout($this->handle, $seconds, $microseconds);
    }

    /**
     * @param int $size
     * @return int
     */
    function setReadBuffer(int $size): int
    {
        $this->checkHandle(__METHOD__);
        return stream_set_read_buffer($this->handle, $size);
    }

    /**
     * @param int $size
     * @return int
     */
    function setWriteBuffer(int $size): int
    {
        $this->checkHandle(__METHOD__);
        return stream_set_write_buffer($this->handle, $size);
    }

    /**
     * @param int $size
     * @return false|int
     */
    function setChunkSize(int $size)
    {
        $this->checkHandle(__METHOD__);
        return stream_set_chunk_size($this->handle, $size);
    }

    /**
     * Lock stream
     *
     * @param int $operation
     * @return bool
     * @throws Exception
     * @see http://php.net/manual/function.flock.php
     */
    function lock(int $operation): bool
    {
        $this->checkHandle(__METHOD__);
        return flock($this->handle, $operation);
    }

    /**
     * Lock stream
     *
     * @return bool
     * @see http://php.net/manual/function.flock.php
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

    /**
     * @return bool
     */
    function supportsLock(): bool
    {
        $this->checkHandle(__METHOD__);
        return stream_supports_lock($this->handle);
    }

    /**
     * @return array
     */
    function getMetadata(): array
    {
        $this->checkHandle(__METHOD__);
        return stream_get_meta_data($this->handle);
    }

    /**
     * @param int $length
     * @param string $ending
     * @return false|string
     */
    function getLine(int $length, string $ending = "")
    {
        $this->checkHandle(__METHOD__);
        return stream_get_line($this->handle, $length, $ending);
    }

    /**
     * @param $stream_handle
     * @param int $length
     * @param int|null $offset
     * @return false|int
     */
    function copyToStream($stream_handle, int $length, int $offset = null)
    {
        $this->checkHandle(__METHOD__);
        return stream_copy_to_stream($this->handle, $stream_handle, $length, $offset);
    }

    /**
     * @param string $name
     * @param int $read_write
     * @param $params
     * @return false|resource
     */
    function appendFilter(string $name, int $read_write = STREAM_FILTER_READ, $params = null)
    {
        $this->checkHandle(__METHOD__);
        $filter = stream_filter_append($this->handle, $name, $read_write, $params);
        $this->filters[$name] = $filter;
        return $filter;
    }

    /**
     * @param string $name
     * @param int $read_write
     * @param $params
     * @return false|resource
     */
    function prependFilter(string $name, int $read_write = STREAM_FILTER_READ, $params = null)
    {
        $this->checkHandle(__METHOD__);
        $filter = stream_filter_prepend($this->handle, $name, $read_write, $params);
        $this->filters[$name] = $filter;
        return $filter;
    }

    /**
     * @param string $name
     * @return mixed|null
     */
    function getFilter(string $name)
    {
        return $this->filters[$name] ?? null;
    }

    /**
     * @param string $name
     * @return bool
     */
    function removeFilter(string $name): bool
    {
        $filter = $this->filters[$name] ?? null;
        if ($filter === null) {
            return false;
        }
        stream_filter_remove($filter);
        unset($this->filters[$name]);
        return true;
    }

    /**
     * @param string $method
     * @return void
     */
    protected function checkHandle(string $method)
    {
        if ($this->handle === null) {
            throw new \LogicException('Method "' . $method . '" requires a stream pointer. Run "open" method first.');
        }
    }
}