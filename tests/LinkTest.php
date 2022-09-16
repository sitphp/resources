<?php

namespace SitPHP\Resources\Tests;

use SitPHP\Doubles\TestCase;
use SitPHP\Resources\StandardFile;
use SitPHP\Resources\Link;

class LinkTest extends TestCase
{


    private $file_path = '/tmp/file';
    private $link_path = '/tmp/link';

    function testIsValid(){
        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);

        $this->assertTrue(Link::isValid($this->link_path));

        $link->delete();
        $file->delete();
    }

    function testCreate(){
        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);

        $this->assertTrue(file_exists($this->file_path));
        $this->assertTrue(is_link($this->link_path));
        $this->assertFalse(Link::create($this->link_path, $this->file_path));

        $link->delete();
        $file->delete();
    }

    function testDelete(){

        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);

        $link->delete();
        $this->assertFalse(file_exists($this->link_path));
        $file->delete();
    }

    function testAlreadyExistentLink(){
        $file = new StandardFile($this->file_path);
        symlink($this->file_path, $this->link_path);
        $link = new Link($this->link_path);
        $this->assertEquals($this->link_path, $link->getPath());

        $link->delete();
        $file->delete();
    }

    function testGetStat(){

        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);
        $stat = $link->getStat();
        $this->assertIsInt($stat['uid']);

        $link->delete();
        $file->delete();
    }

    function testGetInfo(){
        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);
        $info = $link->getInfo();
        $this->assertIsInt($info);
        $link->delete();
        $file->delete();
    }

    function testGetTargetPath(){
        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);
        $target_path = $link->getTargetPath();
        $this->assertEquals($this->file_path, $target_path);
        $link->delete();
        $file->delete();
    }

    function testInvalidLinkFileShouldFail(){
        $this->expectException(\InvalidArgumentException::class);
        $file = new StandardFile($this->file_path);
        try {
            new Link($this->file_path);
        } catch (\Exception $e){
            $file->delete();
            throw $e;
        }
    }

    function testGetTargetFile(){
        $file = new StandardFile($this->file_path);
        $link = new Link($this->link_path, $this->file_path);
        $target_file = $link->getTargetFile();
        $this->assertInstanceOf(StandardFile::class, $target_file);
        $this->assertEquals($this->file_path, $target_file->getPath());
        $link->delete();
        $file->delete();
    }
}