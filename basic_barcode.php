<?php

/*
 * Plugin Name: basic_barcode
 * Plugin URI: https://localhost/plugins
 * Description: barcode generator
 * Author: nullstep
 * Author URI: https://localhost
 * Version: 1.0.1
 */

defined('ABSPATH') or die('⎺\_(ツ)_/⎺');

// defines      

define('_PLUGIN_BASIC_BARCODE', 'basic_barcode');
define('_PLUGIN_BASIC_BARCODE_ICON', '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 500 500"><path fill="#a7aaad" d="M250,9.8L42,129.9v240.2l208,120.1,208-120.1v-240.2L250,9.8ZM112.4,361h-10.4v-222h10.4v222ZM122.8,360.8h-5.3v-221.8h5.3v221.8ZM138.3,360.8h-5.1v-221.8h5.1v221.8ZM164.3,360.8h-5.1v-221.8h5.1v221.8ZM190.2,360.8h-10.2v-221.8h10.2v221.8ZM211,360.8h-5.1v-221.8h5.1v221.8ZM221.4,360.8h-5.1v-221.8h5.1v221.8ZM231.8,360.8h-5.1v-221.8h5.1v221.8ZM257.8,360.8h-10.4v-221.8h10.4v221.8ZM283.7,360.8h-10.4v-221.8h10.4v221.8ZM304.5,360.8h-10.4v-221.8h10.4v221.8ZM325.3,360.8h-10.4v-221.8h10.4v221.8ZM340.8,360.8h-10.4v-221.8h10.4v221.8ZM372.1,360.8h-15.5v-221.8h15.5v221.8ZM382.5,360.8h-5.3v-221.8h5.3v221.8ZM398,361h-10.4v-222h10.4v222Z"/></svg>');

define('_URL_BASIC_BARCODE', plugin_dir_url(__FILE__));
define('_PATH_BASIC_BARCODE', plugin_dir_path(__FILE__));

//   ▄████████   ▄██████▄   ███▄▄▄▄▄       ▄████████  
//  ███    ███  ███    ███  ███▀▀▀▀██▄    ███    ███  
//  ███    █▀   ███    ███  ███    ███    ███    █▀   
//  ███         ███    ███  ███    ███   ▄███▄▄▄      
//  ███         ███    ███  ███    ███  ▀▀███▀▀▀      
//  ███    █▄   ███    ███  ███    ███    ███         
//  ███    ███  ███    ███  ███    ███    ███         
//  ████████▀    ▀██████▀    ▀█    █▀     ███

// basic_barcode args

define('_ARGS_BASIC_BARCODE', [
	'bbc_active' => [
		'type' => 'string',
		'default' => 'no'
	]
]);

// basic_barcode admin

define('_ADMIN_BASIC_BARCODE', [
	'options' => [
		'label' => 'Options',
		'columns' => 4,
		'fields' => [
			'bbc_active' => [
				'label' => 'Plugin Active',
				'type' => 'check'
			]
		]
	]
]);

// basic_barcode api routes

define('_APIPATH_BASIC_BARCODE',
	'settings'
);

define('_API_BASIC_BARCODE', [
	[
		'methods' => 'POST',
		'callback' => 'update_settings',
		'args' => _bbcSettings::args(),
		'permission_callback' => 'permissions'
	],
	[
		'methods' => 'GET',
		'callback' => 'get_settings',
		'args' => [],
		'permission_callback' => 'permissions'
	]
]);

//     ▄████████     ▄███████▄   ▄█   
//    ███    ███    ███    ███  ███   
//    ███    ███    ███    ███  ███▌  
//    ███    ███    ███    ███  ███▌  
//  ▀███████████  ▀█████████▀   ███▌  
//    ███    ███    ███         ███   
//    ███    ███    ███         ███   
//    ███    █▀    ▄████▀       █▀ 

class _bbcAPI {
	public function add_routes() {
		if (count(_API_BASIC_BARCODE)) {

			foreach(_API_BASIC_BARCODE as $route) {
				register_rest_route(_PLUGIN_BASIC_BARCODE . '-api', '/' . _APIPATH_BASIC_BARCODE, [
					'methods' => $route['methods'],
					'callback' => [$this, $route['callback']],
					'args' => $route['args'],
					'permission_callback' => [$this, $route['permission_callback']]
				]);
			}
		}
	}

	public function permissions() {
		return current_user_can('manage_options');
	}

