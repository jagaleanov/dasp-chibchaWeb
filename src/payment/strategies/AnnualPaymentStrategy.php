<?php

namespace src\payment\strategies;

use src\payment\interface\PaymentStrategyInterface;

class AnnualPaymentStrategy implements PaymentStrategyInterface
{
    public function calculate($basePrice): float
    {
        return $basePrice; // Sin cambio para pago anual
    }

    public function getNextPaymentDate(String $lastPaymentDate): String
    {
        $lastPaymentDate = strtotime($lastPaymentDate);
        $nextPaymentDate = date("Y-m-d", strtotime("+1 year", $lastPaymentDate));
        return $nextPaymentDate;
    }
}
