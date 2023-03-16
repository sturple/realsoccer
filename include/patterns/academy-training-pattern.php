<?php

namespace Bcgov\Theme\FoodDirectory;

ob_start();
?>


<!-- wp:paragraph -->
<p>Example pattern</p>
<!-- /wp:paragraph -->


<?php
$pattern = ob_get_contents();
ob_end_clean();
/**
 * A comfortably padded section in which to place elements.
 */
return [
	'title'      => __( 'Academy Training' ),
	'categories' => [ 'worldcupsoccercamp' ],
	'content'    => $pattern
];