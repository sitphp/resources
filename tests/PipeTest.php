<?php

namespace SitPHP\Resources\Tests;

use InvalidArgumentException;
use SitPHP\Doubles\TestCase;
use SitPHP\Resources\Directory;
use SitPHP\Resources\File;
use SitPHP\Resources\Pipe;
use SitPHP\Resources\StandardFile;

class PipeTest extends TestCase
{
    protected $pipe_path = '/tmp/pipe';
    protected $dir_path = '/tmp/dir';


    function testCreate()
    {
        $pipe = new Pipe($this->pipe_path);
        $file = new File($this->pipe_path);
        $this->assertTrue($file->isPipe());
        $this->assertFalse(Pipe::create($this->pipe_path));
        $pipe->delete();
    }

    function testIsValid()
    {
        $this->assertFalse(Pipe::isValid($this->pipe_path));
        $file = new Directory($this->dir_path);
        $pipe = new Pipe($this->pipe_path);
        $this->assertTrue(Pipe::isValid($this->pipe_path));
        $this->assertFalse(Pipe::isValid($this->dir_path));
        $pipe->delete();
        $file->delete();
    }

    function testPipeOnExistentFile()
    {
        posix_mkfifo($this->pipe_path, 0666);
        $pipe = new Pipe($this->pipe_path);
        $pipe->open();
        $pipe->write('hello');
        $this->assertEquals('hello', $pipe->read(5));
        $pipe->close();
        $pipe->delete();
    }


    function testPipeOnInvalidFileShouldFail()
    {
        $this->expectException(InvalidArgumentException::class);
        $file = new StandardFile($this->pipe_path);
        try {
            new Pipe($this->pipe_path);
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $file->delete();
        }
    }
}