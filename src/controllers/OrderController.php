<?php
namespace src\controllers;

use Exception;
use src\modules\menu\MenuController;
use src\services\ModelService;
use stdClass;
class OrderController extends Controller
{
    private $customerModel, $hostModel, $paymentModel, $creditCardModel, $operativeSystemModel, $hostPlanModel, $paymentPlanModel;

    public function __construct()
    {
        parent::__construct();
        $this->customerModel = ModelService::getInstance()->get('CustomerModel');
        $this->hostModel = ModelService::getInstance()->get('HostModel');
        $this->paymentModel = ModelService::getInstance()->get('PaymentModel');
        $this->creditCardModel = ModelService::getInstance()->get('CreditCardModel');
        $this->operativeSystemModel = ModelService::getInstance()->get('OperativeSystemModel');
        $this->hostPlanModel = ModelService::getInstance()->get('HostPlanModel');
        $this->paymentPlanModel = ModelService::getInstance()->get('PaymentPlanModel');
    }

    public function capturePostData($fields)
    {
        $postData = [];
        foreach ($fields as $field) {
            $postData[$field] = isset($_POST[$field]) ? $_POST[$field] : null;
        }
        return $postData;
    }

    public function newOrder()
    {
        try {
            if ($this->postService->get('submit')) {
                // Validación de datos de entrada
                $rules = [
                    [
                        'field' => 'name',
                        'label' => 'nombre',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'last_name',
                        'label' => 'apellido',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'email',
                        'label' => 'correo electrónico',
                        'rules' => ['required', 'email']
                    ],
                    [
                        'field' => 'password',
                        'label' => 'contraseña',
                        'rules' => ['required', 'min_length:6']
                    ],
                    [
                        'field' => 'address',
                        'label' => 'dirección',
                        'rules' => ['required']
                    ],
                    [
                        'field' => 'host_plan_id',
                        'label' => 'plan de hosting',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'operative_system_id',
                        'label' => 'sistema operativo',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'payment_plan_id',
                        'label' => 'plan de pago',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'credit_card_number',
                        'label' => 'número de tarjeta de crédito',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'credit_card_name',
                        'label' => 'nombre en la tarjeta de crédito',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'credit_card_month',
                        'label' => 'mes en la tarjeta de crédito',
                        'rules' => ['required', 'integer', 'min:1', 'max:12']
                    ],
                    [
                        'field' => 'credit_card_year',
                        'label' => 'año en la tarjeta de crédito',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'credit_card_code',
                        'label' => 'codigo de seguridad de la tarjeta de crédito',
                        'rules' => ['required', 'integer']
                    ],
                    [
                        'field' => 'credit_card_type',
                        'label' => 'tipo de la tarjeta de crédito',
                        'rules' => ['required', 'alpha']
                    ],
                    [
                        'field' => 'amount',
                        'label' => 'total',
                        'rules' => ['required', 'integer']
                    ],
                ];

                $validate = $this->validationService->validate($this->postService->get(), $rules);

                if ($validate->valid === true) {
                    $res = $this->createOrder($validate->sanitizedData);

                    if ($res->success) {
                        header('Location:' . BASE_URL . '/login');
                    } else {
                        $this->layoutService->setMessages([
                            'danger' => [$res->message],
                        ]);
                    }
                } else {
                    $this->layoutService->setMessages([
                        'danger' => $validate->errors,
                    ]);
                }
            }
            $operativeSystems = $this->operativeSystemModel->findAll();
            $hostPlans = $this->hostPlanModel->findAll();
            $paymentPlans = $this->paymentPlanModel->findAll();

            $data = [
                'post' => $this->postService,
                'operativeSystems' => $operativeSystems,
                'hostPlans' => $hostPlans,
                'paymentPlans' => $paymentPlans,
            ];

            $this->layoutService->setScript('
                $(document).ready(function () {
                    getTotal();
                    validateCreditCard();
                    $("#host_plan_id").change(function () {
                        getTotal();
                    });
                    $("#operative_system_id").change(function () {
                        getTotal();
                    });
                    $("#payment_plan_id").change(function () {
                        getTotal();
                    });
                    $("#credit_card_number").keyup(function () {
                        validateCreditCard();
                    });
                })

                function getTotal(){
					$.ajax({
						url: "' . BASE_URL . '/orders/amount",
						type: "POST",
						data: { 
                            host_plan: $("#host_plan_id").val(),
                            operative_system: $("#operative_system_id").val(),
                            payment_plan: $("#payment_plan_id").val(),
                        },
						success: function (resp) {
                            resp=JSON.parse(resp)
                            console.log(resp)
                            $("#totalValue").html(resp.data)
                            $("#amount").val(resp.data)
                            
						}
					});
                }

                function validateCreditCard(){
					$.ajax({
						url: "' . BASE_URL . '/credit-cards/validate",
						type: "POST",
						data: { 
                            credit_card_number: $("#credit_card_number").val(),
                        },
						success: function (resp) {
                            resp=JSON.parse(resp)
                            console.log(resp)
                            $("#credit_card_type").val(resp.data)
                            
						}
					});
                }
            ');

            $menu = new MenuController();
            $this->layoutService->setModule('navBar',$menu->index());
            $this->layoutService->view('orders/new', $data);
        } catch (\Exception $e) {
            print_r($e);
        }
    }

    private function createOrder($data)
    {
        try {

            if ($this->probabilisticFail(0)) {
                throw new Exception('Transaccion fallida');
            }

            // Iniciar una transacción
            $this->customerModel->beginTransaction();

            $customer = new stdClass();
            $customer->name = $data['name'];
            $customer->last_name = $data['last_name'];
            $customer->email = $data['email'];
            $customer->password = password_hash($data['password'], PASSWORD_DEFAULT);
            $customer->address = $data['address'];

            $customer = $this->customerModel->save($customer);
            if (!$customer) {
                $this->customerModel->rollback();
                throw new Exception('Datos de cliente inválidos');
            }

            $creditCard = new stdClass();
            $creditCard->customer_id = $customer->id;
            $creditCard->number = $data['credit_card_number'];
            $creditCard->type = $data['email'];
            $creditCard->expiration_year = $data['credit_card_year'];
            $creditCard->expiration_month = $data['credit_card_month'];
            $creditCard->security_code = $data['credit_card_code'];
            $creditCard->name = $data['credit_card_name'];

            $creditCard = $this->creditCardModel->save($creditCard);
            if (!$creditCard) {
                $this->customerModel->rollback();
                throw new Exception('Tarjeta de crédito inválida');
            }

            $host = new stdClass();
            $host->customer_id = $customer->id;
            $host->host_plan_id = $data['host_plan_id'];
            $host->payment_plan_id = $data['payment_plan_id'];
            $host->operative_system_id = $data['operative_system_id'];

            $host = $this->hostModel->save($host);
            if (!$customer) {
                $this->customerModel->rollback();
                throw new Exception('Datos de host inválidos');
            }

            $payment = new stdClass();
            $payment->host_id = $host->id;
            $payment->credit_card_customer_id = $creditCard->customer_id;
            $payment->credit_card_number = $creditCard->number;
            $payment->amount = $data['amount'];

            $payment = $this->paymentModel->save($payment);
            if (!$payment) {
                $this->customerModel->rollback();
                throw new Exception('Datos de pago inválidos');
            }

            // Confirmar la transacción
            $this->customerModel->commit();

            return (object)[
                'success' => true,
                'data' => ['customer' => $customer, 'creditCard' => $creditCard, 'host' => $host, 'payment' => $payment],
            ];
        } catch (\Exception $e) {
            return (object)[
                'success' => false,
                'message' => $e->getMessage(),
            ];
        }
    }

    public function getAmount()
    {
        try {
            // Validación de datos de entrada
            if (
                !isset($_POST['host_plan']) || empty($_POST['host_plan'])
                || !isset($_POST['operative_system']) || empty($_POST['operative_system'])
                || !isset($_POST['payment_plan']) || empty($_POST['payment_plan'])
            ) {
                $res = [
                    'success' => false,
                    'data' => 0,
                ];
                print json_encode($res);
            } else {
                $hostingPlanId = $_POST['host_plan'];
                $operativeSystemId = $_POST['operative_system'];
                $paymentPlanId = $_POST['payment_plan'];
                $amount = 0;

                if ($hostingPlanId != 'null' && $operativeSystemId != 'null' && $paymentPlanId != 'null') {
                    if ($hostingPlanId > 1) {
                        $amount += 1000;
                    } else {
                        $amount += 1500;
                    }

                    if ($operativeSystemId > 1) {
                        $amount += 1000;
                    } else {
                        $amount += 1500;
                    }

                    if ($paymentPlanId > 1) {
                        $amount += 1000;
                    } else {
                        $amount += 1500;
                    }
                    $res = [
                        'success' => true,
                        'data' => $amount,
                    ];
                }
                print json_encode($res);
            }
        } catch (\Exception $e) {

            $res = [
                'success' => false,
                'message' => $e->getMessage(),
            ];
            print json_encode($res);
        }
    }

    private function probabilisticFail($probability)
    {
        $randomNumber = mt_rand(0, 100);
        return $randomNumber < $probability;
    }
}