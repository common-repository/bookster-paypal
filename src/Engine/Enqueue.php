<?php
namespace Bookster_Paypal\Engine;

use Bookster_Paypal\Features\Utils\SingletonTrait;
use Bookster\Features\Scripts\ScriptName;
use Bookster\Features\Scripts\EnqueueLogic;

/**
 * Enqueue
 *
 * @method static Enqueue get_instance()
 */
class Enqueue {
    use SingletonTrait;

    public const STYLE_PAYPAL    = 'bookster/style/paypal';
    public const FRONTEND_SCRIPT = 'bookster/module/bookster-paypal/frontend';
    public const ADMIN_SCRIPT    = 'bookster/module/bookster-paypal/admin';

    /** @var EnqueueLogic */
    private $enqueue_logic;

    protected function __construct() {
        $this->enqueue_logic = EnqueueLogic::get_instance();

        if ( ! $this->enqueue_logic->is_prod() ) {
            return;
        }

        add_action( 'init', [ $this, 'register_all_scripts' ] );

        add_filter( 'bookster_scripts_dependencies', [ $this, 'add_paypal_script' ], 10, 1 );
        add_action( 'bookster_after_enqueue_script', [ $this, 'add_paypal_style' ], 10, 0 );
    }

    public function register_all_scripts() {
        wp_register_style( self::STYLE_PAYPAL, BOOKSTER_PAYPAL_PLUGIN_URL . 'assets/dist/paypal/style.css', [], BOOKSTER_PAYPAL_VERSION );

        $deps = [ ScriptName::LIB_CORE, ScriptName::LIB_ICONS, ScriptName::LIB_COMPONENTS, ScriptName::LIB_BOOKING, 'react', 'react-dom', 'wp-hooks', 'wp-i18n' ];

        wp_register_script( self::FRONTEND_SCRIPT, BOOKSTER_PAYPAL_PLUGIN_URL . 'assets/dist/paypal/frontend.js', $deps, BOOKSTER_PAYPAL_VERSION, false );
        wp_set_script_translations( self::FRONTEND_SCRIPT, 'bookster-paypal', BOOKSTER_PAYPAL_PLUGIN_PATH . 'languages' );

        wp_register_script( self::ADMIN_SCRIPT, BOOKSTER_PAYPAL_PLUGIN_URL . 'assets/dist/paypal/admin.js', $deps, BOOKSTER_PAYPAL_VERSION, false );
        wp_set_script_translations( self::ADMIN_SCRIPT, 'bookster-paypal', BOOKSTER_PAYPAL_PLUGIN_PATH . 'languages' );
    }

    public function add_paypal_script( $deps ) {
        if ( is_admin() ) {
            $deps[] = self::ADMIN_SCRIPT;
        } else {
            $deps[] = self::FRONTEND_SCRIPT;
        }

        return $deps;
    }

    public function add_paypal_style() {
        wp_enqueue_style( self::STYLE_PAYPAL );
    }
}
