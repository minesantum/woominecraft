<?php

namespace WooMinecraft\Helpers;

const WM_SERVERS = 'wm_servers';

/**
 * Sets up all the things related to Order handling.
 */
function setup() {
	$n = function( $string ) {
		return __NAMESPACE__ . '\\' . $string;
	};

	add_action( 'template_redirect', $n( 'deprecate_json_feed' ) );
	add_filter( 'woocommerce_get_wp_query_args', $n( 'filter_query' ), 10, 2 );

	// Simplify Checkout: Remove unnecessary fields
	add_filter( 'woocommerce_checkout_fields', $n( 'simplify_checkout_fields' ), 9999 );
	add_filter( 'woocommerce_enable_order_notes_field', $n( 'disable_order_notes_if_minecraft' ), 9999 );
}

/**
 * Disables the order notes field if minecraft items are in cart.
 */
function disable_order_notes_if_minecraft( $enabled ) {
	if ( ! class_exists( 'WooCommerce' ) || ! isset( WC()->cart ) ) {
		return $enabled;
	}

	$items = WC()->cart->get_cart_contents();
	if ( empty( $items ) || ! wmc_items_have_commands( $items ) ) {
		return $enabled;
	}

	return false;
}

/**
 * Removes unnecessary fields from checkout.
 *
 * @param array $fields
 * @return array
 */
function simplify_checkout_fields( $fields ) {
	if ( ! class_exists( 'WooCommerce' ) || ! isset( WC()->cart ) ) {
		return $fields;
	}

	$items = WC()->cart->get_cart_contents();
	if ( empty( $items ) || ! wmc_items_have_commands( $items ) ) {
		return $fields;
	}

	// Standard Unsets
	$targets = [ 'billing_first_name', 'billing_last_name', 'billing_company', 'billing_address_1', 'billing_address_2', 'billing_city', 'billing_state', 'billing_postcode', 'billing_country', 'billing_phone', 'order_comments' ];
	
	// Remove Billing fields
	foreach ( $targets as $target ) {
		if ( isset( $fields['billing'][ $target ] ) ) {
			unset( $fields['billing'][ $target ] );
		}
	}

	// Hide the email field and populate it automatically
	if ( isset( $fields['billing']['billing_email'] ) ) {
		$fields['billing']['billing_email']['type'] = 'hidden';
		unset( $fields['billing']['billing_email']['label'] ); // Eliminar la etiqueta
		$fields['billing']['billing_email']['class'] = [ 'hidden', 'hide' ]; // Añadir clases para ocultar el wrapper
		
		if ( is_user_logged_in() ) {
			$fields['billing']['billing_email']['default'] = wp_get_current_user()->user_email;
		} else {
			// Fallback for guests so validation doesn't fail
			$domain = isset( $_SERVER['HTTP_HOST'] ) ? sanitize_text_field( wp_unslash( $_SERVER['HTTP_HOST'] ) ) : 'example.com';
			$fields['billing']['billing_email']['default'] = 'guest@' . $domain;
		}
	}
	
	// Remove Shipping fields entirely
	if ( isset( $fields['shipping'] ) ) {
		unset( $fields['shipping'] );
	}
	
	// Remove Order Notes
	if ( isset( $fields['order']['order_comments'] ) ) {
		unset( $fields['order']['order_comments'] );
	}
	
	return $fields;
}

/**
 * Adds meta query capability to the WooCommerce order method.
 * @param $wp_query_args
 * @param $query_vars
 *
 * @return mixed
 */
function filter_query( $wp_query_args, $query_vars ) {
	if ( isset( $query_vars['meta_query'] ) ) {
		$meta_query                  = isset( $wp_query_args['meta_query'] ) ? $wp_query_args['meta_query'] : [];
		$wp_query_args['meta_query'] = array_merge( $meta_query, $query_vars['meta_query'] );
	}
	return $wp_query_args;
}

/**
 * Sends an error to the user.
 *
 * The error lets the user know that the MC version of the plugin is out of date.
 */
function deprecate_json_feed() {
	if ( ! isset( $_GET['wmc_key'] ) ) {
		return;
	}
	wp_send_json_error( [ 'msg' => esc_html__( 'You are using an older version, please update your Minecraft plugin.', 'woominecraft' ) ] );
}

/**
 * Determines if any item in the cart has WMC commands attached.
 *
 * @param array $items Cart contents from WooCommerce
 *
 * @return bool
 */
function wmc_items_have_commands( array $items ) {
	foreach ( $items as $item ) {
		$post_id = $item['product_id'];

		if ( ! empty( $item['variation_id'] ) ) {
			$post_id = $item['variation_id'];
		}

		if ( empty( get_post_meta( $post_id, 'wmc_commands', true ) ) ) {
			continue;
		} else {
			return true;
		}
	}

	return false;
}

/**
 * Gets the delivered key for orders.
 * @param string $server
 *
 * @return string
 */
function get_meta_key_delivered( $server ) {
	return '_wmc_delivered_' . $server;
}

/**
 * Gets the pending meta key for orders.
 * @param string $server
 *
 * @return string
 */
function get_meta_key_pending( $server ) {
	return '_wmc_commands_' . $server;
}

/**
 * Gets the query parameters to grab order data.
 *
 * @param string $server The server.
 *
 * @return array
 */
function get_order_query_params( $server ) {
	return apply_filters(
		'woo_minecraft_json_orders_args',
		array(
			'limit'      => '-1',
			'status'     => 'completed',
			'meta_query' => array(
				'relation' => 'AND',
				array(
					'key'     => get_meta_key_pending( $server ),
					'compare' => 'EXISTS',
				),
				array(
					'key'     => get_meta_key_delivered( $server ),
					'compare' => 'NOT EXISTS',
				),
			),
		)
	);
}
