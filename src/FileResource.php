<?php

namespace SitPHP\Resources;

abstract class FileResource extends Resource
{

    /**
     * Returns canonicalized absolute pathname
     *
     * @return bool|string
     * @see http://php.net/manual/function.realpath.php
     */
    function getRealPath()
    {
        return realpath($this->path);
    }

    /**
     * Return parent path
     *
     * @param int $levels
     * @return string
     * @see http://php.net/manual/function.dirname.php
     */
    function getParentPath(int $levels = 1): string
    {
        return dirname($this->path, $levels);
    }

    function getName(string $suffix = null): string
    {
        return basename($this->path, $suffix);
    }

    /**
     * Change file group
     *
     * @param $group
     * @return bool
     * @see http://php.net/manual/function.chgrp.php
     */
    function changeGroup($group): bool
    {
        return chgrp($this->path, $group);
    }

    /**
     * Change file mode
     *
     * @param int $mode
     * @return bool
     * @see http://php.net/manual/function.chmod.php
     */
    function changeMode(int $mode): bool
    {
        return chmod($this->path, $mode);
    }

    /**
     * Change file owner
     *
     * @param $owner
     * @return bool
     * @see http://php.net/manual/function.chown.php
     */
    function changeOwner($owner): bool
    {
        return chown($this->path, $owner);
    }

    /**
     * Return last file access time
     *
     * @return bool|int
     * @see http://php.net/manual/function.fileatime.php
     */
    function getLastAccessTime()
    {
        return fileatime($this->path);
    }

    /**
     * Return file group
     *
     * @return bool|int
     * @see http://php.net/manual/function.filegroup.php
     */
    function getGroupId()
    {
        return filegroup($this->path);
    }

    /**
     * Return file inode number
     *
     * @return bool|int
     * @see http://php.net/manual/function.fileinode.php
     */
    function getInode()
    {
        return fileinode($this->path);
    }

    /**
     * Return last inode change time
     *
     * @return bool|int
     * @see http://php.net/manual/function.filectime.php
     */
    function getInodeChangeTime()
    {
        return filectime($this->path);
    }

    /**
     * Return last modification time
     *
     * @return bool|int
     * @see http://php.net/manual/function.filemtime.php
     */
    function getLastModified()
    {
        return filemtime($this->path);
    }

    /**
     * Return file owner
     *
     * @return bool|int
     * @see http://php.net/manual/function.fileowner.php
     */
    function getOwnerId()
    {
        return fileowner($this->path);
    }


    /**
     * Return file permission
     *
     * @return bool|int
     * @see http://php.net/manual/function.fileperms.php
     */
    function getPermissions()
    {
        return fileperms($this->path);
    }

    /**
     * Sets access and modification time of file
     *
     * @param int $time
     * @param int|null $access_time
     * @return bool
     * @see http://php.net/manual/function.touch.php
     */
    function touch(int $time, int $access_time = null): bool
    {
        return touch($this->path, $time, $access_time);
    }

    /**
     * Return file size
     *
     * @return bool|int
     * @see http://php.net/manual/function.filesize.php
     */
    function getSize()
    {
        return filesize($this->path);
    }

    /**
     * Rename file
     *
     * @param string $newname
     * @param null $context
     * @return bool
     * @see http://php.net/manual/function.rename.php
     */
    function rename(string $new_name, $context = null): bool
    {
        $path_parts =  explode(DIRECTORY_SEPARATOR, $this->path);
        array_pop($path_parts);
        $path_parts[] = $new_name;
        $new_path = implode(DIRECTORY_SEPARATOR, $path_parts);
        $success = isset($context) ? rename($this->path, $new_path, $context) : rename($this->path, $new_path);
        if($success){
            $this->path = $new_path;
        }
        return $success;
    }


    /**
     * Copy file to destination
     *
     * @param string $dest
     * @param null $context
     * @return bool
     * @see http://php.net/manual/function.copy.php
     */
    function copy(string $dest, $context = null): bool
    {
        return copy($this->path, $dest, $context);
    }

    function move(string $to, $context = null) : bool
    {
        if(!is_dir($to)){
            throw new \InvalidArgumentException('Invalid directory');
        }
        $path_parts =  explode(DIRECTORY_SEPARATOR, $this->path);
        $name = array_pop($path_parts);
        $new_path = $to.DIRECTORY_SEPARATOR.$name;
        $success = isset($context) ? rename($this->path, $new_path, $context) : rename($this->path, $new_path);
        if($success){
            $this->path = $new_path;
        }
        return $success;
    }

    /**
     * Delete file
     *
     * @param null $context
     * @return bool
     * @see http://php.net/manual/function.unlink.php
     */
    function delete($context = null): bool
    {
        return unlink($this->path, $context);
    }

    /**
     * Check if file exist and is readable
     *
     * @return bool
     * @see http://php.net/manual/function.is-readable.php
     */
    function isReadable(): bool
    {
        return is_readable($this->path);
    }

    /**
     * Check if file is writable
     *
     * @return bool
     * @see http://php.net/manual/function.is-writable.php
     */
    function isWritable(): bool
    {
        return is_writable($this->path);
    }

    function getStat(){
        return stat($this->path);
    }

    /**
     * Clear file stat cache
     *
     * @param bool $clear_realpath_cache
     * @see http://php.net/manual/function.clearstatcache.php
     */
    function clearStatCache(bool $clear_realpath_cache = false)
    {
        clearstatcache($clear_realpath_cache, $this->path);
    }

}