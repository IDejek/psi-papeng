<?php
/**
 * Single Video Template
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();

 $yt_url = get_post_meta( get_the_ID(), '_psi_video_youtube', true );
 $vm_url = get_post_meta( get_the_ID(), '_psi_video_vimeo', true );
 $embed  = $yt_url ?: $vm_url;
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <p class="text-gray-300 mt-2"><i class="far fa-calendar mr-1"></i> <?php echo get_the_date(); ?></p>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-gray-900 min-h-screen">
    <div class="max-w-5xl mx-auto px-4">
        <?php if ( $embed ) : ?>
        <div class="aspect-video rounded-2xl overflow-hidden shadow-2xl mb-8">
            <iframe src="<?php echo esc_url( $embed ); ?>" class="w-full h-full" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen title="<?php echo esc_attr( get_the_title() ); ?>"></iframe>
        </div>
        <?php endif; ?>
        <div class="text-white">
            <h2 class="text-2xl font-bold mb-4"><?php the_title(); ?></h2>
            <div class="prose prose-invert psi-content max-w-none">
                <?php the_content(); ?>
            </div>
            <?php psi_papeng_share_buttons(); ?>
        </div>
        <div class="mt-8">
            <a href="<?php echo esc_url( home_url( '/video' ) ); ?>" class="inline-flex items-center gap-2 px-5 py-2.5 bg-white/10 text-white border border-white/20 font-medium rounded-lg hover:bg-white/20 transition-all">
                <i class="fas fa-arrow-left text-sm"></i> Kembali ke Video
            </a>
        </div>
    </div>
</div>
<?php get_footer(); ?>
