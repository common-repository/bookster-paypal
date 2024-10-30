<?php
namespace Bookster_Paypal\Engine;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster_Paypal\Services\PaypalSettingsService;
use Bookster\Features\Enums\PaymentGatewayEnum;
use Bookster\Engine\BEPages\ManagerPage;
use Bookster\Features\Auth\Caps;

/**
 * Handle Settings hooks for Paypal Gateway
 */
class PaypalSettings {
    use SingletonTrait;

    /** @var PaypalSettingsService */
    private $paypal_settings_service;

    protected function __construct() {
        $this->paypal_settings_service = PaypalSettingsService::get_instance();

        add_filter( 'bookster_public_data', [ $this, 'add_paypal_public' ], 10, 1 );
        add_filter( 'bookster_public_data', [ $this, 'add_payment_gateway' ], 30, 1 );

        add_filter( 'bookster_manager_data', [ $this, 'add_paypal_settings' ], 10, 1 );
        add_action( 'bookster_update_payment_settings', [ $this, 'update_paypal_settings' ], 10, 1 );

        if ( current_user_can( Caps::MANAGE_SHOP_SETTINGS_CAP ) ) {
            add_filter( 'plugin_action_links_' . plugin_basename( BOOKSTER_PAYPAL_PLUGIN_FILE ), [ $this, 'add_action_links' ] );
        }
    }

    public function add_paypal_public( $public_data ) {
        $public_data['addonPaymentPaypal'] = $this->paypal_settings_service->get_public_settings();
        return $public_data;
    }

    public function add_paypal_settings( $manager_data ) {
        $manager_data['addonPaymentPaypal'] = $this->paypal_settings_service->get_settings();
        return $manager_data;
    }

    public function update_paypal_settings( $json_params ) {
        if ( isset( $json_params['addonPaymentPaypal'] ) ) {
            $this->paypal_settings_service->patch_settings( $json_params['addonPaymentPaypal'] );
        }
    }

    public function add_payment_gateway( $public_data ) {
        if ( $this->paypal_settings_service->is_enabled() ) {
            $public_data['paymentGateway']['now'] = PaymentGatewayEnum::PAYPAL;
        }
        return $public_data;
    }

    public function add_action_links( array $links ) {
        return array_merge(
            [
                'manage' => '<a href="' . admin_url( 'admin.php?page=' . ManagerPage::MENU_SLUG . '#/settings/payment' ) . '">' . __( 'Settings', 'bookster-paypal' ) . '</a>',
            ],
            $links
        );
    }
}
