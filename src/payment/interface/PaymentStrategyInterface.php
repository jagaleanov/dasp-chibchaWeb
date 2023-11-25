<?php

namespace src\payment\interface;

interface PaymentStrategyInterface
{
    public function calculate($basePrice): float;
    public function getNextPaymentDate(String $lastPaymentDate): String;
}
