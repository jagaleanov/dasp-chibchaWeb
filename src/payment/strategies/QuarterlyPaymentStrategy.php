<?php

namespace src\payment\strategies;

use src\payment\interface\PaymentStrategyInterface;

class QuarterlyPaymentStrategy implements PaymentStrategyInterface
{
    public function calculate($basePrice): float
    {
        $periodPrice =  ($basePrice / 4); //valor anual dividido en 4
        return  $periodPrice * 0.02 + $periodPrice; //precio del periodo + 2%
    }

    public function getNextPaymentDate(String $lastPaymentDate): String
    {
        $lastPaymentDate = strtotime($lastPaymentDate);
        $nextPaymentDate = date("Y-m-d", strtotime("+3 month", $lastPaymentDate));
        return $nextPaymentDate;
    }
}
