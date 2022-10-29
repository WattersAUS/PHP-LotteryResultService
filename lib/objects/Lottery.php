<?php
//
//  Module: Lottery.php - G.J. Watson
//    Desc: Lottery Object
// Version: 1.03
//

require_once("Draw.php");

final class Lottery {
    private $ident;
    private $description;
    private $draw;
    private $numbers;
    private $upperNumber;
    private $numbersTag;
    private $specials;
    private $upperSpecial;
    private $specialsTag;
    private $isBonus;
    private $baseUrl;
    private $lastModified;
    private $endDate;

    private $draws;

    // New lottery (we have some data)
    public function __construct($arg1, $arg2, $arg3, $arg4, $arg5, $arg6, $arg7, $arg8, $arg9, $arg10, $arg11, $arg12, $arg13) {
        $this->ident = $arg1;
        $this->description = $arg2;
        $this->draw = $arg3;
        $this->numbers = $arg4;
        $this->upperNumber = $arg5;
        $this->numbersTag = $arg6;
        $this->specials = $arg7;
        $this->upperSpecial = $arg8;
        $this->specialsTag = $arg9;
        $this->isBonus = $arg10;
        $this->baseUrl = $arg11;
        $this->lastModified = $arg12;
        $this->endDate = $arg13;
        $this->draws = [];
    }

    public function addDraw($draw) {
        array_push($this->draws, $draw);
    }

    public function getLotteryID() {
        return $this->ident;
    }

    public function getDescription() {
        return $this->description;
    }

    public function getDraw() {
        return $this->draw;
    }

    public function getNumbers() {
        return $this->numbers;
    }

    public function getUpperNumber() {
        return $this->upperNumber;
    }

    public function getNumbersTag() {
        return $this->numbersTag;
    }

    public function getSpecials() {
        return $this->specials;
    }

    public function getUpperSpecial() {
        return $this->upperSpecial;
    }

    public function getSpecialsTag() {
        return $this->specialsTag;
    }

    public function getIsBonus() {
        return $this->isBonus;
    }

    public function getBaseURL() {
        return $this->baseUrl;
    }

    public function getLastModified() {
        return $this->lastModified;
    }

    public function getEndDate() {
        return $this->endDate;
    }

    private function getLotteryDraws() {
        $arr = [];
        foreach ($this->draws as $draw) {
            $arr[] = $draw->getDrawAsArray();
        }
        return $arr;
    }

    public function getLotteryAsArray() {
        $obj["id"] = $this->getLotteryID();
        $obj["description"] = $this->getDescription();
        $obj["last_draw"] = $this->getDraw();
        $obj["numbers"] = $this->getNumbers();
        $obj["number_upper_limit"] = $this->getUpperNumber();
        $obj["specials_used"] = $this->getSpecials();
        $obj["special_upper_limit"] = $this->getUpperSpecial();
        $obj["last_modified"] = $this->getLastModified();
        $obj["bonus_numbers"] = $this->getIsBonus();
        if (count($this->draws) > 0) {
            $obj["draws"] = $this->getLotteryDraws();
        }
        return $obj;
    }
}
?>
