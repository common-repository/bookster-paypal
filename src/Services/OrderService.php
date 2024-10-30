<?php
namespace Bookster_Paypal\Services;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster_Paypal\Features\Utils\PaypalUtils;
use Bookster\Features\Utils\Decimal;
use Bookster\Features\Enums\TransactionStatusEnum;
use Bookster\Features\Enums\PaymentGatewayEnum;
use Bookster\Models\TransactionModel;
use Bookster\Services\BaseService;
use Bookster\Services\TransactionsService;
use Bookster_Paypal\Services\PaypalSettingsService;

/**
 * Paypal Order Service
 *
 * @method static OrderService get_instance()
 */
class OrderService extends BaseService {
    use SingletonTrait;

    /** @var PaypalSettingsService */
    private $paypal_settings_service;
    /** @var TransactionsService */
    private $transactions_service;

    protected function __construct() {
        $this->paypal_settings_service = PaypalSettingsService::get_instance();
        $this->transactions_service    = TransactionsService::get_instance();
    }

    /**
     * Create order
     *
     * @param string  $transaction_secret_id
     * @param Decimal $amount
     * @param string  $service_name
     * @return array
     */
    public function create_order( $transaction_secret_id, $amount, $service_name ) {
        $response = wp_remote_request(
            $this->paypal_settings_service->get_api_url() . '/v2/checkout/orders',
            [
                'method'  => 'POST',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->paypal_settings_service->get_access_token(),
                ],
                'body'    => wp_json_encode(
                    [
                        'intent'         => 'CAPTURE',
                        'purchase_units' => [
                            [
                                'description' => 'Bookster Booking: ' . $service_name,
                                'custom_id'   => 'booksterTransactionSecretId_' . $transaction_secret_id,
                                'amount'      => [
                                    'currency_code' => PaypalUtils::get_currency(),
                                    'value'         => PaypalUtils::convert_amount_bookster_to_paypal( $amount ),
                                ],
                            ],
                        ],
                        'payment_source' => [
                            'paypal' => [
                                'experience_context' => [
                                    'shipping_preference' => 'NO_SHIPPING',
                                    'payment_method_preference' => 'IMMEDIATE_PAYMENT_REQUIRED',
                                    'user_action'         => 'PAY_NOW',
                                ],
                            ],
                        ],
                    ]
                ),
            ]
        );
        if ( is_wp_error( $response ) ) {
            throw new \Exception( esc_html( $response->get_error_message() ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( 200 !== $response['response']['code'] ) {
            throw new \Exception( esc_html( $data['message'] ) );
        }

        return $data;
    }

    /**
     * Update order amount
     *
     * @param string  $order_id
     * @param string  $service_name
     * @param Decimal $amount
     * @return bool
     */
    public function update_order_amount( $order_id, $service_name, $amount ) {
        $response = wp_remote_request(
            $this->paypal_settings_service->get_api_url() . '/v2/checkout/orders/' . $order_id,
            [
                'method'  => 'PATCH',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->paypal_settings_service->get_access_token(),
                ],
                'body'    => wp_json_encode(
                    [
                        [
                            'op'    => 'replace',
                            'path'  => '/purchase_units/@reference_id==\'default\'/description',
                            'value' => 'Bookster Booking: ' . $service_name,
                        ],
                        [
                            'op'    => 'replace',
                            'path'  => '/purchase_units/@reference_id==\'default\'/amount',
                            'value' => [
                                'currency_code' => PaypalUtils::get_currency(),
                                'value'         => PaypalUtils::convert_amount_bookster_to_paypal( $amount ),
                            ],
                        ],
                    ]
                ),
            ]
        );
        if ( is_wp_error( $response ) ) {
            throw new \Exception( esc_html( $response->get_error_message() ) );
        }

        if ( 204 !== $response['response']['code'] ) {
            $body = wp_remote_retrieve_body( $response );
            $data = json_decode( $body, true );
            throw new \Exception( esc_html( $data['message'] ) );
        }

        return true;
    }

    /**
     * Capture order
     *
     * @param string $order_id
     * @return array
     */
    public function capture_order( $order_id ) {
        $response = wp_remote_request(
            $this->paypal_settings_service->get_api_url() . '/v2/checkout/orders/' . $order_id . '/capture',
            [
                'method'  => 'POST',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->paypal_settings_service->get_access_token(),
                ],
            ]
        );
        if ( is_wp_error( $response ) ) {
            throw new \Exception( esc_html( $response->get_error_message() ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( 201 !== $response['response']['code'] ) {
            throw new \Exception( esc_html( $data['message'] ) );
        }

        return $data;
    }

    /**
     * Get order
     *
     * @param string $order_id
     * @return array
     */
    public function get_order( $order_id ) {
        $response = wp_remote_request(
            $this->paypal_settings_service->get_api_url() . '/v2/checkout/orders/' . $order_id,
            [
                'method'  => 'GET',
                'headers' => [
                    'Content-Type'  => 'application/json',
                    'Authorization' => 'Bearer ' . $this->paypal_settings_service->get_access_token(),
                ],
            ]
        );
        if ( is_wp_error( $response ) ) {
            throw new \Exception( esc_html( $response->get_error_message() ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        if ( 200 !== $response['response']['code'] ) {
            throw new \Exception( esc_html( $data['message'] ) );
        }

        return $data;
    }

    /**
     * @param string  $transaction_secret_id
     * @param array   $order
     * @param Decimal $order_amount
     *
     * @return \Bookster\Models\TransactionModel
     */
    public function save_as_transaction( $transaction_secret_id, $order, $order_amount ) {
        $amount           = $order_amount->to_string();
        $transaction_link = $this->paypal_settings_service->get_paypal_dashboard_url();

        $transaction_attr = [
            'transaction_secret_id' => $transaction_secret_id,
            'payment_gateway'       => PaymentGatewayEnum::PAYPAL,
            'amount'                => $amount,
            'transaction_status'    => TransactionStatusEnum::CREATED,
            'transaction_message'   => 'Paid via Paypal',
            'transaction_link'      => $transaction_link . '/unifiedtransactions',
            'transaction_meta'      => [
                'order_id' => $order['id'],
            ],
        ];

        return $this->transactions_service->insert( $transaction_attr );
    }

    /**
     * @param array $order
     */
    public function validate_can_update( $order ) {
        $forbid_update_statuses = [
            'APPROVED',
            'COMPLETED',
            'VOIDED',
        ];
        // not yet APPROVED then allow update
        if ( in_array( $order['status'], $forbid_update_statuses, true ) ) {
            throw new \Exception( 'Invalid Order Status!' );
        }
    }

    /**
     * @param array $order
     */
    public function validate_can_capture( $order ) {
        // after APPROVED then allow capture
        if ( 'APPROVED' !== $order['status'] ) {
            throw new \Exception( 'Your Order is not yet Approved!' );
        }
    }

    /**
     * @param array $order
     */
    public function validate_can_save_appointment( $order ) {
        // after COMPLETED then allow save appointment
        if ( 'COMPLETED' !== $order['status'] ) {
            throw new \Exception( 'Your Order is not yet Completed!' );
        }
    }

    /**
     * Make Sure the Paypal Order is valid and can only Book One Appointment.
     * Verify Order is created with the same secret id
     *
     * @param TransactionModel $transaction
     * @param array            $order
     * @throws \Exception Invalid Paypal Order.
     */
    public function validate_secret_id( $transaction, $order ) {
        if ( ! isset( $order['purchase_units'][0]['custom_id'] ) || ( 'booksterTransactionSecretId_' . $transaction->transaction_secret_id ) !== $order['purchase_units'][0]['custom_id'] ) {
            throw new \Exception( 'Invalid Transaction Secret ID!' );
        }
        if ( ! empty( $transaction->appointment_id ) ) {
            throw new \Exception( 'Appointment already Booked!' );
        }
    }
}
