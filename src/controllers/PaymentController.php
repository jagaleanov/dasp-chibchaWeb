<?php

// Espacio de nombres utilizado por el controlador
namespace src\controllers;

// Importaciones de otras clases que se usarán en el controlador

use Exception;
use src\modules\menu\MenuController;
use src\payment\PaymentContext;
use src\payment\strategies\AnnualPaymentStrategy;
use src\payment\strategies\BiannualPaymentStrategy;
use src\payment\strategies\MonthlyPaymentStrategy;
use src\payment\strategies\QuarterlyPaymentStrategy;
use src\services\ModelService;
use stdClass;

class PaymentController extends Controller
{
    private $paymentModel, $hostModel, $operativeSystemModel, $hostPlanModel, $paymentPlanModel, $creditCardModel;

    public function __construct()
    {
        parent::__construct();
        $this->paymentModel = ModelService::getInstance()->get('PaymentModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->operativeSystemModel = ModelService::getInstance()->get('OperativeSystemModel');
        $this->hostPlanModel = ModelService::getInstance()->get('HostPlanModel');
        $this->paymentPlanModel = ModelService::getInstance()->get('PaymentPlanModel');
        $this->creditCardModel = ModelService::getInstance()->get('CreditCardModel');
    }

    public function newPayment($hostId)
    {
        if (!$this->aclService->isRoleIn([1])) {
            $_SESSION['systemMessages'] = [
                'danger'=>'Acceso restringido.'
            ];
            header('Location:' . BASE_URL . '/home');
            exit;
        }

        try {

            $host = $this->hostModel->find($hostId);
            $paymentPlan = $this->paymentPlanModel->find($host->payment_plan_id);
            $hostPlan = $this->hostPlanModel->find($host->payment_plan_id);
            $operativeSystem = $this->operativeSystemModel->find($host->operative_system_id);
            $creditCard = $this->creditCardModel->findByCustomerId($host->customer_id);
            $lastPayment = $this->paymentModel->findHostLastPayment($hostId);

            $paymentContext = new PaymentContext();

            if ($paymentPlan->name == 'Mensual') {
                $paymentStrategy = new MonthlyPaymentStrategy();
            } elseif ($paymentPlan->name == 'Trimestral') {
                $paymentStrategy = new QuarterlyPaymentStrategy();
            } elseif ($paymentPlan->name == 'Semestral') {
                $paymentStrategy = new BiannualPaymentStrategy();
            } elseif ($paymentPlan->name == 'Anual') {
                $paymentStrategy = new AnnualPaymentStrategy();
            }

            $paymentContext->setStrategy($paymentStrategy);

            $yearPrice = $hostPlan->price + 
            $operativeSystem->price;
            $periodicAmount = $paymentContext->calculatePayment($yearPrice);
            $nextPaymentDate = $paymentContext->getNextPaymentDate($lastPayment->created_at);

            if ($this->postService->get('submit')) {

                $payment = new stdClass();
                $payment->host_id = $host->id;
                $payment->credit_card_customer_id = $creditCard->customer_id;
                $payment->credit_card_number = $creditCard->number;
                $payment->amount = $periodicAmount;

                $payment = $this->paymentModel->save($payment);
                if ($payment) {
                    $_SESSION['systemMessages'] = [
                        'success'=>'Pago registrado.'
                    ];
                    header('Location:' . BASE_URL . '/customers/details');
                    exit;
                }else{
                    throw new Exception('Error durante el pago');
                }
            }

            $data = [
                'active' => strtotime($nextPaymentDate) < strtotime('NOW'),
                'post' => $this->postService,
                'host' => $host,
                'hostPlan' => $hostPlan,
                'paymentPlan' => $paymentPlan,
                'operativeSystem' => $operativeSystem,
                'amount' => $periodicAmount,
                'creditCard' => $creditCard,
                'lastPayment' => $lastPayment,
                'nextPaymentDate' => $nextPaymentDate,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('payments/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    public function getAllPayments()
    {
        try {

            $payments = $this->paymentModel->findAll();

            $data = [
                'post' => $this->postService,
                'payments' => $payments,
            ];
            $menu = new MenuController();
            $this->layoutService->setModule('navBar', $menu->index());
            $this->layoutService->view('payments/list', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }
}
