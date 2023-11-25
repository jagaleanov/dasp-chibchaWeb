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

// Controlador para gestionar dominios
class PaymentController extends Controller
{
    // Propiedad para el repositorio de dominios
    private $paymentModel, $hostModel, $operativeSystemModel, $hostPlanModel, $paymentPlanModel, $creditCardModel;

    // Constructor que inyecta el repositorio de usuarios
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
        try {

            $host = $this->hostModel->find($hostId);
            $paymentPlan = $this->paymentPlanModel->find($host->payment_plan_id);
            $hostPlan = $this->hostPlanModel->find($host->payment_plan_id);
            $operativeSystem = $this->operativeSystemModel->find($host->payment_plan_id);
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

            $yearPrice = $hostPlan->price + $operativeSystem->price;
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
                    header('Location:' . BASE_URL . '/customers/details');
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
            // print "<pre>";print_r($data);print "</pre>";
            $this->layoutService->view('payments/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    // Método para obtener todos los dominios
    // public function getAllPayments()
    // {
    //     try {
    //         $payments = $this->paymentModel->findAll();
    //         return $this->successResponse($payments);
    //     } catch (\Exception $e) {
    //         // En caso de error, se retorna un mensaje de error
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // Método para obtener un dominio por su ID
    // public function getPayment($id)
    // {
    //     try {
    //         $payment = $this->paymentModel->find($id);
    //         return $this->successResponse($payment);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // Método para actualizar un dominio por su ID
    // public function updatePayment($id)
    // {
    //     try {
    //         $data = $this->getInputData();

    //         // Validación de datos de entrada
    //         if (empty($data['host_id']) || empty($data['credit_card_customer_id']) || empty($data['credit_card_number']) || empty($data['amount'])) {
    //             return $this->errorResponse('Datos inválidos', self::HTTP_BAD_REQUEST);
    //         }

    //         $payment = $this->paymentModel->find($id);

    //         if (!$payment) {
    //             return $this->notFoundResponse();
    //         }

    //         $payment->host_id = $data['host_id'];
    //         $payment->credit_card_customer_id = $data['credit_card_customer_id'];
    //         $payment->credit_card_number = $data['credit_card_number'];
    //         $payment->amount = $data['amount'];
    //         $this->paymentModel->update($payment);

    //         return $this->successResponse(['payment' => $payment]);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }

    // Método para eliminar un dominio por su ID
    // public function deletePayment($id)
    // {
    //     try {
    //         $payment = $this->paymentModel->find($id);

    //         if (!$payment) {
    //             return $this->notFoundResponse();
    //         }

    //         $this->paymentModel->delete($payment);
    //         return $this->successResponse(['message' => 'Cliente eliminado exitosamente']);
    //     } catch (\Exception $e) {
    //         return $this->errorResponse($e->getMessage() . ' on ' . $e->getFile() . ' in line ' . $e->getLine() . '. ' . $e->getTraceAsString(), self::HTTP_INTERNAL_SERVER_ERROR);
    //     }
    // }
}