	public function update_settings(WP_REST_Request $request) {
		$settings = [];
		foreach (_bbcSettings::args() as $key => $val) {
			$settings[$key] = $request->get_param($key);
		}
		_bbcSettings::save_settings($settings);
		return rest_ensure_response(_bbcSettings::get_settings());
	}

	public function get_settings(WP_REST_Request $request) {
		return rest_ensure_response(_bbcSettings::get_settings());
	}
}

//     ▄████████     ▄████████      ███          ███       ▄█   ███▄▄▄▄▄       ▄██████▄      ▄████████  
//    ███    ███    ███    ███  ▀█████████▄  ▀█████████▄  ███   ███▀▀▀▀██▄    ███    ███    ███    ███  
//    ███    █▀     ███    █▀      ▀███▀▀██     ▀███▀▀██  ███▌  ███    ███    ███    █▀     ███    █▀   
//    ███          ▄███▄▄▄          ███   ▀      ███   ▀  ███▌  ███    ███   ▄███           ███         
//  ▀███████████  ▀▀███▀▀▀          ███          ███      ███▌  ███    ███  ▀▀███ ████▄   ▀███████████  
//           ███    ███    █▄       ███          ███      ███   ███    ███    ███    ███           ███  
//     ▄█    ███    ███    ███      ███          ███      ███   ███    ███    ███    ███     ▄█    ███  
//   ▄████████▀     ██████████     ▄████▀       ▄████▀    █▀     ▀█    █▀     ████████▀    ▄████████▀ 

class _bbcSettings {
	protected static $option_key = _PLUGIN_BASIC_BARCODE . '-settings';

	public static function args() {
		$args = _ARGS_BASIC_BARCODE;
		foreach (_ARGS_BASIC_BARCODE as $key => $val) {
			$val['required'] = true;
			switch ($val['type']) {
				case 'integer': {
					$cb = 'absint';
					break;
				}
				default: {
					$cb = 'sanitize_text_field';
				}
				$val['sanitize_callback'] = $cb;
			}
		}
		return $args;
	}

	public static function get_settings() {
		$defaults = [];
		foreach (_ARGS_BASIC_BARCODE as $key => $val) {
			$defaults[$key] = $val['default'];
		}
		$saved = get_option(self::$option_key, []);
		if (!is_array($saved) || empty($saved)) {
			return $defaults;
		}
		return wp_parse_args($saved, $defaults);
	}

	public static function save_settings(array $settings) {
		$defaults = [];
		foreach (_ARGS_BASIC_BARCODE as $key => $val) {
			$defaults[$key] = $val['default'];
		}
		foreach ($settings as $i => $setting) {
			if (!array_key_exists($i, $defaults)) {
				unset($settings[$i]);
			}
		}
		update_option(self::$option_key, $settings);
	}
}

//    ▄▄▄▄███▄▄▄▄       ▄████████  ███▄▄▄▄▄    ███    █▄   
//  ▄██▀▀▀███▀▀▀██▄    ███    ███  ███▀▀▀▀██▄  ███    ███  
//  ███   ███   ███    ███    █▀   ███    ███  ███    ███  
//  ███   ███   ███   ▄███▄▄▄      ███    ███  ███    ███  
//  ███   ███   ███  ▀▀███▀▀▀      ███    ███  ███    ███  
//  ███   ███   ███    ███    █▄   ███    ███  ███    ███  
//  ███   ███   ███    ███    ███  ███    ███  ███    ███  
//   ▀█   ███   █▀     ██████████   ▀█    █▀   ████████▀ 

class _bbcMenu {
	protected $slug = _PLUGIN_BASIC_BARCODE . '-menu';
	protected $assets_url;

	public function __construct($assets_url) {
		$this->assets_url = $assets_url;
		add_action('admin_menu', [$this, 'add_page']);
		add_action('admin_enqueue_scripts', [$this, 'register_assets']);
	}

	public function add_page() {
		add_menu_page(
			_PLUGIN_BASIC_BARCODE,
			_PLUGIN_BASIC_BARCODE,
			'manage_options',
			$this->slug,
			[$this, 'render_admin'],
			'data:image/svg+xml;base64,' . base64_encode(_PLUGIN_BASIC_BARCODE_ICON),
			30
		);

		// add config submenu

		add_submenu_page(
			$this->slug,
			'Configuration',
			'Configuration',
			'manage_options',
			$this->slug
		);
	}

