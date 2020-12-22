<?php
/*
 * Plugin Name:		Cart Filter
 * Description:		Filter products by category from cart
 * Version:		0.1
 * Author:		MBPF
 * Plugin URI:		https://github.com/mbpf1090/WooCommerce_Filter_Cart
*/

if (!defined('ABSPATH')) {
	exit; // Exit if accessed directly
}

// Filter
add_filter( 'woocommerce_cart_item_visible', 'mbpf_hide_category_from_cart', 10, 3 );
add_filter( 'woocommerce_widget_cart_item_visible', 'mbpf_hide_category_from_cart', 10, 3 );
add_filter( 'woocommerce_checkout_cart_item_visible', 'mbpf_hide_category_from_cart', 10, 3 );
add_filter( 'woocommerce_order_item_visible', 'mbpf_hide_category_from_order', 10, 2 );

function mbpf_hide_category_from_cart($visible, $cart_item, $cart_item_key) {
	$product = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key);
	$category_set = get_term_by( 'id', $product->category_ids[0], 'product_cat', 'ARRAY_A' );
	$category = $category_set['name'];
	$categories = get_option('category_option');
	foreach ($categories as $cat => $cat_value) {
		if ( strcmp($cat, $category ) == 0 ) {
			$visible = false;
		}
	}
	return $visible;
}

function mbpf_hide_category_from_order($visible, $order_item) {
	$product = $order_item->get_product();
	$category_set = get_term_by( 'id', $product->category_ids[0], 'product_cat', 'ARRAY_A' );
	$category = $category_set['name'];
	$categories = get_option('category_option');
	foreach ($categories as $cat => $cat_value) {
		if ( strcmp($cat, $category ) == 0 ) {
			$visible = false;
		}
	}
	return $visible;
}

// Helper functions
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
//	register_setting( string $option_group, string $option_name, array $args = array()  )
	register_setting( 'category-settings', 'category_option' );
//	add_settings_section( string $id, string $title, callable $callback, string $page  )
	add_settings_section( 'category-settings-options', 'Cart Options', 'category_settings_callback', 'filter-cart' );
//	add_settings_field( string $id, string $title, callable $callback, string $page, string $section = 'default', array $args = array() )<Paste>
	add_settings_field( 'category-option', 'Categories', 'category_selection_callback', 'filter-cart', 'category-settings-options' );
}

function category_settings_callback() {
	echo 'Check the categories to hide from cart';
}

function category_selection_callback() {
	$categories = get_all_categories();
	$selected_categories = get_option('category_option');
	$output = '';
	foreach ($categories as $category) {
		$checked = (@$selected_categories[$category->name] == 1 ? 'checked' : '');
		$category = $category->name;

		$output .= '<label><input type="checkbox" id="'.$category.'" name="category_option['.$category.']" value="1" '.$checked.' /> '.$category.'</label><br>';
	}
	echo $output;	
}



function cart_filter_admin_menu() {
	add_menu_page('Cart Filter', 'Filter Options', 'manage_options', 'filter-cart', 'create_menu_page', 'dashicons-cart', 4 );
}

function create_menu_page() {
	require_once( dirname(__FILE__) . '/card-filter-admin.php' );
}


