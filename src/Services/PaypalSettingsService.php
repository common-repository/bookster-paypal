<?php
namespace Bookster_Paypal\Services;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster\Services\BaseService;

/**
 * Paypal Settings Service
 *
 * @method static PaypalSettingsService get_instance()
 */
class PaypalSettingsService extends BaseService {
    use SingletonTrait;

    public const SETTINGS_OPTION = 'bookster_paypal_settings';
    public const TOKEN_OPTION    = 'bookster_paypal_access_token';

    private $paypal_settings;

    public static function get_default_settings() {
        return [
            'enabled'               => false,
            'is_live_mode'          => false,
            'sandbox_client_id'     => '',
            'sandbox_client_secret' => '',
            'live_client_id'        => '',
            'live_client_secret'    => '',
        ];
    }

    public function get_settings() {
        if ( null !== $this->paypal_settings ) {
            return $this->paypal_settings;
        }

        $default_settings      = self::get_default_settings();
        $this->paypal_settings = wp_parse_args( get_option( self::SETTINGS_OPTION, $default_settings ), $default_settings );
        return $this->paypal_settings;
    }

    public function patch_settings( $new_settings ) {
        update_option( self::SETTINGS_OPTION, $new_settings );
        delete_option( self::TOKEN_OPTION );
    }

    public function get_public_settings() {
        return [
            'client_id' => $this->get_client_id(),
        ];
    }

    /** @return bool */
    public function is_enabled() {
        $settings = $this->get_settings();
        return $settings['enabled'];
    }

    /** @return bool */
    public function is_live_mode() {
        $settings = $this->get_settings();
        return $settings['is_live_mode'];
    }

    public function get_client_id() {
        $settings = $this->get_settings();
        return $this->is_live_mode() ? $settings['live_client_id'] : $settings['sandbox_client_id'];
    }

    public function get_client_secret() {
        $settings = $this->get_settings();
        return $this->is_live_mode() ? $settings['live_client_secret'] : $settings['sandbox_client_secret'];
    }

    public function get_api_url() {
        return $this->is_live_mode() ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
    }

    public function get_paypal_dashboard_url() {
        return $this->is_live_mode() ? 'https://www.paypal.com' : 'https://www.sandbox.paypal.com';
    }

    /**
     * Get access token
     *
     * @return string
     */
    public function get_access_token() {
        $token_option = get_option( self::TOKEN_OPTION );

        if ( false !== $token_option && $token_option['client_id'] === $this->get_client_id() && $token_option['expires'] > time() ) {
            return $token_option['access_token'];
        }

        $oauth_data   = $this->request_oauth_token( $this->get_api_url(), $this->get_client_id(), $this->get_client_secret() );
        $token_option = [
            'client_id'    => $this->get_client_id(),
            'access_token' => $oauth_data['access_token'],
            'expires'      => time() + $oauth_data['expires_in'] - 10,
        ];
        update_option( self::TOKEN_OPTION, $token_option );
        return $token_option['access_token'];
    }

    public function validate_settings( $is_live_mode, $client_id, $client_secret ) {
        $api_url = $is_live_mode ? 'https://api-m.paypal.com' : 'https://api-m.sandbox.paypal.com';
        $this->request_oauth_token( $api_url, $client_id, $client_secret );
    }

    private function request_oauth_token( $api_url, $client_id, $client_secret ) {
        $response = wp_remote_request(
            $api_url . '/v1/oauth2/token',
            [
                'method'  => 'POST',
                'headers' => [
                    'Content-Type'  => 'application/x-www-form-urlencoded',
                    'Authorization' => 'Basic ' . base64_encode( $client_id . ':' . $client_secret ), // phpcs:ignore WordPress.PHP.DiscouragedPHPFunctions.obfuscation_base64_encode
                ],
                'body'    => 'grant_type=client_credentials',
            ]
        );

        if ( is_wp_error( $response ) ) {
            throw new \Exception( esc_html( $response->get_error_message() ) );
        }

        $body = wp_remote_retrieve_body( $response );
        $data = json_decode( $body, true );

        return $data;
    }
}
