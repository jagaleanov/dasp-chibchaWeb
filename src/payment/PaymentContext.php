<?php

namespace src\payment;

use src\payment\interface\PaymentStrategyInterface;

class PaymentContext
{
    private $strategy;

    public function setStrategy(PaymentStrategyInterface $strategy)
    {
        $this->strategy = $strategy;
    }

    public function calculatePayment($basePrice): float
    {
        return $this->strategy->calculate($basePrice);
    }

    public function getNextPaymentDate(String $lastPaymentDate): String
    {
        return $this->strategy->getNextPaymentDate($lastPaymentDate);
    }
}
