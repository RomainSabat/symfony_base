<?php
namespace App\Tests\Service;
 
use App\Service\Calculator;
use PHPUnit\Framework\TestCase;
 
class CalculatorTest extends TestCase
{
    public function testAdd(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(2, 3);
        $this->assertEquals(5, $result);
    }

    public function testAddNegativeNumbers(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(-2, -3);
        $this->assertEquals(-5, $result);
    }

    public function testAddZero(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(0, 5);
        $this->assertEquals(5, $result);
    }

    public function testAddLargeNumbers(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(1000000, 2000000);
        $this->assertEquals(3000000, $result);
    }

    public function testAddMixedNumbers(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(-2, 3);
        $this->assertEquals(1, $result);
    }

    public function testAddSameNumbers(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(4, 4);
        $this->assertEquals(8, $result);
    }

    public function testAddWithZeroes(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(0, 0);
        $this->assertEquals(0, $result);
    }

    public function testAddNegativeAndZero(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(-5, 0);
        $this->assertEquals(-5, $result);
    }

    public function testAddLargeAndSmallNumbers(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(1000000, 1);
        $this->assertEquals(1000001, $result);
    }

    public function testAddBoundaryValues(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->add(PHP_INT_MAX, 0);
        $this->assertEquals(PHP_INT_MAX, $result);
    }


    public function testDivide(): void
    {
        $calculator = new Calculator();
 
        $result = $calculator->divide(10, 2);
        $this->assertEquals(5, $result);
 
        // Tester une exception
        $this->expectException(\InvalidArgumentException::class);
        $calculator->divide(10, 0);
    }
}