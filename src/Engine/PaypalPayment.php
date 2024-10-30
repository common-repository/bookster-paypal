<?php
namespace Bookster_Paypal\Engine;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster_Paypal\Services\OrderService;
use Bookster_Paypal\Services\PaypalSettingsService;
use Bookster\Services\PaymentService;
use Bookster\Services\TransactionsService;
use Bookster\Features\Enums\PaymentStatusEnum;
use Bookster\Features\Enums\PaymentGatewayEnum;
use Bookster\Features\Enums\TransactionStatusEnum;
use Bookster\Features\Utils\Decimal;
use Bookster\Models\TransactionModel;
use Bookster\Models\AppointmentModel;
use Bookster\Models\CustomerModel;

/**
 * Add Paypal Payment
 */
class PaypalPayment {
    use SingletonTrait;

    /** @var PaymentService */
    private $payment_service;
    /** @var OrderService */
    private $order_service;
    /** @var TransactionsService */
    private $transactions_service;
    /** @var PaypalSettingsService */
    private $paypal_settings_service;

    protected function __construct() {
        $this->payment_service         = PaymentService::get_instance();
        $this->order_service           = OrderService::get_instance();
        $this->transactions_service    = TransactionsService::get_instance();
        $this->paypal_settings_service = PaypalSettingsService::get_instance();

        add_filter( 'bookster_prepare_booking_input', [ $this, 'retrieve_order_from_transaction_input' ], 10, 1 );
        add_filter( 'bookster_validate_booking_input', [ $this, 'validate_order_status_before_booking' ], 10, 1 );

        add_filter( 'bookster_create_booking_transaction', [ $this, 'save_order_status_to_transaction' ], 10, 4 );
    }

    public function retrieve_order_from_transaction_input( $booking_request_input ) {
        $transaction_input = $booking_request_input['transactionInput'];
        if ( PaymentGatewayEnum::PAYPAL === $transaction_input['payment_gateway'] ) {
            $transaction_id = $transaction_input['transactionId'];
            $transaction    = $this->transactions_service->get_by_id( $transaction_id );
            $order_id       = $transaction->transaction_meta->order_id;
            $order          = $this->order_service->get_order( $order_id );

            $booking_request_input['transactionInput']['transaction'] = $transaction;
            $booking_request_input['transactionInput']['order']       = $order;
        }
        return $booking_request_input;
    }

    public function validate_order_status_before_booking( $booking_request_input ) {
        $transaction_input = $booking_request_input['transactionInput'];
        if ( PaymentGatewayEnum::PAYPAL !== $transaction_input['payment_gateway'] ) {
            return $booking_request_input;
        }

        if ( ! $this->payment_service->is_payment_gateway_enabled( PaymentGatewayEnum::PAYPAL ) ) {
            throw new \Exception( 'Paypal Gateway is Not Enabled' );
        }

        $order       = $transaction_input['order'];
        $transaction = $transaction_input['transaction'];
        $this->order_service->validate_secret_id( $transaction, $order );
        $this->order_service->validate_can_save_appointment( $order );

        $order_amount   = $order['purchase_units'][0]['amount']['value'];
        $paid_amount    = Decimal::from_string( $order_amount );
        $total_amount   = Decimal::from_string( $booking_request_input['bookingInput']['total_amount'] );
        $payment_status = $paid_amount->equals( $total_amount ) ? PaymentStatusEnum::COMPLETE : PaymentStatusEnum::INCOMPLETE;

        $booking_request_input['bookingInput']['payment_status'] = $payment_status;
        $booking_request_input['bookingInput']['paid_amount']    = $paid_amount->to_string();
        return $booking_request_input;
    }

    /**
     * @param TransactionModel|null $transaction_model
     * @param array                 $booking_input
     * @param AppointmentModel      $appointment_model
     * @param CustomerModel         $customer_model
     *
     * @return TransactionModel
     */
    public function save_order_status_to_transaction( $transaction_model, $booking_input, $appointment_model, $customer_model ) {
        $transaction_input = $booking_input['transactionInput'];
        if ( PaymentGatewayEnum::PAYPAL !== $transaction_input['payment_gateway'] ) {
            return $transaction_model;
        }

        if ( null !== $transaction_model ) {
            throw new \Exception( 'Paypal Gateway is Not Installed Properly!' );
        }

        $order              = $transaction_input['order'];
        $transaction        = $transaction_input['transaction'];
        $transaction_status = 'COMPLETED' === $order['status'] ? TransactionStatusEnum::SUCCEEDED : TransactionStatusEnum::FAILED;
        $transaction_link   = $this->paypal_settings_service->get_paypal_dashboard_url();
        if ( isset( $order['purchase_units'][0]['payments']['captures'][0]['id'] ) ) {
            $paypal_transaction_id = $order['purchase_units'][0]['payments']['captures'][0]['id'];
            $transaction_link     .= '/unifiedtransactions/details/payment/' . $paypal_transaction_id;
        }

        $transaction_model = $this->transactions_service->update(
            $transaction->transaction_id,
            [
                'appointment_id'     => $appointment_model->appointment_id,
                'customer_id'        => $customer_model->customer_id,
                'transaction_status' => $transaction_status,
                'transaction_link'   => $transaction_link,
            ]
        );
        return $transaction_model;
    }
}
