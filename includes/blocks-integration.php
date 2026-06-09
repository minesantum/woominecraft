<?php
/**
 * Handles compatibility with the Block-based Checkout.
 *
 * @package WooMinecraft
 */

namespace WooMinecraft\Blocks;

/**
 * setup the block integration.
 */
function setup() {
	// Intercept the rendering of WooCommerce cart/checkout blocks and replace them with classic shortcodes if needed.
	add_filter( 'render_block', __NAMESPACE__ . '\\force_classic_blocks', 10, 2 );
}

/**
 * Intercepts WooCommerce block rendering and forces classic shortcodes if Minecraft items are in the cart.
 *
 * @param string $block_content The block content.
 * @param array  $block         The full block, including name and attributes.
 * @return string
 */
function force_classic_blocks( $block_content, $block ) {
	if ( ! isset( $block['blockName'] ) ) {
		return $block_content;
	}

	$target_blocks = array( 'woocommerce/checkout', 'woocommerce/cart' );

	if ( in_array( $block['blockName'], $target_blocks, true ) ) {
		if ( ! class_exists( 'WooCommerce' ) || ! isset( WC()->cart ) ) {
			return $block_content;
		}

		$items = WC()->cart->get_cart_contents();
		if ( empty( $items ) || ! \WooMinecraft\Helpers\wmc_items_have_commands( $items ) ) {
			return $block_content;
		}

		// If Minecraft items are present, fallback to classic shortcodes.
		if ( 'woocommerce/checkout' === $block['blockName'] ) {
			return do_shortcode( '[woocommerce_checkout]' );
		}

		if ( 'woocommerce/cart' === $block['blockName'] ) {
			return do_shortcode( '[woocommerce_cart]' );
		}
	}

	return $block_content;
}
