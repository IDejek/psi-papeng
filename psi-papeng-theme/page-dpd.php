<?php
/**
 * Template Name: DPD PSI
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>
<div class="psi-page-header bg-gradient-to-r from-gray-900 to-red-900 py-12 md:py-16">
    <div class="max-w-7xl mx-auto px-4">
        <h1 class="text-3xl md:text-4xl font-black text-white" style="font-family:'Poppins',sans-serif;"><?php the_title(); ?></h1>
        <?php psi_papeng_breadcrumb(); ?>
    </div>
</div>
<div class="py-10 md:py-16 bg-gray-50 min-h-screen">
    <div class="max-w-7xl mx-auto px-4">
        <!-- Search -->
        <div class="mb-8 max-w-md">
            <div class="relative">
                <input type="text" id="psiDpdSearch" placeholder="Cari kabupaten..." class="w-full px-5 py-3 pl-12 bg-white border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm">
                <i class="fas fa-search absolute left-4 top-1/2 -translate-y-1/2 text-gray-400"></i>
            </div>
        </div>
        <?php
        $paged = get_query_var( 'paged' ) ?: 1;
        $dpds = new WP_Query( [
            'post_type'      => 'psi_dpd',
            'posts_per_page' => 12,
            'paged'          => $paged,
        ] );
        if ( $dpds->have_posts() ) :
        ?>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6" id="psiDpdGrid">
            <?php while ( $dpds->have_posts() ) : $dpds->the_post();
                $ketua   = get_post_meta( get_the_ID(), '_psi_dpd_ketua', true );
                $phone   = get_post_meta( get_the_ID(), '_psi_dpd_phone', true );
                $members = get_post_meta( get_the_ID(), '_psi_dpd_members', true );
                $thumb   = get_the_post_thumbnail_url( get_the_ID(), 'psi-dpd' ) ?: '';
            ?>
            <div class="psi-dpd-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group" data-name="<?php echo esc_attr( strtolower( get_the_title() ) ); ?>">
                <a href="<?php the_permalink(); ?>" class="block">
                    <div class="aspect-[4/5] bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                        <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( get_the_title() ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-building text-4xl text-gray-300"></i></div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-black/20 to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-5">
                            <h3 class="text-white font-bold text-lg leading-snug mb-1"><?php the_title(); ?></h3>
                            <?php if ( $ketua ) : ?>
                            <p class="text-gray-300 text-sm"><i class="fas fa-user-tie mr-1"></i> <?php echo esc_html( $ketua ); ?></p>
                            <?php endif; ?>
                            <?php if ( $members ) : ?>
                            <p class="text-gray-400 text-xs mt-1"><i class="fas fa-users mr-1"></i> <?php echo esc_html( $members ); ?> anggota</p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endwhile; wp_reset_postdata(); ?>
        </div>
        <?php psi_papeng_pagination(); ?>
        <?php else : ?>
        <div class="text-center py-20 text-gray-400">
            <i class="fas fa-map-marker-alt text-5xl mb-4"></i>
            <p class="text-lg">Belum ada data DPD.</p>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php get_footer(); ?>
