<?php
namespace Bookster_Paypal\Features\Utils;

use Bookster_Paypal\Features\Constants\CurrenciesData;
use Bookster\Services\PaymentService;
use Bookster\Features\Utils\Decimal;

/** Utils for Paypal */
class PaypalUtils {

    /**
     * Convert amount to Paypal format
     *
     * @param Decimal $amount
     *
     * @return string
     */
    public static function convert_amount_bookster_to_paypal( $amount ) {
        if ( in_array( self::get_currency(), CurrenciesData::ZERO_DECIMAL, true ) ) {
            $int_value = $amount->to_number();
            return Decimal::from_number( round( $int_value ) )->to_string();
        }
        return $amount->to_string();
    }

    /**
     * Get currency as uppercase
     *
     * @return string
     */
    public static function get_currency() {
        $currency = PaymentService::get_instance()->get_currency();
        return strtoupper( $currency );
    }
}
