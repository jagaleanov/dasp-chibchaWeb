<?php

namespace src\payment\strategies;

use src\payment\interface\PaymentStrategyInterface;

class MonthlyPaymentStrategy implements PaymentStrategyInterface
{
    public function calculate($basePrice): float
    {
        $periodPrice =  ($basePrice / 12); //valor anual dividido en 12
        return  $periodPrice * 0.02 + $periodPrice; //precio del periodo + 2%
    }

    public function getNextPaymentDate(String $lastPaymentDate): String
    {
        $lastPaymentDate = strtotime($lastPaymentDate);
        $nextPaymentDate = date("Y-m-d", strtotime("+1 month", $lastPaymentDate));
        return $nextPaymentDate;
    }
}
