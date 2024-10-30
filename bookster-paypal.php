<?php
/**
 * Bookster Paypal Payment Gateway
 *
 * @package             Bookster_Paypal
 * @author              WPBookster
 * @copyright           Copyright 2023-2024, Bookster
 * @license             http://www.gnu.org/licenses/gpl-3.0.html GNU General Public License, version 3 or later
 *
 * @wordpress-plugin
 * Plugin Name:         Bookster Paypal
 * Plugin URI:          https://wpbookster.com/
 * Requires Plugins:    bookster
 * Description:         Official Bookster Paypal addon - Intergrate Paypal Payment to your Bookings.
 * Version:             2.0.0
 * Requires at least:   6.2
 * Requires PHP:        7.4
 * Author:              WPBookster
 * Author URI:          https://wpbookster.com/about
 * Text Domain:         bookster-paypal
 * License:             GPL v3 or later
 * License URI:         https://www.gnu.org/licenses/gpl-3.0.html
 */

if ( ! defined( 'ABSPATH' ) ) {
    die( 'We\'re sorry, but you can not directly access this file.' );
}

define( 'BOOKSTER_PAYPAL_VERSION', '2.0.0' );

define( 'BOOKSTER_PAYPAL_PLUGIN_FILE', __FILE__ );
define( 'BOOKSTER_PAYPAL_PLUGIN_PATH', plugin_dir_path( BOOKSTER_PAYPAL_PLUGIN_FILE ) );
define( 'BOOKSTER_PAYPAL_PLUGIN_URL', plugin_dir_url( BOOKSTER_PAYPAL_PLUGIN_FILE ) );
define( 'BOOKSTER_PAYPAL_PLUGIN_BASENAME', plugin_basename( BOOKSTER_PAYPAL_PLUGIN_FILE ) );

add_action(
    'init',
    function() {
        load_plugin_textdomain( 'bookster-paypal', false, dirname( BOOKSTER_PAYPAL_PLUGIN_BASENAME ) . '/languages' );
    }
);

function bookster_paypal_activate( bool $network_wide ) {
    if ( class_exists( '\Bookster_Paypal\Engine\ActDeact' ) ) {
        \Bookster_Paypal\Engine\ActDeact::activate( $network_wide );
    }
}
function bookster_paypal_deactivate( bool $network_wide ) {
    if ( class_exists( '\Bookster_Paypal\Engine\ActDeact' ) ) {
        \Bookster_Paypal\Engine\ActDeact::deactivate( $network_wide );
    }
}
function bookster_paypal_uninstall() {
    if ( class_exists( '\Bookster_Paypal\Engine\ActDeact' ) ) {
        \Bookster_Paypal\Engine\ActDeact::uninstall();
    }
}
register_activation_hook( BOOKSTER_PAYPAL_PLUGIN_FILE, 'bookster_paypal_activate' );
register_deactivation_hook( BOOKSTER_PAYPAL_PLUGIN_FILE, 'bookster_paypal_deactivate' );
register_uninstall_hook( BOOKSTER_PAYPAL_PLUGIN_FILE, 'bookster_paypal_uninstall' );

require_once BOOKSTER_PAYPAL_PLUGIN_PATH . 'vendor/autoload.php';
if ( ! wp_installing() ) {
    add_action(
        'plugins_loaded',
        function () {
            /** Require Dependencies: (min.any < ver < max.any) => OK */
            $max_bookster_version = '3.0';
            $min_bookster_version = '2.0';

            if ( ! defined( 'BOOKSTER_VERSION' ) ) {
                add_action(
                    'admin_notices',
                    function() {
                        echo wp_kses_post(
                            sprintf(
                                '<div class="notice notice-error"><p>%s</p></div>',
                                __( '"Bookster - Paypal" requires Bookster plugin installed and activated.', 'bookster-paypal' )
                            )
                        );
                    }
                );

                return;
            }

            if ( ! version_compare( $min_bookster_version . '.any', BOOKSTER_VERSION, '<' ) ) {
                add_action(
                    'admin_notices',
                    function() use ( $min_bookster_version ) {
                        $notice = sprintf(
                            /* translators: %1$s - Bookster Paypal Version. %2$s - Minimum Supporting Bookster Version */
                            __( '"Bookster - Paypal %1$s" requires Bookster version %2$s. Please update Bookster plugin!', 'bookster-paypal' ),
                            BOOKSTER_PAYPAL_VERSION,
                            $min_bookster_version
                        );

                        echo wp_kses_post(
                            sprintf(
                                '<div class="notice notice-error"><p>%s</p></div>',
                                $notice
                            )
                        );
                    }
                );

                return;
            }//end if

            if ( ! version_compare( BOOKSTER_VERSION, $max_bookster_version . '.any', '<' ) ) {
                add_action(
                    'admin_notices',
                    function() {
                        $notice = sprintf(
                            /* translators: %s - Bookster Version */
                            __( '"Bookster %s" requires new addon version. Please update Bookster Paypal!', 'bookster-paypal' ),
                            BOOKSTER_VERSION
                        );

                        echo wp_kses_post(
                            sprintf(
                                '<div class="notice notice-error"><p>%s</p></div>',
                                $notice
                            )
                        );
                    }
                );

                return;
            }//end if

            // Make sure Bookster classes loaded.
            if ( class_exists( '\Bookster\Initialize' ) ) {
                \Bookster_Paypal\Initialize::get_instance();
            }
        }
    );
}//end if
