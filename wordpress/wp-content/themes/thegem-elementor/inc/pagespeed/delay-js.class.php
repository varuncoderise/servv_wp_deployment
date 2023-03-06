<?php
class TheGem_DelayJS {
	public function __construct() {
		if ($this->canProcessContent()) {
			$this->initHooks();
		}
	}

	private function canProcessContent() {
		if ($_SERVER['REQUEST_METHOD'] != 'GET') {
			return false;
		}
		if (defined('WP_ADMIN')) {
			return false;
		}
		if (is_user_logged_in()) {
			return false;
		}
		if (defined('WP_BLOG_ADMIN')) {
			return false;
		}
		if (defined('DOING_AJAX') || (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest')) {
			return false;
		}
		if (defined('DOING_CRON')) {
			return false;
		}
		if (defined('APP_REQUEST')) {
			return false;
		}
		if (defined('XMLRPC_REQUEST')) {
			return false;
		}
		if (defined('SHORTINIT') && SHORTINIT) {
			return false;
		}
		return true;
	}

	public function init() {
		ob_start(array($this, 'obCallback'));
	}

	private function initHooks() {

		if (did_action('init') > 0) {
			$this->init();
		} else {
			add_action('init', array($this, 'init'), 9999);
		}

		add_action('wp_head', array($this, 'printHeadScripts'), 0);
	}

	public function obCallback($buffer) {
		if(thegem_delay_js_active()) {
			$buffer = $this->parseHTML($buffer);
		}
		return $buffer;
	}

	private function parseHTML( $html ): string {
		$replaced_html = preg_replace_callback(
			'/<\s*script\s*(?<attr>[^>]*?)?>(?<content>.*?)?<\s*\/\s*script\s*>/ims',
			[
				$this,
				'replace_scripts',
			],
			$html
		);

		if ( empty( $replaced_html ) ) {
			return $html;
		}

		return $replaced_html;
	}

	public function replace_scripts( $matches ): string {
		$excluded = apply_filters('thegem_delay_js_exclusions', array('autoptimize_', '\/jquery-?([0-9.]){0,10}\.(min\.|slim\.|slim\.min\.)?js','revslider','layerslider','\(','\{','\/recaptcha\/api\.js','odometer.js'));
		foreach ( $excluded as $pattern ) {
			if ( preg_match( "#{$pattern}#i", $matches[0] ) ) {
				return $matches[0];
			}
		}

		$matches['attr'] = trim( $matches['attr'] );
		$delay_js = $matches[0];
		if ( ! empty( $matches['attr'] ) ) {

			if (
				strpos( $matches['attr'], 'type=' ) !== false
				&&
				! preg_match( '/type\s*=\s*["\'](?:text|application)\/(?:(?:x\-)?javascript|ecmascript|jscript)["\']|type\s*=\s*["\'](?:module)[ "\']/i', $matches['attr'] )
			) {
				return $matches[0];
			}

			$delay_attr = preg_replace( '/type=(["\'])(.*?)\1/i', 'data-thegem-$0', $matches['attr'], 1 );

			if ( null !== $delay_attr ) {
				$delay_js = preg_replace( '#' . preg_quote( $matches['attr'], '#' ) . '#i', $delay_attr, $matches[0], 1 );
			}
		}
		return preg_replace( '/<script/i', '<script type="thegemdelayscript"', $delay_js, 1 );
	}

	public function printHeadScripts() {
		if(!thegem_delay_js_active()) return ;
?>
<script type="text/javascript">
<?php echo file_get_contents(get_template_directory() . '/js/thegem-delay-javascript.min.js'); ?>
</script>
<?php
	}

}