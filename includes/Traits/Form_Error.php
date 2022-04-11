<?php
namespace ARPC\Popup\Traits;

/**
 * Form error handler trait
 */ 
trait Form_Error {

    public $errors = [];
    
    function has_error( $key ) {
        return isset( $this->errors[ $key ] ) ? true : false;
    }

    function get_error( $key ) {
        if( isset( $this->errors[ $key ] ) ) {
            return $this->errors[ $key ];
        }

        return false;
    }
}