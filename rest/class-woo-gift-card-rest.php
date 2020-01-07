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
use Dompdf\Dompdf;

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
	    'callback' => array($this, 'ajax_template_iframe'),
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
			    return $param && is_numeric($param) && is_object(get_post($param));
			}
			return false;
		    }
		),
		'wgc-receiver-price' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {
			    return is_numeric($param);
			}
			return true;
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
		return true; // wp_verify_nonce($_POST['wgc-preview-nonce'], 'wgc-preview');
	    }
	));

	/**
	 * Below routes hide the actual file paths. will return the content instead
	 */
	$regex = "(?P<file>(\\\\?([^\\/]*[\\/])*)([^\\/]+)$)";

	register_rest_route('woo-gift-card/v1', '/template/' . $regex, array(
	    'methods' => WP_REST_Server::READABLE,
	    'callback' => array($this, 'ajax_get_file'),
	    'args' => array(
		'required' => true,
	    ),
	));

	register_rest_route('woo-gift-card/v1', '/' . $regex, array(
	    'methods' => WP_REST_Server::READABLE,
	    'callback' => array($this, 'ajax_get_file'),
	    'args' => array(
		'required' => true,
		'file' => array(
		    'sanitize_callback' => function($param, $request, $key) {
			return "../" . $param;
		    }
		)
	    ),
	));
    }

    public function ajax_get_file(WP_REST_Request $request) {

	$path = $request->get_param('file');

	//search for correct content type mime
	$this->ext = pathinfo(basename($path), PATHINFO_EXTENSION);
	$mimes = array_filter(wp_get_mime_types(), function($mime) {
	    return in_array($this->ext, explode("|", $mime));
	}, ARRAY_FILTER_USE_KEY);

	ob_start();
	//set relevant content type for document
	if (empty($mimes)) {
	    header('Content-Type: text/plain');
	} else {
	    foreach ($mimes as $mime) {
		header('Content-Type: ' . $mime);
	    }
	}

	include_once plugin_dir_path(__DIR__) . 'includes/libs/pdfjs-2.2/web/' . $path;

	/**
	 * Before outputting the requested file
	 */
	echo apply_filters("wgc_ajax_template_file", ob_get_clean(), basename($path), $mimes);

	//wp set content type to json so, we have to exit here to prevent that
	exit();
    }

    /**
     * Filter the content of a file before it's output
     *
     * @param string $content
     * @param string $path
     * @param array $mimes
     */
    public function wgc_ajax_template_file($content, $path, $mimes) {
	if (basename($path) == "viewer.html") {

	    $template = get_post($this->params['wgc-receiver-template']);

	    $pdf = $this->get_pdf($template);
	    //Get an instance of WP_Scripts or create new;
	    $wp_scripts = wp_scripts();

	    //Get the script by registered handler name
	    $jquery = $wp_scripts->registered["jquery-core"];

	    $script = "";
	    $script .= '<script> window.wgc_pdf_base64 = "' . esc_js(base64_encode($pdf->output())) . '";</script>';
	    $script .= '<script src="' . get_site_url(null, $jquery->src) . '"></script>';
	    $script .= '<script src="' . plugin_dir_url(__DIR__) . 'public/js/woo-gift-card-template.js' . '"></script></head>';

	    $content = str_replace("</head>", $script, $content);
	}
	return $content;
    }

    public function ajax_template_iframe(WP_REST_Request $request) {

	$request->set_param("file", "viewer.html");
	$this->params = $request->get_params();

	$this->ajax_get_file($request);
    }

    private function get_pdf($template) {
	//generate a temporary pdf
	$options = array(
	    'isHtml5ParserEnabled' => true,
	    "tempDir" => get_temp_dir()
	);

	$dompdf = new Dompdf($options);

	$dompdf->loadHtml($this->get_html($template));
	//$dompdf->loadHtml("test"); //$this->get_html($template));
	//paper size and orientation
	$dimension = WooGiftCardsUtils::getTemplateDimension(get_post_meta($template->ID, "wgc-template-dimension", true));

	$dompdf->setPaper($dimension->getSizeInPoints(), get_post_meta($template->ID, "wgc-template-orientation", true));

	$dompdf->render();

	// return the generated PDF
	return $dompdf;
    }

    private function get_html($template) {

	$html = '<!DOCTYPE html>';
	$html .= '<html class = "no-js" ' . get_language_attributes() . '>';
	$html .= '<head>';
	$html .= '<meta charset = "' . get_bloginfo('charset') . '">';
	$html .= '<meta name = "viewport" content = "width=device-width, initial-scale=1.0" >';
	$html .= '<style>' . get_post_meta($template->ID, 'wgc-template-css', true) . '</style>';
	$html .= '</head>';
	$html .= '<body class = "wgc-preview-body" style = "' . $this->getBackGroundImageStyle() . '">';
	$html .= apply_filters('the_content', do_shortcode($template->post_content));
	$html .= '</body></html>';

	return $html;
    }

    private function getBackGroundImageStyle() {
	$template = get_post($this->params['wgc-receiver-template']);
	$style = "";

	//if we have a background image we want to show it
	if (has_post_thumbnail($template)) {
	    $thumb_id = get_post_thumbnail_id($template);

	    //$style .= "background-repeat: no-repeat;";
	    //$style .= "background-attachment: local;";
	    // $style .= "background-size: 100% 100%;";
	    // $style .= "width: 100%;";
	    //$style .= "height: 100%;";
	    $style .= "background-image: url('" . esc_attr(wp_get_attachment_image_url($thumb_id, "full")) . "');";
	}
	return $style;
    }

    /**
     * Handle woo gift card template short codes
     *
     * @return void
     */
    public function template_shortcode($atts, $content = "", $sCode) {
	$shortcode = ltrim($sCode, WooGiftCardsUtils::getShortCodePrefix());
	$html = "";

	$template = get_post($this->params['wgc-receiver-template']);
	$product = isset($this->params['wgc-product']) ? wc_get_product($this->params['wgc-product']) : null;

	switch ($shortcode) {
	    case "amount":
		if ($product && is_object($product)) {
		    $price = '';
		    switch ($product->get_meta("wgc-pricing")) {
			case "range":
			case 'user':
			case "selected":
			    $price = $this->params['wgc-receiver-price'];
			    break;
			case 'fixed':
			default:
			    $price = $product->get_price();
		    }

		    $html = wc_price(wc_get_price_to_display($product, array('price' => $price))) . $product->get_price_suffix($price);
		}
		break;
	    case "disclaimer":
		$html = esc_html__(get_option('wgc-message-disclaimer', ''));
		break;
	    case "event":
		$html = isset($this->params['wgc-event']) ? $this->params['wgc-event'] : apply_filters('the_title', $template->post_title);
		break;
	    case "expiry-date":
		$html = 'todo calculate date';
		break;
	    case "from":
		$html = get_user_option("display_name");
		break;
	    case "logo":
		break;
	    case "message":
		$html = $this->params['wgc-receiver-message'];
		break;
	    case "order-id":
		break;
	    case "product-name":
		if ($product && is_object($product)) {
		    $html = $product->get_name();
		}
		break;
	    case "to":
		$html = $this->params['wgc-receiver-email'];
		break;
	    case "code":
		break;
	}

	if (empty($html)) {
	    $html = '[' . strtoupper($shortcode) . ']';
	}

	return $html;
    }

    public function uniqueFileName($dir, $name, $ext) {
	return wp_generate_password(12, false) . $ext;
    }

    private function implodeAttr($attribute, $value) {
	return $attribute . ' = "' . $value . '"';
    }

    private function implodeStyle($attribute, $value) {
	return $attribute . ':' . $value . ';
	';
    }

}
