<?php

namespace SitPHP\Resources\Tests;

use SitPHP\Doubles\TestCase;
use SitPHP\Resources\StandardFile;
use SitPHP\Resources\Stream;

class StreamTest extends TestCase
{

    protected $file_path = '/tmp/file';
    protected $stream_path = 'php://memory';
    
    function testOpen(){
        $stream = new Stream($this->stream_path);
        $this->assertIsResource($stream->open());
    }

    function testClose(){
        $stream = new Stream($this->stream_path);
        $handle = $stream->open();
        $this->assertTrue($stream->close());
        $this->assertFalse(is_resource($handle));
        $this->assertNull($stream->close());
    }

    function testGetHandleShouldReturnAResource(){
        $stream = new Stream($this->stream_path);
        $handle = $stream->open();
        $this->assertIsResource($handle);
        $stream->close();
    }

    function testGetPath(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertEquals($this->stream_path, $stream->getPath());
        $stream->close();
    }

    function writeCSV(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertTrue($stream->writeCSV(['test' => 1]));
        $stream->close();
    }

    function readCSV(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertEquals('', $stream->readCSV());
        $stream->close();
    }

    function testFlush(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertTrue($stream->flush());
        $stream->close();
    }

    function testParse(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertEquals('', $stream->parse(''));
        $stream->close();
    }

    function testCSV(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->writeCSV(['test']);
        $stream->rewind();
        $this->assertEquals(['test'], $stream->readCSV());
        $stream->close();
    }

    function testIsLocal(){
        $stream_1 = new Stream('php://stdin');
        $stream_1->open('r');
        $this->assertTrue($stream_1->isLocal());
        $stream_1->close();

    }

    function testIsAtty(){
        $stream = new Stream('/dev/tty', 'r');
        $stream->open();
        $this->assertTrue($stream->isatty());
        $stream->close();
    }

    function testGetResourceTypeShouldReturnType(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertEquals('stream', $stream->getResourceType());
        $stream->close();
    }

    function testIsEndOfFile(){
        $stream = new Stream($this->stream_path, 'w+');
        $stream->open();
        $stream->write('write');
        $this->assertFalse($stream->isEndOfFile());
        $stream->readLine();
        $this->assertTrue($stream->isEndOfFile());
        $stream->close();
    }

    function testWrite(){
        $stream = new Stream($this->stream_path, 'w+');
        $stream->open();
        $stream->write('write');
        $stream->rewind();
        $this->assertEquals('write', $stream->getStreamContents());
        $stream->close();
    }

    function testReadByte(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->write('byte');
        $stream->rewind();
        $this->assertEquals('b',$stream->readByte());
        $this->assertEquals('y',$stream->readByte());
        $this->assertEquals('t',$stream->readByte());
        $this->assertEquals('e',$stream->readByte());
        $this->assertEquals('',$stream->readByte());
        $stream->close();
    }

    function testReadBytes(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->write('bytes');
        $stream->rewind();
        $this->assertEquals('byte',$stream->read(4));
        $stream->close();
    }

    function testReadLine(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->write('bytes'."\n".'on'."\n".'lines');
        $stream->rewind();
        $stream->read(2);
        $this->assertEquals('tes'."\n",$stream->readLine());
        $stream->close();
    }

    function testPassThru(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->write('bytes'."\n".'on'."\n".'lines');
        $stream->rewind();
        $stream->read(2);
        ob_start();
        $bytes = $stream->passThru();
        $written = ob_get_clean();
        $this->assertEquals(12,$bytes);
        $this->assertEquals('tes'."\n".'on'."\n".'lines', $written);
        $stream->close();
    }

    function testPointer(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $stream->write('write');
        $stream->seek(1);
        $this->assertEquals('rite', $stream->getStreamContents());
        $this->assertEquals(5, $stream->tell());
        $stream->close();
    }

    function testTell(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        fwrite($stream->open(), 'write');
        $stream->seek(1);
        $this->assertEquals(1, $stream->tell());
        $stream->close();
    }

    function testSetTimeout(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertFalse($stream->setTimeOut('2'));
        $stream->close();
    }

    function testSetChunkSize(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertIsInt($stream->setChunkSize(2));
        $stream->close();
    }

    function testSetReadBuffer(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertIsInt($stream->setWriteBuffer(2));
        $stream->close();
    }

    function testSetWriteBuffer(){
        $stream = new Stream($this->stream_path);
        $stream->open();
        $this->assertIsInt($stream->setReadBuffer(2));
        $stream->close();
    }

    function testGetPathInfo(){
        $stream = new Stream($this->stream_path);
        $this->assertIsArray($stream->getPathInfo());
    }
    
    function testLock(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertTrue($file->lock(LOCK_EX));
        $file->close();
        $file->delete();
    }

    function testUnLock(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertTrue($file->lock(LOCK_EX));
        $this->assertTrue($file->unlock());
        $file->close();
        $file->delete();
    }

    function testSetBlocking(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertTrue($file->setBlocking(true));
        $this->assertTrue($file->unlock());
        $file->close();
        $file->delete();
    }

    function testSupportsLock(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertTrue($file->supportsLock());
        $file->close();
        $file->delete();
    }

    function testGetMetadata(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertIsArray($file->getMetadata());
        $file->close();
        $file->delete();
    }

    function testGetLine(){
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertEquals('', $file->getLine(0));
        $file->close();
        $file->delete();
    }

    function testAppendFilter(){
        stream_filter_register('test', Filter::class);
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertIsResource($file->appendFilter('test'));
        $file->close();
        $file->delete();
    }

    function testPrependFilter(){
        stream_filter_register('test', Filter::class);
        $file = new StandardFile($this->file_path);
        $file->open();
        $this->assertIsResource($file->prependFilter('test'));
        $file->close();
        $file->delete();
    }

    function testGetFilter(){
        stream_filter_register('test', Filter::class);
        $file = new StandardFile($this->file_path);
        $file->open();
        $file->prependFilter('test');
        $this->assertIsResource($file->getFilter('test'));
        $file->close();
        $file->delete();
    }

    function testRemoveFilter(){
        stream_filter_register('test', Filter::class);
        $file = new StandardFile($this->file_path);
        $file->open('w+');
        $file->prependFilter('string.rot13');
        $file->removeFilter('test');
        $this->assertNull($file->getFilter('test'));
        $file->close();
        $file->delete();
    }

    function testCopyToStream(){

        $file = new StandardFile($this->file_path);
        $file->open();
        $file->write('test');
        $file->rewind();
        $stream_handle = fopen($this->stream_path, 'r+');
        $this->assertEquals(4, $file->copyToStream($stream_handle, 4));
        $file->close();
        $file->delete();
        fclose($stream_handle);
    }

}

class Filter extends \php_user_filter {

}
