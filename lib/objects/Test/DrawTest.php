<?php
//
//  Module: DrawTest.php - G.J. Watson
//    Desc: Tests for Number Class
// Version: 1.00
//

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once("Draw.php");

final class DrawTest extends TestCase {

    private $draw;

    private $testDraw;
    private $testDrawDate;
    private $testLastModified;
    private $testNumbers;
    private $testSpecials;

    protected function setUp() {
        $this->testDraw         = 99;
        $this->testDrawDate     = "TestDate";
        $this->testLastModified = "TestLastModified";
        $this->testNumbers      = [1,2,3,4,5];
        $this->testSpecials     = [6,7];
        $this->draw             = new Draw($this->testDraw, $this->testDrawDate, $this->testLastModified);
    }

    protected function tearDown() {
        $this->number = NULL;
    }

    public function testDrawConstructorWorks() {
        print("\nFunction: testDrawConstructorWorks\n");
        // test everything set as expected in object
        $this->assertEquals($this->testDraw,      $this->draw->getDrawID());
        $this->assertEquals(0,             strcmp($this->testDrawDate, $this->draw->getDrawDate()));
        $this->assertEquals(0,             strcmp($this->testLastModified, $this->draw->getLastModified()));
        $this->assertEquals(1, (sizeof($this->draw->getNumbersAsArray()) == 0));
        $this->assertEquals(1, (sizeof($this->draw->getSpecialsAsArray()) == 0));
    }

    public function testDrawAddNumberWorks() {
        print("\nFunction: testDrawAddNumberWorks\n");
        foreach ($this->testNumbers as $number) {
            $this->draw->addNumber($number);
        }
        $arr = $this->draw->getNumbersAsArray();
        $this->assertEquals(5, sizeof($arr));
        $this->assertEquals($this->testNumbers[0], $arr[0]);
        $this->assertEquals($this->testNumbers[1], $arr[1]);
        $this->assertEquals($this->testNumbers[2], $arr[2]);
        $this->assertEquals($this->testNumbers[3], $arr[3]);
        $this->assertEquals($this->testNumbers[4], $arr[4]);

    }

    public function testDrawAddSpecialWorks() {
        print("\nFunction: testDrawAddSpecialWorks\n");
        foreach ($this->testSpecials as $special) {
            $this->draw->addSpecial($special);
        }
        $arr = $this->draw->getSpecialsAsArray();
        $this->assertEquals(2, sizeof($arr));
        $this->assertEquals($this->testSpecials[0], $arr[0]);
        $this->assertEquals($this->testSpecials[1], $arr[1]);
    }

    public function testDrawGetAsArrayWorks() {
        print("\nFunction: testDrawGetAsArrayWorks\n");
        foreach ($this->testNumbers as $number) {
            $this->draw->addNumber($number);
        }
        foreach ($this->testSpecials as $special) {
            $this->draw->addSpecial($special);
        }

        $obj = $this->draw->getDrawAsArray();
        $this->assertEquals($obj["draw"], $this->draw->getDrawID());
        $this->assertEquals(0, strcmp($obj["date"], $this->draw->getDrawDate()));
        $arr = $obj["nos"];
        $this->assertEquals(5, sizeof($arr));
        $arr = $obj["spc"];
        $this->assertEquals(2, sizeof($arr));
    }
}
?>
