<?php

namespace SitPHP\Resources\Tests;


use SitPHP\Doubles\TestCase;
use SitPHP\Resources\Directory;
use SitPHP\Resources\File;
use SitPHP\Resources\Link;
use SitPHP\Resources\Pipe;
use SitPHP\Resources\StandardFile;
use SitPHP\Resources\Stream;

class FileTest extends TestCase
{
    function testGetType(){
        $file  = new File(__FILE__);
        $this->assertEquals(File::TYPE_FILE, $file->getType());
    }
    function testIsPipe(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isPipe());
    }
    function testIsLink(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isLink());
    }
    function testIsChar(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isChar());
    }
    function testIsDir(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isDir());
    }
    function testIsStandardFile(){
        $file  = new File(__FILE__);
        $this->assertTrue($file->isStandardFile());
    }
    function testIsBloc(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isBloc());
    }
    function testIsExecutable(){
        $file  = new File(__FILE__);
        $this->assertFalse($file->isExecutable());
    }
    function testDelete(){
        new StandardFile('/tmp/file');
        new Directory('/tmp/dir');
        $file = new File('/tmp/file');
        $dir = new File('/tmp/dir');
        $file->delete();
        $dir->delete();
        $this->assertFalse(file_exists('/tmp/file'));
        $this->assertFalse(file_exists('/tmp/dir'));
    }
    function testBuild(){
        new StandardFile('/tmp/file');
        new Directory('/tmp/dir');
        new Link('/tmp/link', '/tmp/file');
        new Pipe('/tmp/pipe');
        $file = new File('/tmp/file');
        $dir = new File('/tmp/dir');
        $link = new File('/tmp/link');
        $pipe = new File('/tmp/pipe');
        $stream = new File('php://temp');

        $this->assertInstanceOf(StandardFile::class, $file->build());
        $this->assertInstanceOf(Directory::class, $dir->build());
        $this->assertInstanceOf(Link::class, $link->build());
        $this->assertInstanceOf(Pipe::class, $pipe->build());
        $this->assertFalse($stream->build());

        $link->delete();
        $file->delete();
        $dir->delete();
        $pipe->delete();
    }
}