<?php
/*
 * Plugin Name:		Cart Filter
 * Description:		Filter products by category from cart
 * Version:		0.1
 * Author:		MBPF
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

add_filter( 'woocommerce_cart_item_visible', 'mbpf_hide_category_from_cart', 10, 3 );


function mbpf_hide_category_from_cart($visible, $cart_item, $cart_item_key) {
	$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
	echo get_product_category_name_by_id($product->category_ids[0]);
	$catergory = get_product_category_name_by_id($product->category_ids[0]);
	echo var_dump($category);
	if ( strcmp("Zutat", get_product_category_name_by_id($product->category_ids[0])) == 0 ) {
		$visible = false;
	}
	return $visible;
}

function get_product_category_name_by_id( $category_id ) {
  $term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
  return $term['name'];
}
