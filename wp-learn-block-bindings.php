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
		'supports'     => array( 'title', 'editor', 'author', 'thumbnail', 'excerpt', 'custom-fields' ),
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
		'cover_image',
        array(
            'show_in_rest' => true,
            'single'       => true,
            'type'         => 'string',
            'label'        => __( 'Cover Image', 'wp-learn-block-bindings' ),
        )
    );
}

add_filter( 'postmeta_form_keys', 'bookstore_add_isbn_to_quick_edit', 10, 2 );

function bookstore_add_isbn_to_quick_edit( $keys, $post ) {
	if ( 'book' === $post->post_type ) {
		$keys[] = 'isbn';
	}
	return $keys;
}

add_action( 'init', 'wp_learn_register_book_image_source' );

function wp_learn_register_book_image_source() {
    register_block_bindings_source(
        'wp-learn/cover-image',
        array(
            'label'              => __( 'Cover Image', 'wp-learn-block-bindings' ),
            'get_value_callback' => 'wp_learn_get_cover_image',
        )
    );
}

function wp_learn_get_cover_image( $source_args, $block_instance ) {
    return 'https://upload.wikimedia.org/wikipedia/commons/a/a4/MeditationsMarcusAurelius1811.jpg';
}