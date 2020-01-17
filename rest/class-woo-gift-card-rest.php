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
			return true;
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
			if ($param) {
			    $count = count(get_users(array(
				"search_columns" => array("display_name"),
				"search" => $param
			    )));

			    return $count > 0;
			}
			return true;
		    }
		),
		'wgc-receiver-email' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {
			    return is_email($param);
			}
			return TRUE;
		    },
		),
		'wgc-receiver-message' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {
			    $length = strlen($param);
			    $max = get_option('wgc-message-length');

			    // if length is greater than zero and below max if set
			    return $length > 0 && (is_numeric($max) && $length <= get_option('wgc-message-length') || true);
			}
			return true;
		    }
		),
		'wgc-receiver-image' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {

			    //if image was sent with request
			    return file_is_valid_image($_FILES["wgc-receiver-image"]["tmp_name"]);
			}
			return true;
		    }
		),
		'wgc-event' => array(
		    'validate_callback' => function($param, $request, $key) {
			if ($param) {
			    return strlen($param) > 0;
			}
			return true;
		    }
		)
	    ),
	    'permission_callback' => function () {
		return true || is_user_logged_in();
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
	$mime = wgc_get_mime_type_for_file($path);

	ob_start();
	//set relevant content type for document
	header('Content-Type: ' . $mime);

	include_once plugin_dir_path(__DIR__) . 'includes/libs/pdfjs-2.2/web/' . $path;

	/**
	 * Before outputting the requested file
	 */
	echo apply_filters("wgc_ajax_template_file", ob_get_clean(), basename($path), $mime);

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
	    $script .= '<meta http-equiv="Cache-control" content="public">';
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

	//paper size and orientation
	$orientation = get_post_meta($template, "wgc-template-orientation", true);

	$dompdf->setPaper($this->getTemplateSizeInPoints($template, $orientation), $orientation);

	$dompdf->render();

	// return the generated PDF
	return $dompdf;
    }

    private function getTemplateSizeInPoints($template, $orientation = "landscape") {
	$dimension = wp_get_post_terms($template->ID, "wgc-template-dimension")[0];

	$meta = get_term_meta($dimension->term_id);

	$value1 = $meta["wgc-dimension-value1"][0];
	$value2 = $meta["wgc-dimension-value2"][0];

	if ($value1 && $value2) {

	    //to pt
	    switch ($meta["wgc-dimension-unit"][0]) {
		case "mm":
		    $value1 *= 2.835;
		    $value2 *= 2.835;
		    break;
		case "in":
		    $value1 *= 75;
		    $value2 *= 75;
		    break;
		case "pt":
		default :
	    }

	    if ($orientation === "landscape") {
		$value1 = max(array($value1, $value2));
		$value2 = min(array($value1, $value2));
	    } else {
		$value1 = min(array($value1, $value2));
		$value2 = max(array($value1, $value2));
	    }
	} else {

	    $width = 0;
	    $height = 0;

	    //if we have a background image we want to show it
	    if (isset($_FILES['wgc-receiver-image']) && $_FILES['wgc-receiver-image']['size']) {
		$path = $_FILES['wgc-receiver-image']['tmp_name'];

		require_once ABSPATH . "wp-admin/includes/image.php";

		if (file_is_valid_image($path)) {
		    list($width, $height) = @getimagesize($path);
		}
	    } elseif (has_post_thumbnail($template)) {
		$thumb_id = get_post_thumbnail_id($template);

		list(, $width, $height) = wp_get_attachment_image_src($thumb_id, "full");
	    } else {
		//get most popular term
		$terms = get_terms(array(
		    "taxonomy" => "wgc-template-dimension",
		    "orderby" => "count",
		    "include" => get_term_by("slug", "a4", "wgc-template-dimension")->term_id,
		    "number" => 1,
		    "hide_empty" => false
		));

		$meta = get_term_meta($terms[0]->term_id);

		//convert to pixels
		//1mm = 25.4 px
		//1px = 1/25.4 mm = 25.4 px
		$width = $meta["wgc-dimension-value1"][0] * 96 / 25.4;
		$height = $meta["wgc-dimension-value2"][0] * 96 / 25.4;
	    }

	    /**
	     * 72 pt = 96 px = 1 in
	     */
	    //convert px to pt
	    $value1 = $width * 72 / 96;
	    $value2 = $height * 72 / 96;
	}

	return array(0, 0, $value1, $value2);
    }

    private function get_html($template) {

	$html = '<!DOCTYPE html>';
	$html .= '<html class = "no-js" ' . get_language_attributes() . '>';
	$html .= '<head>';
	$html .= '<meta charset = "' . get_bloginfo('charset') . '">';
	$html .= '<meta name = "viewport" content = "width=device-width, initial-scale=1.0" >';
	$html .= '<style type="text/css">' . get_post_meta($template->ID, 'wgc-template-css', true) . '</style>';
	$html .= '</head>';
	$html .= '<body class = "preview-body" style="' . $this->getBackGroundImageStyle() . '">';
	$html .= apply_filters('the_content', do_shortcode($template->post_content));
	$html .= '</body></html>';

	return $html;
    }

    /**
     * If this request is a get pdf request
     * @return boolean
     */
    private function is_pdf_request() {
	return isset($this->params);
    }

    /**
     * Filter the post content images and set to base 64
     *
     * @param string $content
     * @return string
     */
    public function the_content($content) {
	if ($this->is_pdf_request()) {

	    //match all image paths to replace
	    $paths = preg_match_all('/<img.+src=[\'"]([^\'"]+)[\'"].*>/i', $content, $matches) ? $matches[1] : array();

	    foreach ($paths as $path) {
		$content = str_replace($path, wgc_path_to_base64($path), $content);
	    }
	}

	return $content;
    }

    private function getBackGroundImageStyle() {
	$template = get_post($this->params['wgc-receiver-template']);
	$style = "";
	$path = "";

	//if we have a background image we want to show it
	if (isset($_FILES['wgc-receiver-image']) && $_FILES['wgc-receiver-image']['size']) {
	    $file = $_FILES['wgc-receiver-image'];
	    $path = $file['tmp_name'];
	} elseif (has_post_thumbnail($template)) {
	    $thumb_id = get_post_thumbnail_id($template);

	    $path = wp_get_attachment_image_url($thumb_id, "full");
	} else {
	    return $style;
	}

	$style .= "background-image: url('" . wgc_path_to_base64($path) . "');";

	return $style;
    }

    /**
     * Handle woo gift card template short codes
     *
     * @return void
     */
    public function template_shortcode($atts, $content = "", $sCode) {
	$shortcode = str_replace("wgc-", "", $sCode);
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
		/**
		 * Gift voucher disclaimer text
		 */
		$html = esc_html__(get_option('wgc-message-disclaimer') ?: '');
		break;
	    case "event":
		/**
		 * The title of the gift voucher
		 */
		if (version_compare(phpversion(), "7", ">=")) {
		    $html = $this->params['wgc-event'] ?? apply_filters('the_title', $template->post_title);
		} else {
		    $event = isset($this->params['wgc-event']) ? $this->params['wgc-event'] : "";
		    $html = $event ?: apply_filters('the_title', $template->post_title);
		}
		break;
	    case "expiry-days":
		/**
		 * The number of days gift voucher will expire in
		 */
		if ($product && is_object($product)) {
		    $html = $product->get_meta("wgc-expiry-days") ?: __("Never", "woo-gift-card");
		}
		break;
	    case "from":
		/**
		 * The name of the sender of gift voucher
		 */
		$html = get_user_option("display_name");
		break;
	    case "logo":
		/**
		 * Site logo
		 */
		if (has_custom_logo()) {
		    $html = get_custom_logo();
		} else {
		    $html = get_bloginfo("name");
		}
		break;
	    case "message":
		$html = isset($this->params['wgc-receiver-message']) ? $this->params['wgc-receiver-message'] : "";
		break;
	    case "order-id":
		break;
	    case "product-name":
		if ($product && is_object($product)) {
		    $html = $product->get_name();
		}
		break;
	    case "to-name":
		/**
		 * The recipient name of the gift voucher
		 */
		if (version_compare(phpversion(), "7", ">=")) {
		    $html = $this->params['wgc-receiver-name'] ?? get_user_option("display_name");
		} else {
		    $name = isset($this->params['wgc-receiver-name']) ? $this->params['wgc-receiver-name'] : "";
		    $html = $name ?: get_user_option("display_name");
		}
		break;
	    case "to-email":
		/**
		 * The recipient email of the gift voucher
		 */
		if (version_compare(phpversion(), "7", ">=")) {
		    $html = $this->params['wgc-receiver-email'] ?? get_user_option("email");
		} else {
		    $email = isset($this->params['wgc-receiver-email']) ? $this->params['wgc-receiver-email'] : "";
		    $html = $email ?: get_user_option("email");
		}
		break;
	    case "code":

		$meta = get_post_meta($template->ID);

		$prefix = get_option("wgc-code-prefix");
		$code = str_repeat("X", get_option("wgc-code-length"));
		$suffix = get_option("wgc-code-suffix");

		switch (get_post_meta($template->ID, "wgc-coupon-type", true)) {
		    case 'qrcode':

			$qrcode = $prefix . $code . $suffix;

			$file = get_temp_dir() . $qrcode . ".png";

			QRcode::png($qrcode, $file, $meta["wgc-coupon-qrcode-ecc"][0], $meta["wgc-coupon-qrcode-size"][0], $meta["wgc-coupon-qrcode-margin"][0]);

			$image = file_get_contents($file);

			$html = '<div class="qrcode-container">';
			$html .= '<div class="qrcode-img"><img alt="' . $qrcode . '" ';
			$html .= 'src="' . wgc_content_to_base64($image, wgc_get_mime_type_for_file($file)) . '"';
			$html .= "></div>";

			if ($meta["wgc-coupon-qrcode-code"][0] == "yes") {
			    $html .= '<div class="qrcode">' . $qrcode . '</div>';
			}

			$html .= "</div>";

			break;
		    case 'barcode':

			$generator = false;
			$barcode = $prefix . $code . $suffix;
			$html = '<div class="barcode-container">';

			switch ($meta["wgc-coupon-barcode-image-type"][0]) {
			    case "html";
				$generator = new Picqer\Barcode\BarcodeGeneratorHTML();

				$html .= $generator->getBarcode($code, $meta["wgc-coupon-barcode-type"][0], $meta["wgc-coupon-barcode-width"][0], $meta["wgc-coupon-barcode-height"][0], $meta["wgc-coupon-barcode-color"][0]);

				break;
			    case "svg";
				$generator = new Picqer\Barcode\BarcodeGeneratorSVG();
				$color = $meta["wgc-coupon-barcode-color"][0];
			    case "png";
				$generator = is_object($generator) ? $generator : new Picqer\Barcode\BarcodeGeneratorPNG();
			    case "jpg";
				$generator = is_object($generator) ? $generator : new Picqer\Barcode\BarcodeGeneratorJPG();

				$color ?: $this->hexColorToArray($meta["wgc-coupon-barcode-color"][0]);

				$image = $generator->getBarcode($code, $meta["wgc-coupon-barcode-type"][0], $meta["wgc-coupon-barcode-width"][0], $meta["wgc-coupon-barcode-height"][0], $color);

				$html .= '<div class="barcode-img"><img alt="' . $barcode . '" ';
				$html .= 'src="' . wgc_content_to_base64($image, wgc_get_mime_type_for_file("image." . $meta["wgc-coupon-barcode-image-type"][0])) . '"';
				$html .= "></div>";

				break;
			}

			$html .= '<div class="barcode" style="color: ' . esc_attr($meta["wgc-coupon-barcode-color"][0]) . ';">' . $barcode . '</div>';
			$html .= "</div>";

			break;
		    default:
			$html = $prefix . $code . $suffix;
		}

		break;
	}

	if (empty($html)) {
	    $html = '[' . strtoupper($shortcode) . ']';
	}

	return $html;
    }

    /**
     * Convert a hex color to dec
     * @param string $color
     * @return array
     */
    private function hexColorToArray($color) {

	$color = trim($color, "#");

	$chunks = array_slice(explode(".", chunk_split($color, 2, ".")), 0, 3);

	return array_map("hexdec", $chunks);
    }

}
