<?php
//
//  Module: Draw.php - G.J. Watson
//    Desc: Draw Object
// Version: 1.02
//

final class Draw {
    private $draw;
    private $drawDate;
    private $lastModified;

    private $numbers;
    private $specials;

    // New lottery (we have some data)
    public function __construct($arg1, $arg2, $arg3) {
        $this->draw = $arg1;
        $this->drawDate = $arg2;
        $this->lastModified = $arg3;
        $this->numbers = [];
        $this->specials = [];
    }

    public function addNumber($number) {
        $this->numbers[] = $number;
    }

    public function addSpecial($special) {
        $this->specials[] = $special;
    }

    public function getDrawID() {
        return $this->draw;
    }

    public function getDrawDate() {
        return $this->drawDate;
    }

    public function getLastModified() {
        return $this->lastModified;
    }

    private function getNumbersArray($numbers) {
        $arr = [];
        foreach ($numbers as $item) {
            $arr[] = $item;
        }
        return $arr;
    }

    public function getNumbersAsArray() {
        return $this->getNumbersArray($this->numbers);
    }

    public function getSpecialsAsArray() {
        return $this->getNumbersArray($this->specials);
    }

    public function getDrawAsArray() {
        $obj["draw"] = $this->draw;
        $obj["date"] = $this->drawDate;
        $obj["numbers"] = $this->getNumbersAsArray();
        $obj["specials"] = $this->getSpecialsAsArray();
        return $obj;
    }
}
?>
