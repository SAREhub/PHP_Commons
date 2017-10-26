<?php


namespace SAREhub\Commons\Misc;

use org\bovigo\vfs\vfsStream;
use org\bovigo\vfs\vfsStreamDirectory;
use PHPUnit\Framework\TestCase;

class SimpleSemaforTest extends TestCase
{

    /** @var vfsStreamDirectory */
    private $root;


    public function setUp()
    {
        $this->root = vfsStream::setup('tmp');
    }

    public function testLock()
    {
        $semafor = new SimpleSemafor($this->root->url() . '/semafor.lock');
        $this->assertTrue($semafor->lock());
        $this->assertTrue($semafor->isLocked());
    }

    public function testLockWhenSemforFileExists()
    {
        $semafor = new SimpleSemafor($this->root->url() . '/semafor.lock');
        $this->root->addChild(vfsStream::newFile('semafor.lock'));
        $this->assertTrue($semafor->isLocked());
        $this->assertFalse($semafor->lock());

    }
}
