<?php
/**
 * Default Page Template
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-16 md:py-20">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-12 md:py-16 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="max-w-4xl mx-auto prose prose-lg prose-gray psi-content">
            <?php
            while ( have_posts() ) : the_post();
                the_content();
            endwhile;
            ?>
        </div>
    </div>
</div>
<?php get_footer(); ?>
