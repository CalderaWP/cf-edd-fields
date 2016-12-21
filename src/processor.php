<?php
/**
 * Created by PhpStorm.
 * User: josh
 * Date: 12/21/16
 * Time: 6:27 PM
 */

namespace calderawp\cfeddfields;


class processor extends \Caldera_Forms_Processor_Processor {


	/**
	 * @inheritdoc
	 */
	public function pre_processor( array $config, array $form, $proccesid ) {
		$value = \Caldera_Forms::get_field_data( $config[ 'edd_licensed_downloads' ], $form ); // direct field bind can get data, magic tags wont work.
		$_user = \Caldera_Forms::do_magic_tags( $config[  'edd_licensed_downloads_user'] );
		if ( 0 < absint( $_user ) ) {
			$user = $_user;
		}else{
			$user = null;
		}

		$downloads = license::get_downloads_by_licensed_user( $user );

		if ( ! in_array( $value, array_keys( $downloads ) ) ) {
			return array(
				'type'=>'error',
				'note' => __( "Selected User Does Note Have A License For This Download.", 'cf-edd' )
			);
		}
	}


	public function processor( array $config, array $form, $proccesid ) {}


}