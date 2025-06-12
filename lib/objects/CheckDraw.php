<?php
//
//  Module: CheckDraw.php - G.J. Watson
//    Desc: Check Draw Object
// Version: 1.01
//

final class CheckDraw {
    private $ident;
    private $description;
    private $isActive;
    private $lastModified;
    
    private $numbers;
    private $specials;

    // New lottery (we have some data)
    public function __construct($arg1, $arg2, $arg3, $arg4) {
        $this->ident = $arg1;
        $this->description = $arg2;
        $this->isActive = $arg3;
        $this->lastModified = $arg4;
        $this->numbers = [];
        $this->specials = [];
    }

    public function addNumber($number) {
        $this->numbers[] = $number;
    }

    public function addSpecial($special) {
        $this->specials[] = $special;
    }

    public function getUserID() {
        return $this->ident;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getIsActive() {
        return $this->isActive;
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

    public function getCheckDrawAsArray() {
        $obj["id"] = $this->getUserID();
        $obj["description"] = $this->getDescription();
        $obj["is_active"] = $this->getIsActive();
        $obj["last_modified"] = $this->getLastModified();
        $obj["numbers"] = $this-> getNumbersAsArray();
        if (count($this->specials) > 0) {
            $obj["specials"] = $this-> getSpecialsAsArray();
        }
        return $obj;
    }
}
?>
