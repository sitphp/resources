<?php

use Doublit\TestCase;
use SitPHP\Resources\Stream;

class StreamTest extends TestCase
{
    function testGetHandleShouldReturnAResource(){
        $stream = new Stream('php://memory');
        $this->assertIsResource($stream->getHandle());
    }
    function testGetPath(){
        $stream = new Stream('php://memory');
        $this->assertEquals('php://memory', $stream->getPath());
    }
    function testGetMode(){
        $stream = new Stream('php://memory', 'r+');
        $this->assertEquals('r+', $stream->getMode());
    }
    function testGetContext(){
        $context = stream_context_create();
        $stream = new Stream('php://memory', 'r+', $context);
        $this->assertEquals($context, $stream->getContext());
    }

    /*
     * Test is local
     */
    function testIsLocal(){
        $stream_1 = new Stream('php://stdin');
        $stream_2 = new Stream('http://www.example.com');
        $this->assertTrue($stream_1->isLocal());
        $this->assertFalse($stream_2->isLocal());
    }

    /*
     * Test types
     */
    function testIsPipe(){
        $stream = new Stream('php://stdin');
        $this->assertTrue($stream->isPipe());
    }
    function testIsFile(){
        $stream = new Stream(__FILE__, 'r');
        $this->assertTrue($stream->isFile());
    }
    function testIsDir(){
        $stream = new Stream(__DIR__, 'r');
        $this->assertTrue($stream->isDir());
    }
    function testIsBlock(){
        $stream = new Stream(__FILE__, 'r');
        $this->assertFalse($stream->isBlock());
    }
    function testIsChar(){
        $stream = new Stream(__FILE__, 'r');
        $this->assertFalse($stream->isChar());
    }
    function testIsLink(){
        $stream = new Stream(__FILE__, 'r');
        $this->assertFalse($stream->isLink());
    }
    function testIsAtty(){
        $stream = new Stream(__FILE__, 'r');
        $this->assertFalse($stream->isatty());
    }
    function testGetResourceTypeShouldReturnType(){
        $stream = new Stream('php://memory');
        $this->assertEquals('stream', $stream->getResourceType());
    }


    /*
   * Test is end of file
   */
    function testIsEndOfFile(){
        $stream = new Stream('php://memory', 'w+');
        $stream->put('write');
        $this->assertFalse($stream->isEndOfFile());
        $stream->readLine();
        $this->assertTrue($stream->isEndOfFile());
    }


    /*
     * Test write
     */
    function testWrite(){
        $stream = new Stream('php://memory', 'w+r+');
        $stream->put('write');
        $stream->rewind();
        $this->assertEquals('write', $stream->getContents());
    }


    /*
     * Test read
     */
    function testReadByte(){
        $stream = new Stream('php://memory', 'w+r+');
        $stream->put('byte');
        $stream->rewind();
        $this->assertEquals('b',$stream->readByte());
        $this->assertEquals('y',$stream->readByte());
        $this->assertEquals('t',$stream->readByte());
        $this->assertEquals('e',$stream->readByte());
        $this->assertEquals('',$stream->readByte());
    }

    function testReadBytes(){
        $stream = new Stream('php://memory', 'w+r+');
        $stream->put('bytes');
        $stream->rewind();
        $this->assertEquals('byte',$stream->readBytes(4));
    }

    function testReadLine(){
        $stream = new Stream('php://memory', 'w+r+');
        $stream->put('bytes'."\n".'on'."\n".'lines');
        $stream->rewind();
        $stream->readBytes(2);
        $this->assertEquals('tes'."\n",$stream->readLine());
    }

    /*
     * Test pass thru
     */
    function testPassThru(){
        $stream = new Stream('php://memory', 'w+r+');
        $stream->put('bytes'."\n".'on'."\n".'lines');
        $stream->rewind();
        $stream->readBytes(2);
        $this->assertEquals(12,$stream->passThru('tes'."\n".'on'."\n".'lines'));
    }

    /*
     * Test pointer
     */

    function testPointer(){
        $stream = new Stream('php://memory', 'r+w+');
        $stream->put('write');
        $stream->seek(1);
        $this->assertEquals('rite', $stream->getContents());
        $this->assertEquals(5, $stream->tell());
    }

    /*
     * Test tell
     */
    function testTell(){
        $stream = new Stream('php://memory', 'w+');
        fwrite($stream->getHandle(), 'write');
        $stream->seek(1);
        $this->assertEquals(1, $stream->tell());
    }

    /*
     * Test close
     */
    function testClose(){
        $stream = new Stream('php://memory');
        $stream->close();
        $this->assertFalse(is_resource($stream->getHandle()));
    }
}
