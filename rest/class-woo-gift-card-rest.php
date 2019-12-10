<?php
/**
 * The rest api functionality of the plugin.
 *
 * @link       tyganeutronics.com
 * @since      1.0.0
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/rest
 */

/**
 * The rest api functionality of the plugin.
 *
 * Defines the plugin name, version
 *
 * @package    Woo_gift_card
 * @subpackage Woo_gift_card/rest
 * @author     Richard Muvirimi <tygalive@gmail.com>
 */
class Woo_gift_card_Rest {

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * The request parameters
     *
     * @since    1.0.0
     * @access   private
     * @var array $params The parameters of the request
     */
    private $params;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version) {

	$this->plugin_name = $plugin_name;
	$this->version = $version;
    }

    /**
     * On initialize the rest api
     */
    public function register_routes() {
	register_rest_route('woo-gift-card/v1', '/template', array(
	    'methods' => WP_REST_Server::CREATABLE,
	    'callback' => array($this, 'process_product_preview'),
	    'args' => array(
		'required' => true,
		'wgc-product' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param && is_numeric($param)) {
			    $product = wc_get_product($param);
			    return !is_null($product) && $product->is_type('woo-gift-card');
			}
			return false;
		    }
		),
		'wgc-receiver-template' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {
			    //if set validate
			    return $param && is_numeric($param) && !is_null(get_post($param));
			} else {
			    return true;
			}
		    }
		),
		'wgc-receiver-price' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_numeric($param);
		    }
		),
		'wgc-receiver-name' => array(
		    'validate_callback' => function($param, $request, $key) {
			return true;
		    }
		),
		'wgc-receiver-email' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_email($param);
		    },
		),
		'wgc-receiver-message' => array(
		    'validate_callback' => function($param, $request, $key) {
			return true;
		    }
		),
		'wgc-receiver-image' => array(
		    'validate_callback' => function($param, $request, $key) {
			return true;
		    }
		),
		'wgc-event' => array(
		    'validate_callback' => function($param, $request, $key) {
			return true;
		    }
		)
	    ),
	    'permission_callback' => function () {
		return true;
	    }
	));
    }

    public function process_product_preview(WP_REST_Request $request) {

	$this->params = $request->get_params();
	$template = get_post($request->get_param('wgc-receiver-template'));

	ob_start();
	?>
	<!DOCTYPE html>;
	<html class="no-js" <?php language_attributes(); ?>>
	    <head>

		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<style><?php esc_textarea(get_post_meta($template->ID, 'wgc-template-css', true)); ?></style>

	    </head>
	    <body>
		<?php esc_html_e(do_shortcode($template->post_content)); ?>
	    </body>
	</html>

	<?php
	return array("template" => ob_get_clean());
	//amount
	//to name
	//to email
	//message
	//template
	//image
    }

    /**
     * Handle woo gift card template short codes
     *
     * @return void
     */
    public function wgc_shortcode($atts) {
	$template = get_post($this->params['wgc-receiver-template']);
	$product = wc_get_product($this->params['wgc-product']);

	$shortcode = '';

	$attributes = shortcode_atts(array("attr" => "code"), $atts, 'woogiftcard');
	switch ($attributes['attr']) {
	    case "amount":
		switch ($product->get_meta("wgc-pricing")) {
		    case "range":
		    case 'user':
		    case "selected":
			$shortcode = $this->params['wgc-receiver-price'];
			break;
		    case 'fixed':
		    default:
			$shortcode = $product->get_price();
		}
		break;
	    case "disclaimer":
		$shortcode = esc_html__(get_option('wgc-message-disclaimer', ''));
		break;
	    case "event":
		$shortcode = $this->params['wgc-event'] ?: $template->post_title;
		break;
	    case "expiry-date":
		$shortcode = 'todo caluculate date';
		break;
	    case "featured-image":

		break;
	    case "from":
		$shortcode = get_user_option("display_name");
		break;
	    case "logo":
		break;
	    case "message":
		$shortcode = $this->params['wgc-receiver-message'];
		break;
	    case "order-id":
		break;
	    case "product-name":
		$shortcode = $product->get_name();
		break;
	    case "to":
		$shortcode = $this->params['wgc-receiver-email'];
		break;
	    case "code":
		break;
	}

	if (empty($shortcode)) {
	    $shortcode = '[' . strtoupper($attributes['attr']) . ']';
	}

	return $shortcode;
    }

}
