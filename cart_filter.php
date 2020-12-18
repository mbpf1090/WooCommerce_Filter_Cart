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
// Filter
add_filter( 'woocommerce_cart_item_visible', 'mbpf_hide_category_from_cart', 10, 3 );


function mbpf_hide_category_from_cart($visible, $cart_item, $cart_item_key) {
	$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
	$category_set = get_term_by( 'id', $product->category_ids[0], 'product_cat', 'ARRAY_A' );
	$category = $category_set['name'];

	if ( strcmp("Zutat", $category ) == 0 ) {
		$visible = false;
	}
	$cats = get_all_categories();
	echo var_dump($cats);
	return $visible;
}

function get_product_category_name_by_id( $category_id ) {
	$term = get_term_by( 'id', $category_id, 'product_cat', 'ARRAY_A' );
	return $term['name'];
}

function get_all_categories() {
	$orderby = 'name';
	$order = 'asc';
	$hide_empty = false ;
	$cat_args = array(
    		'orderby'    => $orderby,
    		'order'      => $order,
    		'hide_empty' => $hide_empty,
	);

	return get_terms( 'product_cat', $cat_args );
}

// Menu
add_action('admin_init', 'cart_filter_settings');
add_action('admin_menu', 'cart_filter_admin_menu');

function cart_filter_settings() {
	register_setting('category-settings', 'category_option');
	add_settings_section('category-settings-options', 'Cart Options', 'category_settings_callback', 'filter-cart');
	add_settings_field('category_option', 'Categories', 'category_selection_callback', 'filter-cart', 'category-settings-options');
}

function category_settings_callback() {
	echo 'hallo';
}

function category_selection_callback() {
	$categories = get_all_categories();
	$selected_categories = get_option('category_option');
	//var_dump($selected_categories);
	$output = '';
	$checked = false;
	foreach ($categories as $format) {
		$format = $format->name;
		$output .= '<label><input type="checkbox" id="'.$format.'" name="post_formats['.$format.']" value="1" '.$checked.' /> '.$format.'</label><br>';
	}
	echo $output;	
}



function cart_filter_admin_menu() {
	add_menu_page('Cart Filter', 'Filter Options', 'manage_options', 'filter-cart', 'create_menu_page', 'dashicons-cart', 4 );
}

function create_menu_page() {
	require_once( dirname(__FILE__) . '/card-filter-admin.php' );
}