	public function register_assets() {
		$boo = microtime(false);
		wp_register_script($this->slug, $this->assets_url . '/' . _PLUGIN_BASIC_BARCODE . '.js?' . $boo, ['jquery']);
		wp_register_style($this->slug, $this->assets_url . '/' . _PLUGIN_BASIC_BARCODE . '.css?' . $boo);
		wp_localize_script($this->slug, _PLUGIN_BASIC_BARCODE, [
			'strings' => [
				'saved' => 'Settings Saved',
				'error' => 'Error'
			],
			'api' => [
				'url' => esc_url_raw(rest_url(_PLUGIN_BASIC_BARCODE . '-api/settings')),
				'nonce' => wp_create_nonce('wp_rest')
			]
		]);
	}

	public function enqueue_assets() {
		if (!wp_script_is($this->slug, 'registered')) {
			$this->register_assets();
		}

		wp_enqueue_script($this->slug);
		wp_enqueue_style($this->slug);
	}

	public function render_admin() {
		wp_enqueue_media();
		$this->enqueue_assets();

		$name = _PLUGIN_BASIC_BARCODE;
		$form = _ADMIN_BASIC_BARCODE;

		// build form

		echo '<div id="' . $name . '-wrap" class="wrap">';
			echo '<h1>' . $name . '</h1>';
			echo '<p>Configure your ' . $name . ' settings...</p>';
			echo '<form id="' . $name . '-form" method="post">';
				echo '<nav id="' . $name . '-nav" class="nav-tab-wrapper">';

				foreach ($form as $tid => $tab) {
					echo '<a href="#' . $name . '-' . $tid . '" class="nav-tab">' . $tab['label'] . '</a>';
				}
				echo '</nav>';
				echo '<div class="tab-content">';

				foreach ($form as $tid => $tab) {
					echo '<div id="' . $name . '-' . $tid . '" class="' . $name . '-tab">';

					foreach ($tab['fields'] as $fid => $field) {
						echo '<div class="form-block col-' . $tab['columns'] . '">';
						
						switch ($field['type']) {
							case 'input': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<input id="' . $fid . '" type="text" name="' . $fid . '">';
								break;
							}
							case 'select': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<select id="' . $fid . '" name="' . $fid . '">';
									foreach ($field['values'] as $value => $label) {
										echo '<option value="' . $value . '">' . $label . '</option>';
									}
								echo '</select>';
								break;
							}
							case 'text': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<textarea id="' . $fid . '" class="tabs" name="' . $fid . '"></textarea>';
								break;
							}
							case 'file': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<input id="' . $fid . '" type="text" name="' . $fid . '">';
								echo '<input data-id="' . $fid . '" type="button" class="button-primary choose-file-button" value="...">';
								break;
							}
							case 'colour': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<input id="' . $fid . '" type="text" name="' . $fid . '">';
								echo '<input data-id="' . $fid . '" type="color" class="choose-colour-button" value="#000000">';
								break;
							}
							case 'code': {
								echo '<label for="' . $fid . '">';
									echo $field['label'] . ':';
								echo '</label>';
								echo '<textarea id="' . $fid . '" class="code" name="' . $fid . '"></textarea>';
								break;
							}
							case 'check': {
								echo '<em>' . $field['label'] . ':</em>';
								echo '<label class="switch">';
									echo '<input type="checkbox" id="' . $fid . '" name="' . $fid . '" value="yes">';
									echo '<span class="slider"></span>';
								echo '</label>';
								break;
							}
						}
						echo '</div>';
					}
					echo '</div>';
				}
				echo '</div>';
				echo '<div>';
					submit_button();
				echo '</div>';
				echo '<div id="' . $name . '-feedback"></div>';
			echo '</form>';
		echo '</div>';
	}
}

// menu stuff

function bbc_set_current_menu($parent_file) {
	global $submenu_file, $current_screen, $pagenow;

	if (in_array($current_screen->id, ['edit-svg', 'svg'])) {
		if ($pagenow == 'post.php') {
			$submenu_file = 'edit.php?post_type=' . $current_screen->post_type;
		}
		$parent_file = _PLUGIN_BASIC_BARCODE . '-menu';
	}
	return $parent_file;
}

//   ▄█   ███▄▄▄▄▄     ▄█       ███      
//  ███   ███▀▀▀▀██▄  ███   ▀█████████▄  
//  ███▌  ███    ███  ███▌     ▀███▀▀██  
//  ███▌  ███    ███  ███▌      ███   ▀  
//  ███▌  ███    ███  ███▌      ███      
//  ███   ███    ███  ███       ███      
//  ███   ███    ███  ███       ███      
//  █▀     ▀█    █▀   █▀       ▄████▀

function bbc_init($dir) {
	// do something
}


