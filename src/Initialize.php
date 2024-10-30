<?php
namespace Bookster_Paypal;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster\Features\Enums\AddonStatusEnum;

/** Bookster Paypal Gateway Initializer */
class Initialize {
    use SingletonTrait;

    /** The Constructor that load the engine classes */
    protected function __construct() {
        \Bookster_Paypal\Engine\ActDeact::get_instance();
        \Bookster_Paypal\Engine\Enqueue::get_instance();
        \Bookster_Paypal\Engine\RestAPI::get_instance();

        \Bookster_Paypal\Engine\PaypalSettings::get_instance();
        \Bookster_Paypal\Engine\PaypalPayment::get_instance();

        add_filter( 'bookster_addon_infos', [ $this, 'add_activated_addons' ] );
    }

    public function add_activated_addons( $addon_infos ) {
        $addon_infos = array_map(
            function( $addon_info ) {
                if ( 'bookster-paypal' === $addon_info['slug'] ) {
                    $addon_info['installStatus']  = AddonStatusEnum::ACTIVATED;
                    $addon_info['currentVersion'] = BOOKSTER_PAYPAL_VERSION;
                }
                return $addon_info;
            },
            $addon_infos
        );

        return $addon_infos;
    }
}
