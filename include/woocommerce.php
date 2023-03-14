<?php


add_filter( 'use_block_editor_for_post_type',  'realsoccer_use_block_editor_for_post_type', 15, 2 );
add_action( 'woocommerce_after_register_taxonomy',  'realsoccer_product_register_taxonomies' );
add_filter('manage_product_posts_columns', 'realsoccer_manage_product_posts_columns', 15 );

// Removing Product Tags, as these are not being used, and only add additional noise.
add_filter( 'woocommerce_taxonomy_objects_product_tag', '__return_false' );



/**
 * This overrides woocommerce hook, which turns off block editing in the Product.
 *
 * @param bool $can_edit  If the user can edit this post type.
 * @param string $post_type  The post type.
 * @return boolean
 */
function realsoccer_use_block_editor_for_post_type( $can_edit, $post_type ) {
    return 'product' === $post_type ? true : $can_edit;
}

/**
 * WordPress hook to update columns to display for the product admin list.
 *
 * @param array $columns  The columns to use for the product admin list.
 * @return array
 */
function realsoccer_manage_product_posts_columns( array $columns ): array {
    unset( $columns['sku'] );
    unset( $columns['product_tag'] );
    unset( $columns['featured'] );
    unset( $columns['date'] );
    return $columns;
}

/**
 * Registers additional taxonomies for the Product, including gender, age group.
 *
 * @return void
 */
function realsoccer_product_register_taxonomies() {
    register_taxonomy(
        'product_gender',
        apply_filters( 'woocommerce_taxonomy_objects_product_gender', array( 'product' ) ),
        array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'query_var'         => true,
            'public'            => false,
            'show_tagcloud'     => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'label'             => _x( 'Gender', 'Taxonomy name', 'woocommerce' ),
        )
    );
    register_taxonomy(
        'product_age',
        apply_filters( 'woocommerce_taxonomy_objects_product_age', array( 'product' ) ),
        array(
            'hierarchical'      => true,
            'show_ui'           => true,
            'show_in_nav_menus' => false,
            'query_var'         => true,
            'public'            => true,
            'show_tagcloud'     => false,
            'show_admin_column' => true,
            'show_in_rest'      => true,
            'label'             => _x( 'Age Group', 'Taxonomy name', 'woocommerce' ),
        )
    );
}

