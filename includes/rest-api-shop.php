<?php

namespace WooMinecraft\REST;

/**
 * Get all product categories.
 *
 * @param \WP_REST_Request $request
 * @return \WP_Error|array
 */
function get_categories( $request ) {
	$server_key = esc_attr( $request->get_param( 'server' ) );
    
    // Validate key
	$servers    = get_option( 'wm_servers', [] );
    if ( empty( $servers ) ) return new \WP_Error( 'no_servers', 'No servers setup', [ 'status' => 200 ] );
	$keys = wp_list_pluck( $servers, 'key' );
	if ( false === array_search( $server_key, $keys, true ) ) {
		return new \WP_Error( 'invalid_key', 'Invalid Key', [ 'status' => 200 ] );
	}

    $terms = get_terms( [
        'taxonomy'   => 'product_cat',
        'hide_empty' => false,
    ] );

    $categories = [];
    if ( ! empty( $terms ) && ! is_wp_error( $terms ) ) {
        foreach ( $terms as $term ) {
            $categories[] = [
                'id'   => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }
    }

    return [ 'categories' => $categories ];
}

/**
 * Get products by category ID.
 *
 * @param \WP_REST_Request $request
 * @return \WP_Error|array
 */
function get_products_by_category( $request ) {
    $server_key = esc_attr( $request->get_param( 'server' ) );
    $cat_id     = (int) $request->get_param( 'category_id' );

    // Validate key
    $servers    = get_option( 'wm_servers', [] );
    if ( empty( $servers ) ) return new \WP_Error( 'no_servers', 'No servers setup', [ 'status' => 200 ] );
    $keys = wp_list_pluck( $servers, 'key' );
    if ( false === array_search( $server_key, $keys, true ) ) {
        return new \WP_Error( 'invalid_key', 'Invalid Key', [ 'status' => 200 ] );
    }

    $term = get_term( $cat_id, 'product_cat' );
    if ( is_wp_error( $term ) || ! $term ) {
         return [ 'products' => [] ];
    }

    $args = [
        'status'   => 'publish',
        'limit'    => -1,
        'category' => [ $term->slug ], 
    ];

    // Use wc_get_products for better compatibility
    $products_raw = wc_get_products( $args );
    $products = [];

    foreach ( $products_raw as $product ) {
        $products[] = [
            'id'          => $product->get_id(),
            'name'        => $product->get_name(),
            'price'       => html_entity_decode( strip_tags( wc_price( $product->get_price() ) ) ),
            'description' => wp_strip_all_tags( $product->get_short_description() ? $product->get_short_description() : $product->get_description() ),
        ];
    }

    return [ 'products' => $products ];
}
