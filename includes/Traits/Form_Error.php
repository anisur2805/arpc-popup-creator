<?php
/**
 * Form error handler trait.
 *
 * @package ARPC\Popup
 */

namespace ARPC\Popup\Traits;

/**
 * Form error handler trait
 */
trait Form_Error {

	/**
	 * Collected errors, keyed by field name.
	 *
	 * @var array
	 */
	public $errors = array();

	/**
	 * Check whether a field has an error.
	 *
	 * @param string $key Field name.
	 * @return bool
	 */
	public function has_errors( $key ) {
		return isset( $this->errors[ $key ] ) ? true : false;
	}

	/**
	 * Get the error message for a field.
	 *
	 * @param string $key Field name.
	 * @return string|false Error message, or false when the field has no error.
	 */
	public function get_error( $key ) {
		if ( isset( $this->errors[ $key ] ) ) {
			return $this->errors[ $key ];
		}

		return false;
	}
}
