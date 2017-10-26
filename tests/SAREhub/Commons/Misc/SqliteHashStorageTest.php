<?php


namespace SAREhub\Commons\Misc;

use PHPUnit\Framework\TestCase;

class SqliteHashStorageTest extends TestCase
{

    public function testOpenAndClose()
    {
        $storage = new SqliteHashStorage(':memory:');
        $storage->open();
        $this->assertTrue($storage->isOpened());
        $storage->close();
        $this->assertFalse($storage->isOpened());
    }

    public function testInsertMulti()
    {
        $storage = new SqliteHashStorage(':memory:');
        $storage->open();
        $storage->insertMulti([
            'test1' => 'hash1',
            'test2' => 'hash2',
        ]);

        $this->assertEquals('hash1', $storage->findById('test1'));
        $this->assertEquals('hash2', $storage->findById('test2'));
    }

    public function testUpdateMulti()
    {
        $storage = new SqliteHashStorage(':memory:');
        $storage->open();
        $storage->insertMulti([
            'test1' => 'hash1',
            'test2' => 'hash2'
        ]);

        $storage->updateMulti([
            'test1' => 'hash3',
        ]);

        $this->assertEquals('hash3', $storage->findById('test1'));
        $this->assertEquals('hash2', $storage->findById('test2'));

    }
}
