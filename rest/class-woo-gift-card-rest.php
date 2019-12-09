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
    public function rest_api_init() {
	register_rest_route('woo-gift-card/v1', '/template', array(
	    'methods' => WP_REST_Server::CREATABLE,
	    'callback' => array($this, 'process_product_preview'),
	    'args' => array(
		'required' => true,
		'product' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_numeric($param) && !is_null(get_post($param));
		    }
		),
		'template' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_numeric($param) && !is_null(get_post($param));
		    }
		),
		'amount' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_numeric($param) && !is_null(get_post($param));
		    }
		),
		'name' => array(
		    'validate_callback' => function($param, $request, $key) {
			return true;
		    }
		),
		'email' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param && is_email($param);
		    },
		// 'default' => current_,
		),
		'message' => array(
		    'validate_callback' => function($param, $request, $key) {
			return $param;
		    }
		),
		'image' => array(
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

	$template_id = $request->get_param('template');
	$product_id = $request->get_param('product');

	$template = get_post($template_id);
	$product = wc_get_product($product_id);

	ob_start();
	?>
	<!DOCTYPE html>;
	<html class="no-js" <?php language_attributes(); ?>>
	    <head>

		<meta charset="<?php bloginfo('charset'); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0" >
		<style><?php echo get_post_meta($template->ID, 'wgc-template-css', true); ?></style>

	    </head>
	    <body>
		<?php echo do_shortcode($template->post_content); ?>
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
//todo short codes
	$attributes = shortcode_atts(array("attr" => "code"), $atts, 'woogiftcard');

	switch ($attributes['attr']) {
	    case "amount":

		break;
	    case "disclaimer":

		break;
	    case "event":

		break;
	    case "expiry-date":

		break;
	    case "featured-image":

		break;
	    case "from":

		break;
	    case "logo":

		break;
	    case "message":

		break;
	    case "order-id":

		break;
	    case "product-name":

		break;
	    case "to":

		break;
	    case "code":
	    default:
	}
    }

}
