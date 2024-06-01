<?php 

namespace Razan\belajar\php\mvc\Config;

use PHPUnit\Framework\TestCase;

class DatabaseTest extends TestCase
{
    public function testGetConnection()
    {
        $connection = Database::getConection();
        self::assertNotNull($connection);
    }

    public function testGetConnectionSingleton()
    {
        $connection1 = Database::getConection();
        $connection2 = Database::getConection();
        self::assertSame($connection1, $connection2);
    }
}