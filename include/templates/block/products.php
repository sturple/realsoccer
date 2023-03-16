<?php
$acf_fields = get_fields($post->ID);

$price = get_post_meta($post->ID, '_price' );
?>
<li class="<?php echo $data->class_name ; ?>">
    <h2><?php echo $data->title; ?></h2>
    <div style="font-size: 1.3rem"><strong><?php echo $data->price; ?></strong></div>
    <div> <?php echo sprintf('%s-%s', $data->start_date, $data->end_date ) ?></div>
    <div><?php  echo $data->age_terms; ?></div>
    <div><?php echo ( $data->content); ?></div>

    <a class="btn btn-register"  href="<?php the_permalink( $post->ID) ; ?>">Register</a>
</li> 