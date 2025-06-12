<?php
//
//  Module: CheckUser.php - G.J. Watson
//    Desc: Check User Object
// Version: 1.00
//

final class CheckUser {
    private $ident;
    private $name;
    private $email;
    private $isActive;
    private $lastModified;

    private $draws;

    // New lottery (we have some data)
    public function __construct($arg1, $arg2, $arg3, $arg4, $arg5) {
        $this->ident = $arg1;
        $this->name = $arg2;
        $this->email = $arg3;
        $this->isActive = $arg4;
        $this->lastModified = $arg5;
        $this->draws = [];
    }

    public function addCheckDraw($draw) {
        array_push($this->draws, $draw);
    }

    public function getUserID() {
        return $this->ident;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getIsActive() {
        return $this->isActive;
    }

    public function getLastModified() {
        return $this->lastModified;
    }

    private function getCheckDraws() {
        $arr = [];
        foreach ($this->draws as $draw) {
            $arr[] = $draw->getCheckDrawAsArray();
        }
        return $arr;
    }

    public function getCheckUserAsArray() {
        $obj["id"] = $this->getUserID();
        $obj["name"] = $this->getName();
        $obj["email"] = $this->getEmail();
        $obj["isActive"] = $this->getIsActive();
        $obj["last_modified"] = $this->getLastModified();
        if (count($this->draws) > 0) {
            $obj["draws"] = $this->getCheckDraws();
        }
        return $obj;
    }
}
?>
