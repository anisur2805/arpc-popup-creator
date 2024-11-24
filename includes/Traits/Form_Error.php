<?php
namespace ARPC\Popup\Traits;

/**
 * Form error handler trait
 */
trait Form_Error {

	public $errors = array();

	public function has_errors( $key ) {
		return isset( $this->errors[ $key ] ) ? true : false;
	}

	public function get_error( $key ) {
		if ( isset( $this->errors[ $key ] ) ) {
			return $this->errors[ $key ];
		}

		return false;
	}
}
