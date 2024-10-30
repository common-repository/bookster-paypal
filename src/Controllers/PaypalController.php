<?php
namespace Bookster_Paypal\Controllers;

use Bookster\Controllers\BaseRestController;
use Bookster\Services\BookingRequestService;
use Bookster\Services\ServicesService;
use Bookster\Services\TransactionsService;
use Bookster\Features\Utils\RandomUtils;
use Bookster\Features\Utils\Decimal;
use Bookster_Paypal\Services\OrderService;
use Bookster_Paypal\Features\Utils\SingletonTrait;

/**
 * Paypal Controller
 *
 * @method static PaypalController get_instance()
 */
class PaypalController extends BaseRestController {
    use SingletonTrait;

    /** @var OrderService */
    private $order_service;
    /** @var BookingRequestService */
    private $booking_request_service;
    /** @var ServicesService */
    private $services_service;
    /** @var TransactionsService */
    private $transactions_service;

    protected function __construct() {
        $this->order_service           = OrderService::get_instance();
        $this->booking_request_service = BookingRequestService::get_instance();
        $this->services_service        = ServicesService::get_instance();
        $this->transactions_service    = TransactionsService::get_instance();
        $this->init_hooks();
    }

    protected function init_hooks() {
        register_rest_route(
            self::REST_NAMESPACE,
            '/paypal/orders',
            [
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'exec_create_order' ],
                    'permission_callback' => '__return_true',
                ],
            ]
        );

        register_rest_route(
            self::REST_NAMESPACE,
            '/paypal/orders/amount',
            [
                [
                    'methods'             => 'PATCH',
                    'callback'            => [ $this, 'exec_update_order_amount' ],
                    'permission_callback' => '__return_true',
                ],
            ]
        );
        register_rest_route(
            self::REST_NAMESPACE,
            '/paypal/orders/capture',
            [
                [
                    'methods'             => 'PATCH',
                    'callback'            => [ $this, 'exec_capture_order' ],
                    'permission_callback' => '__return_true',
                ],
            ]
        );
    }

    public function create_order( \WP_REST_Request $request ) {
        $payload               = $request->get_json_params();
        $booking_request_input = $payload['bookingRequestInput'];
        $transaction_secret_id = RandomUtils::gen_unique_id();

        $details      = $this->booking_request_service->make_booking_details( $booking_request_input );
        $order_amount = $details->tax->total;
        $service      = $this->services_service->find_by_id( $booking_request_input['apptInput']['service_id'] );
        $order        = $this->order_service->create_order( $transaction_secret_id, $order_amount, $service->name );
        $transaction  = $this->order_service->save_as_transaction( $transaction_secret_id, $order, $order_amount );

        return [
            'transactionId' => $transaction->transaction_id,
            'orderAmount'   => $order_amount->to_string(),
            'orderId'       => $order['id'],
            'orderStatus'   => $order['status'],
        ];
    }

    public function update_order_amount( \WP_REST_Request $request ) {
        $payload               = $request->get_json_params();
        $booking_request_input = $payload['bookingRequestInput'];
        $transaction_id        = $payload['transactionId'];

        $transaction = $this->transactions_service->get_by_id( $transaction_id );
        $order_id    = $transaction->transaction_meta->order_id;
        $order       = $this->order_service->get_order( $order_id );

        $this->order_service->validate_secret_id( $transaction, $order );
        $this->order_service->validate_can_update( $order );

        $service      = $this->services_service->find_by_id( $booking_request_input['apptInput']['service_id'] );
        $details      = $this->booking_request_service->make_booking_details( $booking_request_input );
        $order_amount = $details->tax->total;
        $this->order_service->update_order_amount( $order_id, $service->name, $order_amount );
        $transaction = $this->transactions_service->update( $transaction_id, [ 'amount' => $order_amount->to_string() ] );

        return [
            'transactionId' => $transaction_id,
            'orderAmount'   => $order_amount->to_string(),
            'orderId'       => $order['id'],
            'orderStatus'   => $order['status'],
        ];
    }

    public function capture_order( \WP_REST_Request $request ) {
        $payload        = $request->get_json_params();
        $transaction_id = $payload['transactionId'];

        $transaction = $this->transactions_service->get_by_id( $transaction_id );
        $order_id    = $transaction->transaction_meta->order_id;
        $order       = $this->order_service->get_order( $order_id );

        $this->order_service->validate_secret_id( $transaction, $order );
        $this->order_service->validate_can_capture( $order );

        $order_amount = $order['purchase_units'][0]['amount']['value'];
        $order_amount = Decimal::from_string( $order_amount );
        $order        = $this->order_service->capture_order( $order_id );

        return [
            'transactionId' => $transaction_id,
            'orderAmount'   => $order_amount->to_string(),
            'orderId'       => $order['id'],
            'orderStatus'   => $order['status'],
        ];
    }

    public function exec_create_order( $request ) {
        return $this->exec_read( [ $this, 'create_order' ], $request );
    }

    public function exec_update_order_amount( $request ) {
        return $this->exec_read( [ $this, 'update_order_amount' ], $request );
    }

    public function exec_capture_order( $request ) {
        return $this->exec_read( [ $this, 'capture_order' ], $request );
    }
}
