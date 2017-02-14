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
    function filterUserByNumber() {
        $agr=func_num_args();
        switch($agr){
            case 1:
                $sql="SELECT * FROM `vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0);
                break;
                break;
            case 2:
              $sql="SELECT * FROM
              (SELECT * FROM `vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0).") AS table1
                  WHERE table1.number1=".func_get_arg(1)." or table1.number2=".func_get_arg(1)." or table1.number3=".func_get_arg(1);
                break;
            case 3:
                $sql="SELECT * from
             (SELECT * FROM
              (SELECT * FROM `vietlott` AS table1 WHERE table1.number1 =".func_get_arg(0)." OR table1.number2 =".func_get_arg(0)." OR table1.number3 =".func_get_arg(0).") AS table1
                  WHERE table1.number1=".func_get_arg(1)." or table1.number2=".func_get_arg(1)." or table1.number3=".func_get_arg(1).")  AS table3
                    WHERE table3.number1=".func_get_arg(2)." OR table3.number2=".func_get_arg(2)." OR table3.number3=".func_get_arg(2)."";
                break;
            default:
                $sql="SELECT * from `vietlott`";
                break;

        }
        return $sql;
    }
}