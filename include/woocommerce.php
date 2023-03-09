<?php



add_filter( 'use_block_editor_for_post_type',  'realsoccer_use_block_editor_for_post_type', 15, 2 );


function realsoccer_use_block_editor_for_post_type( $can_edit, $post_type ) {
    return 'product' === $post_type ? true : $can_edit;
}