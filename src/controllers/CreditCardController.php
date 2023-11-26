<?php

namespace src\controllers;

use src\services\ModelService;

class CreditCardController extends Controller
{
    private $creditCardModel;
    public function __construct()
    {
        parent::__construct();
        // $this->creditCardModel = ModelService::getInstance()->get('CreditCardModel');
    }

    public function validateCreditCard()
    {
        try {
            if (
                !isset($_POST['credit_card_number'])
            ) {
                $res = [
                    'success' => false,
                    'data' => null,
                ];
            } else {
                $number = $_POST['credit_card_number'];

                $creditCard = $this->creditCardModel->getCreditCardType($number);
                if (!$creditCard) {
                    $res = [
                        'success' => false,
                        'message' => 'Tarjeta invalida',
                    ];
                } else {
                    $res = [
                        'success' => true,
                        'data' => $creditCard,
                    ];
                }
            }
            print json_encode($res);
        } catch (\Exception $e) {
            $res = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            print json_encode($res);
        }
    }
}
