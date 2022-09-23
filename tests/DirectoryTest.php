<?php

namespace SitPHP\Resources\Tests;

use SitPHP\Doubles\TestCase;
use SitPHP\Resources\Directory;
use SitPHP\Resources\Link;
use SitPHP\Resources\StandardFile;

class DirectoryTest extends TestCase
{
    protected $file_path = '/tmp/dir';
    protected $dir_path = '/tmp/dir';
    protected $dir_created;


    function testIsValid()
    {
        $dir = new Directory($this->dir_path);
        $this->assertTrue(Directory::isValid($this->dir_path));
        $dir->delete();
    }

    function testCreateWithConstruct()
    {
        $dir = new Directory($this->dir_path);
        $this->assertTrue(is_dir($dir->getPath()));
        $dir->delete();
    }

    function testCreateAlreadyExistent()
    {
        new Directory($this->dir_path);
        $dir = new Directory($this->dir_path);
        $this->assertFalse(Directory::create($this->dir_path));
        $dir->delete();
    }

    function testCreateWithInvalidDirectoryShouldFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        try {
            $file = new StandardFile('/tmp/file');
            new Directory('/tmp/file');
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $file->delete();
        }
    }

    function testDelete()
    {
        $dir = new Directory($this->dir_path);
        new Directory('/tmp/dir/1');
        new Directory('/tmp/dir/2');
        new StandardFile('/tmp/dir/1/file');

        $dir->delete();
        $this->assertFalse(is_dir($this->dir_path));
    }

    function testGetName()
    {
        $dir = new Directory($this->dir_path);
        $this->assertEquals('dir', $dir->getName());
        $dir->delete();
    }

    function testGetFiles()
    {
        $dir = new Directory($this->dir_path);
        new Directory($this->dir_path . '/1');
        new Directory($this->dir_path . '/2');
        new StandardFile($this->dir_path . '/file');

        $files = $dir->getFiles();
        $this->assertIsArray($files);
        $this->assertInstanceOf(Directory::class, $files[0]);
        $this->assertInstanceOf(Directory::class, $files[1]);
        $this->assertInstanceOf(StandardFile::class, $files[2]);
        $dir->delete();
    }

    function testGetParentPath()
    {
        $dir = new Directory($this->dir_path);
        $this->assertEquals('/tmp', $dir->getParentPath());
        $dir->delete();
    }

    function testGetDiskFreeSpace()
    {
        $dir = new Directory($this->dir_path);
        $this->assertIsFloat($dir->getDiskFreeSpace());
        $dir->delete();
    }

    function testGetDiskTotalSpace()
    {
        $dir = new Directory($this->dir_path);
        $this->assertIsFloat($dir->getDiskTotalSpace());
        $dir->delete();
    }

    function testRead()
    {
        $dir = new Directory($this->dir_path);
        $dir->open();
        $this->assertEquals('.', $dir->read());
        $dir->close();
        $dir->delete();
    }

    function testReadWithoutOpenShouldFail()
    {
        $this->expectException(\LogicException::class);
        try {
            $dir = new Directory($this->dir_path);
            $dir->read();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $dir->delete();
        }
    }

    function testRewind()
    {
        $dir = new Directory($this->dir_path);
        $dir1 = new Directory($this->dir_path . '/1');
        $dir2 = new Directory($this->dir_path . '/2');
        $dir->open();
        $dir1->open();
        $dir2->open();
        $this->assertEquals('.', $dir->read());
        $this->assertEquals('..', $dir->read());
        $this->assertEquals('1', $dir->read());
        $dir->rewind();
        $this->assertEquals('.', $dir->read());
        $dir->close();
        $dir1->close();
        $dir2->close();
        $dir->delete();
    }

    function testRewindWithoutOpenShouldFail()
    {
        $this->expectException(\LogicException::class);
        try {
            $dir = new Directory($this->dir_path);
            $dir->rewind();
        } catch (\Exception $e) {
            throw $e;
        } finally {
            $dir->delete();
        }
    }

    function testScan()
    {
        $dir = new Directory($this->dir_path);
        $this->assertEquals(['.', '..'], $dir->scan());
        $dir->delete();
    }


    function testRename()
    {
        $dir = new Directory($this->dir_path);
        $this->assertTrue($dir->rename('new_dir'));
        $this->assertEquals('/tmp/new_dir', $dir->getPath());
        $this->assertTrue(is_dir('/tmp/new_dir'));
        $dir->delete();
    }

    function testMove()
    {
        $to = '/tmp/new_dir/1';
        Directory::create($to);
        $dir = new Directory($this->dir_path);
        $this->assertTrue($dir->move($to));
        $this->assertEquals($to, $dir->getPath());
        $this->assertTrue(is_dir('/tmp/new_dir/1'));
        $this->assertFalse(Directory::isValid($this->dir_path));
        $dir->delete();
    }

    function testMoveWithUndefinedDirectoryShouldFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $to = '/tmp/new_dir/1';
        $dir = new Directory($this->dir_path);
        $dir->move($to);
        $dir->delete();
    }

    function testCopy()
    {
        $to = new Directory('/tmp/new_dir');
        $dir = new Directory($this->dir_path);
        new StandardFile($this->dir_path . '/file');
        new Link($this->dir_path . '/link', $this->dir_path . '/file');
        new Directory($this->dir_path . '/1');

        $dir->copy('/tmp/new_dir');

        $this->assertTrue(is_dir('/tmp/new_dir'));
        $dir->delete();
        $to->delete();
    }

    function testCopyWithUndefinedDirectoryShouldFail()
    {
        $this->expectException(\InvalidArgumentException::class);
        $to = '/tmp/new_dir/1';
        $dir = new Directory($this->dir_path);
        $dir->copy($to);
    }

    function testOpen()
    {
        $dir = new Directory($this->dir_path);
        $this->assertIsResource($dir->open());
        $dir->delete();
    }

    function testClose()
    {
        $dir = new Directory($this->dir_path);
        $handle = $dir->open();
        $dir->close();
        $this->assertFalse(is_resource($handle));
        $dir->delete();
    }

    function testGetHandle()
    {
        $dir = new Directory($this->dir_path);
        $handle = $dir->open();
        $this->assertIsResource($handle);
        $dir->close();
        $dir->delete();
    }

}