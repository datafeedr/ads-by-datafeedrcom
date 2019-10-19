<?php

/**
 * This class adds the necessary meta boxes to the
 * "Add Ad" page.
 */
class DFADS_Meta_Boxes {

	// Call the necessary hooks.
	public function __construct() {
		add_action( 'init', [ $this, 'initialize_cmb_meta_boxes' ] );
		add_filter( 'cmb2_admin_init', [ $this, 'date_range' ] );
		add_filter( 'cmb2_admin_init', [ $this, 'impression_limit' ] );
	}

	// Load Custom Meta Boxes For WordPress Plugin library.
	// https://github.com/jaredatch/Custom-Metaboxes-and-Fields-for-WordPress
	function initialize_cmb_meta_boxes() {
		if ( DFADS_Admin::is_dfads_page() ) {
			require_once( DFADS_PLUGIN_PATH . 'lib/cmb2/init.php' );
		}
	}

	// Add the date range meta box.
	function date_range() {

		$cmb = new_cmb2_box( array(
			'id'           => 'dfads_dates',
			'title'        => esc_html__( 'Date Range', 'dfads' ),
			'object_types' => [ 'dfads' ], // Post type
			'context'      => 'normal',
			'priority'     => 'default',
			'show_names'   => true,
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'Start Date', 'dfads' ),
			'desc' => esc_html__( 'Date this ad should start appearing. Leave blank for immediately. Format: MM/DD/YYYY.',
				'dfads' ),
			'id'   => DFADS_METABOX_PREFIX . 'start_date',
			'type' => 'text_date_timestamp',
		) );

		$cmb->add_field( array(
			'name' => esc_html__( 'End Date', 'dfads' ),
			'desc' => esc_html__( 'Date this ad should stop appearing. Leave blank for never. Format: MM/DD/YYYY.',
				'dfads' ),
			'id'   => DFADS_METABOX_PREFIX . 'end_date',
			'type' => 'text_date_timestamp',
		) );
	}

	// Add impression limit meta box.
	function impression_limit() {

		$cmb = new_cmb2_box( array(
			'id'           => 'dfads_impressions',
			'title'        => esc_html__( 'Impression Limit', 'dfads' ),
			'object_types' => [ 'dfads' ], // Post type
			'context'      => 'normal',
			'priority'     => 'default',
			'show_names'   => false,
		) );

		$cmb->add_field( array(
			'name'            => esc_html__( 'Impressions', 'dfads' ),
			'desc'            => esc_html__( 'Limit the number of impressions this ad is allowed to have.  Leave empty or 0 for no limit.',
				'dfads' ),
			'id'              => DFADS_METABOX_PREFIX . 'impression_limit',
			'type'            => 'text',
			'sanitization_cb' => 'absint',
		) );
	}
}

new DFADS_Meta_Boxes();