//     ▄████████      ███      ▄██   ▄     ▄█           ▄████████  
//    ███    ███  ▀█████████▄  ███   ██▄  ███          ███    ███  
//    ███    █▀      ▀███▀▀██  ███▄▄▄███  ███          ███    █▀   
//    ███             ███   ▀  ▀▀▀▀▀▀███  ███         ▄███▄▄▄      
//  ▀███████████      ███      ▄██   ███  ███        ▀▀███▀▀▀      
//           ███      ███      ███   ███  ███          ███    █▄   
//     ▄█    ███      ███      ███   ███  ███▌    ▄    ███    ███  
//   ▄████████▀      ▄████▀     ▀█████▀   █████▄▄██    ██████████

// some admin styling

function bbc_admin_styling() {
	// do something
}

// add admin scripts

function bbc_add_scripts($hook) {
	// do something
}


//    ▄▄▄▄███▄▄▄▄       ▄████████      ███         ▄████████  
//  ▄██▀▀▀███▀▀▀██▄    ███    ███  ▀█████████▄    ███    ███  
//  ███   ███   ███    ███    █▀      ▀███▀▀██    ███    ███  
//  ███   ███   ███   ▄███▄▄▄          ███   ▀    ███    ███  
//  ███   ███   ███  ▀▀███▀▀▀          ███      ▀███████████  
//  ███   ███   ███    ███    █▄       ███        ███    ███  
//  ███   ███   ███    ███    ███      ███        ███    ███  
//   ▀█   ███   █▀     ██████████     ▄████▀      ███    █▀   

function bbc_add_metaboxes() {
	// do something
}

function bbc_stripe_metabox($post) {
	// do something
}

function bbc_save_postdata($post_id) {
	// do something
}


//     ▄████████     ▄█    █▄      ▄██████▄      ▄████████      ███      
//    ███    ███    ███    ███    ███    ███    ███    ███  ▀█████████▄  
//    ███    █▀     ███    ███    ███    ███    ███    ███     ▀███▀▀██  
//    ███          ▄███▄▄▄▄███▄▄  ███    ███   ▄███▄▄▄▄██▀      ███   ▀  
//  ▀███████████  ▀▀███▀▀▀▀███▀   ███    ███  ▀▀███▀▀▀▀▀        ███      
//           ███    ███    ███    ███    ███  ▀███████████      ███      
//     ▄█    ███    ███    ███    ███    ███    ███    ███      ███      
//   ▄████████▀     ███    █▀      ▀██████▀     ███    ███     ▄████▀

//   ▄████████   ▄██████▄   ████████▄      ▄████████  
//  ███    ███  ███    ███  ███   ▀███    ███    ███  
//  ███    █▀   ███    ███  ███    ███    ███    █▀   
//  ███         ███    ███  ███    ███   ▄███▄▄▄      
//  ███         ███    ███  ███    ███  ▀▀███▀▀▀      
//  ███    █▄   ███    ███  ███    ███    ███    █▄   
//  ███    ███  ███    ███  ███   ▄███    ███    ███  
//  ████████▀    ▀██████▀   ████████▀     ██████████  

function bbc_shortcode($atts = [], $content = null, $tag = '') {
	$a = shortcode_atts([], $atts);

	return null;
}

// caller function

function basic_barcode($pdf) {
	echo do_shortcode('[pdf]' . $pdf . '[/pdf]');
}

//     ▄██████▄    ▄██████▄   
//    ███    ███  ███    ███  
//    ███    █▀   ███    ███  
//   ▄███         ███    ███  
//  ▀▀███ ████▄   ███    ███  
//    ███    ███  ███    ███  
//    ███    ███  ███    ███  
//    ████████▀    ▀██████▀

define('_BPDF', _bbcSettings::get_settings());

// actions

add_action('init', 'bbc_init');
add_action('admin_head', 'bbc_admin_styling');
add_action('admin_enqueue_scripts', 'bbc_add_scripts');
add_action('add_meta_boxes', 'bbc_add_metaboxes');
add_action('save_post', 'bbc_save_postdata');

// filters

add_filter('parent_file', 'bbc_set_current_menu');

// shortcodes

add_shortcode('pdf', 'bbc_shortcode');

// boot plugin

add_action('init', function() {
	if (is_admin()) {
		new _bbcMenu(_URL_BASIC_BARCODE);
	}
});

add_action('rest_api_init', function() {
	_bbcSettings::args();
	$api = new _bbcAPI();
	$api->add_routes();
});

// eof