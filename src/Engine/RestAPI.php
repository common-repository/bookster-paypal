<?php
namespace Bookster_Paypal\Engine;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster_Paypal\Controllers\PaypalController;

/**
 * Paypal Rest API
 */
class RestAPI {
    use SingletonTrait;

    protected function __construct() {
        add_action( 'rest_api_init', [ $this, 'add_paypal_endpoint' ] );
    }

    public function add_paypal_endpoint() {
        PaypalController::get_instance();
    }
}
