<?php
/**
 * Make everything go
 *
 * @package CF_EDD
 * @author    Josh Pollock <Josh@CalderaWP.com>
 * @license   GPL-2.0+
 * @link
 * @copyright 2016 CalderaWP LLC
 */
namespace calderawp\cfeddfields;


class setup {

	/**
	 * @var string
	 */
	protected static $slug = 'edd-licensed-downloads';

	/**
	 * Add hooks
	 */
	public static function add_hooks(){
		add_action( 'caldera_forms_pre_load_processors', [ __CLASS__, 'add_processor' ] );
		add_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'init_license_field' ], 10, 2 );
	}

	/**
	 * Remove hooks
	 */
	public static function remove_hooks(){
		remove_action( 'caldera_forms_pre_load_processors', [ __CLASS__, 'add_processor' ] );
		remove_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'init_license_field' ], 10 );
	}

	/**
	 * Load the EDD SL processor
	 *
	 * @uses "caldera_forms_pre_load_processors" action
	 */
	public static function add_processor(){

		$config = [
			"name"				=>	__( 'EDD: Licensed Downloads', 'cf-edd'),
			"description"		=>	__( 'Populate a select field with a user\'s licensed downloads.', 'cf-edd'),
			"icon"				=>	plugin_dir_url( __FILE__ )  . '/icon.png',
			"author"			=>	"Josh Pollock for CalderaWP LLC",
			"author_url"		=>	"https://CalderaWP.com",
			"template"			=>	__DIR__ . '/config-licensed-downloads.php',
		];
		$fields = [];
		new processor( $config, $fields, self::$slug );

	}

	/**
	 * Setup license field for EDD SL processor
	 *
	 * @uses "caldera_forms_render_get_field" filter
	 *
	 * @param array $field
	 * @param array $form
	 *
	 * @return array
	 */
	public static function init_license_field( $field, $form ){

		if( $processors = \Caldera_Forms::get_processor_by_type( 'edd-licensed-downloads', $form ) ){
			foreach( $processors as $processor ){
				if( $field['ID'] === $processor['config']['edd_licensed_downloads'] ){
					$user_id = null;
					if ( ! empty( $config[ 'config' ][ 'edd_licensed_downloads_user' ] ) && 0 < absint( $config[ 'config' ][ 'edd_licensed_downloads_user' ] ) ) {
						$user_id = $config[ 'config' ][ 'edd_licensed_downloads_user' ];
					}elseif ( is_user_logged_in() ){
						$user_id = get_current_user_id();
					}

					$downloads = license::get_downloads_by_licensed_user( $user_id );
					$field[ 'config' ][ 'option' ] = array();
					if ( ! empty( $downloads ) ) {
						foreach( $downloads as $id => $title ) {
							$field[ 'config' ][ 'option' ][ ] = array(
								'label' => esc_html( $title ),
								'value' => (int) $id,
							);
						}
					}


				}

			}

		}

		return $field;

	}

}