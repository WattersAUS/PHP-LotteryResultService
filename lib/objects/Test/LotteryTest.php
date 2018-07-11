<?php
//
//  Module: LotteryTest.php - G.J. Watson
//    Desc: Tests for Lottery Class
// Version: 1.01
//

declare(strict_types=1);

use PHPUnit\Framework\TestCase;

require_once("Lottery.php");

final class LotteryTest extends TestCase {

    private $lottery;

    private $testIdent;
    private $testDescription;
    private $testDraw;
    private $testNumbers;
    private $testUpperNumber;
    private $testNumbersTag;
    private $testSpecials;
    private $testUpperSpecial;
    private $testSpecialsTag;
    private $testIsBonus;
    private $testBaseUrl;
    private $testLastModified;
    private $testEndDate;

    private $testDrawIdent;
    private $testDrawDraw;
    private $testDrawDate;
    private $testDrawLastModified;
    private $testDrawNumbers;
    private $testDrawSpecials;
    private $draw; 

    private $testDrawIdent1;
    private $testDrawDraw1;
    private $testDrawDate1;
    private $testDrawLastModified1;
    private $testDrawNumbers1;
    private $testDrawSpecials1;
    private $draw1; 

    protected function setUp() {
        $this->testIdent         = 999;
        $this->testDescription   = "TestDesc";
        $this->testDraw          = 888;
        $this->testNumbers       = 5;
        $this->testUpperNumber   = 6;
        $this->testNumbersTag    = "NOS";
        $this->testSpecials      = 2;
        $this->testUpperSpecial  = 8;
        $this->testSpecialsTag   = "SPC";
        $this->testIsBonus       = True;
        $this->testBaseUrl       = "URL";
        $this->testLastModified  = "LastModified";
        $this->testEndDate       = "EndDate";
        $this->lottery= new Lottery($this->testIdent, 
                                    $this->testDescription, 
                                    $this->testDraw, 
                                    $this->testNumbers, 
                                    $this->testUpperNumber, 
                                    $this->testNumbersTag,
                                    $this->testSpecials, 
                                    $this->testUpperSpecial, 
                                    $this->testSpecialsTag,
                                    $this->testIsBonus,
                                    $this->testBaseUrl,
                                    $this->testLastModified,
                                    $this->testEndDate);
        $this->testDrawIdent        = 44;
        $this->testDrawDraw         = 99;
        $this->testDrawDate         = "TestDate";
        $this->testDrawLastModified = "TestLastModified";
        $this->testDrawNumbers      = [1,2,3,4,5];
        $this->testDrawSpecials     = [6,7];
        $this->draw                 = new Draw($this->testDrawIdent, $this->testDrawDraw, $this->testDrawDate, $this->testDrawLastModified);
        foreach ($this->testDrawNumbers as $number) {
            $this->draw->addNumber($number);
        }
        foreach ($this->testDrawSpecials as $special) {
            $this->draw->addSpecial($special);
        }
        $this->testDrawIdent1        = 55;
        $this->testDrawDraw1         = 110;
        $this->testDrawDate1         = "TestDate1";
        $this->testDrawLastModified1 = "TestLastModified";
        $this->testDrawNumbers1      = [8,9,10];
        $this->testDrawSpecials1     = [12];
        $this->draw1                 = new Draw($this->testDrawIdent1, $this->testDrawDraw1, $this->testDrawDate1, $this->testDrawLastModified1);
        foreach ($this->testDrawNumbers1 as $number) {
            $this->draw1->addNumber($number);
        }
        foreach ($this->testDrawSpecials1 as $special) {
            $this->draw1->addSpecial($special);
        }
    }

    protected function tearDown() {
        $this->author = NULL;
    }

