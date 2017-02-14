<?php
class vietlott{
    public $minNumber=0;
    public $maxNumber=75;

    function getAllNumbers(){

    }

    function getRandom(){
        $number= rand($this->minNumber,$this->maxNumber);
        return $number;

    }

    function getRandomList($count){
        $result=array();
        while($count>0){
            $number=$this->getRandom();
            if(!isset($result[$number])){
                $result[$number]=$number;
                $count--;
            }
        }
        return $result;
    }
}