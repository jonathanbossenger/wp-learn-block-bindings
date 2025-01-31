<?php
/**
 * Plugin Name: WP Learn Block Bindings
 * Description: A plugin to demonstrate adding block bindings WordPress.
 * Version: 1.0.0
 * License: GPL2
 *
 * @package WP_Learn_Block_Bindings
 */

add_action( 'init', 'wp_learn_register_book_post_type' );
/**
 * Register the book post type and its meta fields.
 *
 * @return void
 */
function wp_learn_register_book_post_type() {
	$args = array(
		'labels'       => array(
			'name'          => 'Books',
			'singular_name' => 'Book',
			'menu_name'     => 'Books',
			'add_new'       => 'Add New Book',
			'add_new_item'  => 'Add New Book',
			'new_item'      => 'New Book',
			'edit_item'     => 'Edit Book',
			'view_item'     => 'View Book',
			'all_items'     => 'All Books',
		),
		'public'       => true,
		'has_archive'  => true,
		'show_in_rest' => true,
		'rest_base'    => 'books',
		'supports'     => array( 'title', 'editor', 'thumbnail', 'excerpt', 'custom-fields' ),
	);

	register_post_type( 'book', $args );

	register_post_meta(
		'book',
		'isbn',
		array(
			'single'       => true,
			'type'         => 'string',
			'show_in_rest' => true,
			'label'        => __( 'Book ISBN', 'wp-learn-block-bindings' ),
		)
	);

	register_post_meta(
		'book',
		'book_image',
		array(
			'show_in_rest' => true,
			'single'       => true,
			'type'         => 'string',
			'label'        => __( 'Book Image', 'wp-learn-block-bindings' ),
		)
	);

	register_post_meta(
		'book',
		'author',
		array(
			'single'       => true,
			'type'         => 'string',
			'show_in_rest' => true,
			'label'        => __( 'Book Author', 'wp-learn-block-bindings' ),
		)
	);
}

add_filter( 'postmeta_form_keys', 'wp_learn_add_meta_to_quick_edit', 10, 2 );
/**
 * Adds our meta keys to the Custom Fields panel when adding/editing a book
 *
 * @param array  $keys The array of meta keys.
 * @param object $post The post object.
 *
 * @return array The updated array of meta keys.
 */
function wp_learn_add_meta_to_quick_edit( $keys, $post ) {
	if ( 'book' === $post->post_type ) {
		if ( ! is_array( $keys ) ) {
			$keys = array();
		}
		$keys_to_add = array( 'isbn', 'author', 'book_image' );
		foreach ( $keys_to_add as $key ) {
			if ( ! in_array( $key, $keys, true ) ) {
				$keys[] = $key;
			}
		}
	}
	return $keys;
}

add_action( 'init', 'wp_learn_register_book_image_source' );
/**
 * Register the custom block binding source for the book image.
 *
 * @return void
 */
function wp_learn_register_book_image_source() {
	register_block_bindings_source(
		'wp-learn/book-image',
		array(
			'label'              => __( 'Book Image', 'wp-learn-block-bindings' ),
			'get_value_callback' => 'wp_learn_get_book_image',
			'uses_context'       => array( 'postId' ),
		)
	);
}

/**
 * Fetch the custom book image for the custom block binding source
 *
 * @param array  $source_args The source arguments.
 * @param object $block_instance The block instance.
 *
 * @return string The book image URL.
 */
function wp_learn_get_book_image( $source_args, $block_instance ) {
	$post_id = $block_instance->context['postId'];
	$isbn    = get_post_meta( $post_id, 'isbn', true );
	return "https://covers.openlibrary.org/b/ISBN/{$isbn}-L.jpg";
}