    public function testLotteryConstructorWorks() {
        print("\nTEST: testLotteryConstructorWorks\n");
        $this->assertEquals($this->testIdent,                  $this->lottery->getLotteryID());
        $this->assertEquals(0, strcmp($this->testDescription,  $this->lottery->getDescription()));
        $this->assertEquals($this->testDraw,                   $this->lottery->getDraw());
        $this->assertEquals($this->testNumbers,                $this->lottery->getNumbers());
        $this->assertEquals($this->testUpperNumber,            $this->lottery->getUpperNumber());
        $this->assertEquals(0, strcmp($this->testNumbersTag,   $this->lottery->getNumbersTag()));
        $this->assertEquals($this->testSpecials,               $this->lottery->getSpecials());
        $this->assertEquals($this->testUpperSpecial,           $this->lottery->getUpperSpecial());
        $this->assertEquals(0, strcmp($this->testSpecialsTag,  $this->lottery->getSpecialsTag()));
        $this->assertEquals($this->testIsBonus,                $this->lottery->getIsBonus());
        $this->assertEquals(0, strcmp($this->testBaseUrl,      $this->lottery->getBaseURL()));
        $this->assertEquals(0, strcmp($this->testLastModified, $this->lottery->getLastModified()));
        $this->assertEquals(0, strcmp($this->testEndDate,      $this->lottery->getEndDate()));
        return;
    }

    public function testLotteryAddDrawWorks() {
        print("\nTEST: testLotteryAddDrawWorks\n");
        $this->lottery->addDraw($this->draw);
        $this->lottery->addDraw($this->draw1);



        $this->assertEquals(0, strcmp($this->testEndDate,      $this->lottery->getEndDate()));



        print_r($this->lottery->getLotteryAsArray());

        return;
    }

    // public function testLotteryGetLotteryAsArray() {
    //     print("\nTEST: testLotteryGetLotteryAsArray\n");
    //     $this->assertEquals($this->testIdent,                  $this->lottery->getLotteryID());
    //     $this->assertEquals(0, strcmp($this->testDescription,  $this->lottery->getDescription()));
    //     $this->assertEquals($this->testDraw,                   $this->lottery->getDraw());
    //     $this->assertEquals($this->testNumbers,                $this->lottery->getNumbers());
    //     $this->assertEquals($this->testUpperNumber,            $this->lottery->getUpperNumber());
    //     $this->assertEquals(0, strcmp($this->testNumbersTag,   $this->lottery->getNumbersTag()));
    //     $this->assertEquals($this->testSpecials,               $this->lottery->getSpecials());
    //     $this->assertEquals($this->testUpperSpecial,           $this->lottery->getUpperSpecial());
    //     $this->assertEquals(0, strcmp($this->testSpecialsTag,  $this->lottery->getSpecialsTag()));
    //     $this->assertEquals($this->testIsBonus,                $this->lottery->getIsBonus());
    //     $this->assertEquals(0, strcmp($this->testBaseUrl,      $this->lottery->getBaseURL()));
    //     $this->assertEquals(0, strcmp($this->testLastModified, $this->lottery->getLastModified()));
    //     $this->assertEquals(0, strcmp($this->testEndDate,      $this->lottery->getEndDate()));
    //     return;
    // }


    // public function testDrawGetAsArrayWorks() {
    //     print("\nFunction: testDrawGetAsArrayWorks\n");
    //     foreach ($this->testNumbers as $number) {
    //         $this->draw->addNumber($number);
    //     }
    //     foreach ($this->testSpecials as $special) {
    //         $this->draw->addSpecial($special);
    //     }




    //     $obj = $this->draw->getDrawAsArray();
    //     $this->assertEquals($obj["draw"], $this->draw->getDrawID());
    //     $this->assertEquals(0, strcmp($obj["date"], $this->draw->getDrawDate()));
    //     $arr = $obj["nos"];
    //     $this->assertEquals(5, sizeof($arr));
    //     $arr = $obj["spc"];
    //     $this->assertEquals(2, sizeof($arr));
    // }

}
?>
