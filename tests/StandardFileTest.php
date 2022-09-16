<?php

namespace SitPHP\Resources\Tests;

use SitPHP\Doubles\TestCase;
use SitPHP\Resources\Directory;
use SitPHP\Resources\StandardFile;

class StandardFileTest extends TestCase {

    protected $file_path = '/tmp/file';
    protected $new_file_path = '/tmp/new_file';
    protected $file_ext_path = '/tmp/file.txt';
    protected $new_file_ext_path = '/tmp/new_file.txt';
    protected $dir_path = '/tmp/dir';


    public function testConstruct(){
        $file = new StandardFile($this->file_path);
        $this->assertTrue(is_file($this->file_path));
        $file->delete();
    }

    public function testConstructWithInvalidFileShouldFail(){
        $this->expectException(\InvalidArgumentException::class);
        try {
            $dir = new Directory($this->dir_path);
            $file = new StandardFile($this->dir_path);
        } catch (\Exception $e){
            throw $e;
        } finally {
            $dir->delete();
        }
    }

    public function testIsUploaded()
    {
        $file = new StandardFile($this->file_path);
        $this->assertFalse($file->isUploaded($this->dir_path));
        $file->delete();
    }

    public function testMoveUploaded()
    {
        $file = new StandardFile($this->file_path);
        $this->assertFalse($file->moveUploaded($this->dir_path));
        $file->delete();
    }

    public function testGetMimeType()
    {
        $file = new StandardFile($this->file_ext_path);
        $this->assertEquals('text/plain', $file->getMimeType());
        $file->delete();
    }

    public function testIsValid()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue(StandardFile::isValid($this->file_path));
        $this->assertFalse(StandardFile::isValid($this->file_ext_path));
        $file->delete();
    }

    public function testGetExtension()
    {
        $file = new StandardFile($this->file_ext_path);
        $this->assertEquals('txt', $file->getExtension());
        $file->delete();
    }

    public function testIniParse()
    {
        $file = new StandardFile($this->file_ext_path);
        $this->assertEquals([], $file->iniParse());
        $file->delete();
    }

    public function testCreate()
    {
        $this->assertTrue(StandardFile::create($this->file_path));
        $this->assertFalse(StandardFile::create($this->file_path));

        $file = new StandardFile($this->file_path);
        $file->delete();
    }

    public function testRename()
    {
        $file = new StandardFile($this->file_path);
        $file->rename('new_file');
        $this->assertEquals($this->new_file_path, $file->getPath());
        $this->assertTrue(is_file($this->new_file_path));
        $file->delete();

        $file_ext = new StandardFile($this->file_ext_path);
        $file_ext->rename('new_file');
        $this->assertEquals($this->new_file_ext_path, $file_ext->getPath());
        $this->assertTrue(is_file($this->new_file_ext_path));
        $file_ext->delete();
    }

    public function testTruncate()
    {
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertTrue($file->truncate(0));
        $file->close();
        $file->delete();
    }

    public function testPrint()
    {
        $file = new StandardFile($this->file_path);
        $file->open();
        $file->write('test');
        ob_start();
        $file->print();
        $print = ob_get_clean();
        $file->close();
        $file->delete();
        $this->assertEquals('test', $print);
    }

    public function testGetContents()
    {
        $file = new StandardFile($this->file_path);
        $this->assertEquals('', $file->getContents());
        $file->delete();
    }

    public function testPutContent()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->putContent('test'));
        $file->delete();
    }

    public function testChangeGroup()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->changeGroup($file->getGroupId()));
        $file->delete();
    }

    public function testGetOwnerId()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getOwnerId());
        $file->delete();
    }

    public function testGetStat()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsArray($file->getStat());
        $file->delete();
    }

    public function testGetInodeChangeTime()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getInodeChangeTime());
        $file->delete();
    }



    public function testGetSize()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getSize());
        $file->delete();
    }

    public function testGetName()
    {
        $file = new StandardFile($this->file_path);
        $this->assertEquals('file' ,$file->getName());
        $file->delete();
    }

    public function testIsWritable()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->isWritable());
        $file->delete();
    }

    public function testIsReadable()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->isReadable());
        $file->delete();
    }

    public function testCopy()
    {
        $file = new StandardFile($this->file_path);
        $file->copy($this->new_file_path);
        $this->assertTrue(StandardFile::isValid($this->new_file_path));
        $new_file = new StandardFile($this->new_file_path);
        $file->delete();
        $new_file->delete();
    }


    public function testGetLastAccessTime()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getLastAccessTime());
        $file->delete();
    }

    public function testGetInode()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getInode());
        $file->delete();
    }

    public function testGetLastModified()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getLastModified());
        $file->delete();
    }

    public function testChangeOwner()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->changeOwner($file->getOwnerId()));
        $file->delete();
    }

    public function testTouch()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->touch(1000));
        $file->delete();
    }

    public function testGetGroupId()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getGroupId());
        $file->delete();
    }

    public function testDelete()
    {
        $file = new StandardFile($this->file_path);
        $file->delete();
        $this->assertFalse(file_exists($this->file_path));

    }

    public function testGetRealPath()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsString($file->getRealPath());
        $file->delete();
    }

    public function testGetParentPath()
    {
        $file = new StandardFile($this->file_path);
        $this->assertEquals('/tmp', $file->getParentPath());
        $file->delete();
    }

    public function testGetPermissions()
    {
        $file = new StandardFile($this->file_path);
        $this->assertIsInt($file->getPermissions());
        $file->delete();
    }

    public function testMove()
    {
        $file = new StandardFile($this->file_path);
        $dir = new Directory($this->dir_path);
        $file->move($this->dir_path);
        $this->assertFalse(StandardFile::isValid($this->file_path));
        $this->assertTrue(StandardFile::isValid($this->dir_path.'/file'));
        $dir->delete();
    }

    function testMoveToNonDirShouldFail(){
        $this->expectException(\InvalidArgumentException::class);
        try {
            $file = new StandardFile($this->file_path);
            $file->move($this->dir_path);
        } catch (\Exception $e){
            throw $e;
        } finally {
            $file->delete();
        }
    }

    public function testChangeMode()
    {
        $file = new StandardFile($this->file_path);
        $this->assertTrue($file->changeMode(0777));
        $file->delete();
    }

}