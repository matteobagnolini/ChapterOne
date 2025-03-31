<?php
use PHPUnit\Framework\TestCase;

require_once __DIR__ . '/../db/database.php';

class SimpleTest extends TestCase {

    public function testAddition() {
        $sum = 2 + 2;
        $this->assertEquals(4, $sum);
    }

    public function testString() {
        $string = 'Hello, World!';
        $this->assertStringContainsString('World', $string);
    }

 
}
