<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 12/21/16
 * Time: 6:28 PM
 */

namespace calderawp\cfeddfields;


class setup {

	protected static $slug;


	public static function add_hooks(){
		add_filter('caldera_forms_pre_load_processors', [ __CLASS__, 'add_processor' ] );
		add_filter( 'caldera_forms_render_get_field', [ __CLASS__, 'init_license_field' ], 10, 2 );
	}
	public static function add_processor(){

		$config = [
			"name"				=>	__('EDD: Licensed Downloads', 'cf-edd'),
			"description"		=>	__( 'Populate a select field with a user\'s licensed downloads..', 'cf-edd'),
			"icon"				=>	CF_EDD_URL . "icon.png",
			"author"			=>	"Josh Pollock for CalderaWP LLC",
			"author_url"		=>	"https://CalderaWP.com",
			"template"			=>	CF_EDD_PATH . "includes/config-licensed-downloads.php",
		];
		$fields = [];
		new processor( $config, $fields, self::$slug );
	}

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