<?php

/**
 * Exit if accessed directly
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Validates the validity of an MD5 hash.
 *
 * @see https://stackoverflow.com/a/14300703
 *
 * @param string $md5
 *
 * @return bool
 */
function dfads_is_valid_md5( string $md5 = '' ): bool {
	return boolval( preg_match( '/^[a-f0-9]{32}$/', $md5 ) );
}

/**
 * Returns the hash value to use in signed requests.
 *
 * This function will only return a valid MD5 hash. If the value returned
 * from the database does not exist OR is an invalid MD5 hash, this
 * function will create and save a new MD5 hash and return the new hash.
 *
 * @since 0.9.68
 *
 * @return string
 */
function dfads_get_hash(): string {

	$option = 'dfads_hash';
	$hash   = get_option( $option, false );

	if ( dfads_is_valid_md5( $hash ) ) {
		return $hash;
	}

	$password = wp_generate_password( 64, true, true );
	$hash     = wp_hash( $password );

	update_option( $option, $hash, false );

	return $hash;
}

/**
 * Return a "signature" for the $data.
 *
 * @since 0.9.68
 *
 * @param string $data
 *
 * @return bool|string
 */
function dfads_hash_hmac( string $data ) {
	$algo = 'sha256';
	$key  = dfads_get_hash();

	return hash_hmac( $algo, $data, $key );
}

/**
 * All allowed callback functions are now required to be listed in this array.
 *
 * These can be added at the code level like this:
 *
 *  add_filter( 'dfads_allowed_callback_functions', function ( array $functions ) {
 *      $functions[] = 'my_first_custom_callback_function';
 *      $functions[] = 'my_second_custom_callback_function';
 *
 *      return $functions;
 *  } );
 *
 * @since 1.2.0
 *
 * @return array
 */
function dfads_allowed_callback_functions(): array {
	return (array) apply_filters( 'dfads_allowed_callback_functions', [] );
}
