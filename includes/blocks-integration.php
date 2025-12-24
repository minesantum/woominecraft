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
	if ( function_exists( 'woocommerce_register_additional_checkout_field' ) ) {
		add_action( 'woocommerce_init', __NAMESPACE__ . '\\register_checkout_fields' );
		
		// Ensure old behavior is maintained by syncing the new field to the old meta key.
		add_action( 'woocommerce_checkout_order_created', __NAMESPACE__ . '\\copy_block_field_to_legacy_meta' );
	}
}

/**
 * Registers the field for the Checkout Block.
 */
function register_checkout_fields() {
	woocommerce_register_additional_checkout_field(
		'wmc_player_id',
		array(
			'label'    => __( 'Minecraft Username', 'woominecraft' ),
			'location' => 'address', // 'address' places it in address forms, 'order' places it in "Additional Information"
			'type'     => 'text',
			'required' => true,
			'placeholder' => __( 'Required Field', 'woominecraft' ),
            'attributes' => array(
                'autocomplete' => 'username',
            ),
		)
	);
}

/**
 * Copies the block-based field value to the legacy meta key expected by the rest of the plugin.
 * 
 * @param \WC_Order $order
 */
function copy_block_field_to_legacy_meta( $order ) {
    $block_val = $order->get_meta( 'wmc_player_id' );
    if ( ! empty( $block_val ) ) {
        $order->update_meta_data( 'player_id', $block_val );
        $order->save();
    }
}

