<?php
/**
 * Front Page / Homepage
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;
get_header();
?>

<!-- ═══ HERO SLIDER ═══ -->
<section class="psi-hero relative overflow-hidden" aria-label="Hero Slider">
    <div class="psi-slider-wrapper relative" id="psiHeroSlider">
        <?php
        $slides = get_posts( [
            'post_type'      => 'psi_slider',
            'posts_per_page' => 10,
            'orderby'        => 'meta_value_num',
            'meta_key'       => '_psi_slider_order',
            'order'          => 'ASC',
        ] );
        if ( $slides ) :
            $first = true;
            foreach ( $slides as $slide ) :
                $thumb = get_the_post_thumbnail_url( $slide->ID, 'psi-hero' ) ?: PSI_PAPENG_URI . '/assets/img/hero-default.jpg';
                $sub   = get_post_meta( $slide->ID, '_psi_slider_subtitle', true );
                $btn1  = get_post_meta( $slide->ID, '_psi_slider_btn_text', true );
                $url1  = get_post_meta( $slide->ID, '_psi_slider_btn_url', true );
                $btn2  = get_post_meta( $slide->ID, '_psi_slider_btn_text2', true );
                $url2  = get_post_meta( $slide->ID, '_psi_slider_btn_url2', true );
        ?>
        <div class="psi-slide absolute inset-0 transition-opacity duration-700 ease-in-out <?php echo $first ? 'opacity-100 z-10' : 'opacity-0 z-0'; ?>">
            <div class="absolute inset-0 bg-cover bg-center" style="background-image:url('<?php echo esc_url( $thumb ); ?>');">
                <div class="absolute inset-0 bg-gradient-to-r from-black/80 via-black/50 to-transparent"></div>
                <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent"></div>
            </div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 flex items-center min-h-[500px] md:min-h-[600px] lg:min-h-[700px]">
                <div class="max-w-2xl py-16 md:py-20">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-4" style="font-family:'Poppins',sans-serif;">
                        <?php echo esc_html( $slide->post_title ); ?>
                    </h1>
                    <?php if ( $sub ) : ?>
                    <p class="text-base md:text-lg text-gray-300 mb-8 leading-relaxed"><?php echo esc_html( $sub ); ?></p>
                    <?php endif; ?>
                    <div class="flex flex-wrap gap-3">
                        <?php if ( $btn1 && $url1 ) : ?>
                        <a href="<?php echo esc_url( $url1 ); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg hover:shadow-xl hover:-translate-y-0.5">
                            <?php echo esc_html( $btn1 ); ?> <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <?php endif; ?>
                        <?php if ( $btn2 && $url2 ) : ?>
                        <a href="<?php echo esc_url( $url2 ); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-white/10 backdrop-blur-sm border border-white/30 text-white font-semibold rounded-lg hover:bg-white/20 transition-all duration-300">
                            <?php echo esc_html( $btn2 ); ?>
                        </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <?php $first = false; endforeach; ?>
        <?php else : ?>
        <!-- Default Slide -->
        <div class="psi-slide absolute inset-0 opacity-100 z-10">
            <div class="absolute inset-0 bg-gradient-to-br from-gray-900 via-red-900 to-gray-900"></div>
            <div class="relative z-10 max-w-7xl mx-auto px-4 flex items-center min-h-[500px] md:min-h-[600px] lg:min-h-[700px]">
                <div class="max-w-2xl py-16 md:py-20">
                    <h1 class="text-3xl md:text-5xl lg:text-6xl font-black text-white leading-tight mb-4" style="font-family:'Poppins',sans-serif;">
                        DPW PSI Papua Pegunungan
                    </h1>
                    <p class="text-base md:text-lg text-gray-300 mb-8 leading-relaxed">Partai Solidaritas Indonesia — Membangun Papua Pegunungan yang Sejahtera, Adil, dan Bermartabat.</p>
                    <div class="flex flex-wrap gap-3">
                        <a href="<?php echo esc_url( get_theme_mod( 'psi_member_url', 'https://psi.id/menjadi-anggota' ) ); ?>" class="inline-flex items-center gap-2 px-7 py-3.5 bg-red-600 text-white font-semibold rounded-lg hover:bg-red-700 transition-all duration-300 shadow-lg">
                            Daftar Anggota <i class="fas fa-arrow-right text-sm"></i>
                        </a>
                        <a href="#psiWelcome" class="inline-flex items-center gap-2 px-7 py-3.5 bg-white/10 backdrop-blur-sm border border-white/30 text-white font-semibold rounded-lg hover:bg-white/20 transition-all duration-300">
                            Pelajari Lebih Lanjut
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <!-- Slider Dots -->
    <?php if ( count( $slides ) > 1 ) : ?>
    <div class="absolute bottom-6 left-1/2 transform -translate-x-1/2 z-20 flex gap-2" id="psiSliderDots"></div>
    <?php endif; ?>
    <!-- Slider Arrows -->
    <?php if ( count( $slides ) > 1 ) : ?>
    <button class="absolute left-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-all duration-300" id="psiSliderPrev" aria-label="Slide Sebelumnya"><i class="fas fa-chevron-left"></i></button>
    <button class="absolute right-4 top-1/2 -translate-y-1/2 z-20 w-12 h-12 bg-white/10 backdrop-blur-sm rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-all duration-300" id="psiSliderNext" aria-label="Slide Berikutnya"><i class="fas fa-chevron-right"></i></button>
    <?php endif; ?>
</section>

<!-- ═══ WELCOME / SAMBUTAN ═══ -->
<section class="py-16 md:py-24 bg-white" id="psiWelcome">
    <div class="max-w-7xl mx-auto px-4">
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-12 lg:gap-16 items-center">
            <div class="psi-animate fade-up">
                <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">Sambutan Ketua DPW</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900 mb-6 leading-tight" style="font-family:'Poppins',sans-serif;">
                    Selamat Datang di<br><span class="text-red-600">PSI Papua Pegunungan</span>
                </h2>
                <div class="prose prose-lg text-gray-600 leading-relaxed">
                    <?php
                    $welcome_text = get_theme_mod( 'psi_welcome_text', '' );
                    if ( $welcome_text ) {
                        echo wp_kses_post( $welcome_text );
                    } else {
                        echo '<p>Partai Solidaritas Indonesia hadir di Papua Pegunungan dengan semangat membangun masyarakat yang lebih baik. Kami berkomitmen untuk membawa perubahan nyata melalui politik yang bersih, akuntabel, dan berorientasi pada rakyat.</p><p>Bergabunglah bersama kami dalam perjuangan menuju Papua Pegunungan yang sejahtera, adil, dan bermartabat.</p>';
                    }
                    ?>
                </div>
            </div>
            <div class="psi-animate fade-up" style="animation-delay:0.2s;">
                <?php
                $welcome_img = get_theme_mod( 'psi_welcome_image', '' );
                if ( $welcome_img ) :
                ?>
                <div class="relative">
                    <img src="<?php echo esc_url( $welcome_img ); ?>" alt="Ketua DPW PSI Papua Pegunungan" class="w-full max-w-md mx-auto rounded-2xl shadow-2xl" loading="lazy">
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-red-600/10 rounded-2xl -z-10"></div>
                </div>
                <?php else : ?>
                <div class="relative w-full max-w-md mx-auto">
                    <div class="aspect-[4/5] bg-gradient-to-br from-red-600 to-red-800 rounded-2xl shadow-2xl flex items-center justify-center">
                        <div class="text-center text-white p-8">
                            <i class="fas fa-user-tie text-6xl mb-4 opacity-50"></i>
                            <p class="text-sm opacity-70">Upload foto melalui<br>Customizer → Sambutan Ketua</p>
                        </div>
                    </div>
                    <div class="absolute -bottom-4 -right-4 w-full h-full bg-red-600/10 rounded-2xl -z-10"></div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</section>

<!-- ═══ LEADERSHIP ═══ -->
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 psi-animate fade-up">
            <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">Pimpinan</span>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900" style="font-family:'Poppins',sans-serif;">Pimpinan DPW</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            /* FIX: Use meta_query for featured filter, meta_key for ordering only */
            $leaders = get_posts( [
                'post_type'      => 'psi_leadership',
                'posts_per_page' => 3,
                'meta_key'       => '_psi_lead_order',
                'orderby'        => 'meta_value_num',
                'order'          => 'ASC',
                'meta_query'     => [
                    [
                        'key'   => '_psi_lead_featured',
                        'value' => '1',
                    ],
                ],
            ] );
            if ( ! $leaders ) {
                $default_leaders = [
                    [ 'name' => 'Yotam Wonda, S.H., M.Si', 'pos' => 'Ketua DPW' ],
                    [ 'name' => 'Yotias Kobak, S.Sos', 'pos' => 'Sekretaris' ],
                    [ 'name' => 'Almina Wakur, S.IP', 'pos' => 'Bendahara' ],
                ];
                foreach ( $default_leaders as $i => $dl ) :
            ?>
            <div class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( $i * 0.15 ); ?>s;">
                <div class="psi-leader-card bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group">
                    <div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <i class="fas fa-user-tie text-5xl text-gray-400"></i>
                        </div>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 mb-1"><?php echo esc_html( $dl['name'] ); ?></h3>
                        <p class="text-red-600 text-sm font-semibold"><?php echo esc_html( $dl['pos'] ); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php } else { foreach ( $leaders as $i => $leader ) :
                $pos   = get_post_meta( $leader->ID, '_psi_lead_position', true );
                $thumb = get_the_post_thumbnail_url( $leader->ID, 'psi-leader' ) ?: '';
            ?>
            <div class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( $i * 0.15 ); ?>s;">
                <div class="psi-leader-card bg-white rounded-2xl overflow-hidden shadow-lg hover:shadow-2xl transition-all duration-500 hover:-translate-y-2 group cursor-pointer" data-leader-id="<?php echo esc_attr( $leader->ID ); ?>">
                    <div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden">
                        <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $leader->post_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user-tie text-5xl text-gray-400"></i></div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                    </div>
                    <div class="p-6 text-center">
                        <h3 class="text-lg font-bold text-gray-900 mb-1"><?php echo esc_html( $leader->post_title ); ?></h3>
                        <p class="text-red-600 text-sm font-semibold"><?php echo esc_html( $pos ); ?></p>
                    </div>
                </div>
            </div>
            <?php } } ?>
        </div>
        <div class="text-center mt-10 psi-animate fade-up">
            <a href="<?php echo esc_url( home_url( '/?page_id=' . psi_get_page_id( 'struktur-organisasi' ) ) ); ?>" class="inline-flex items-center gap-2 px-6 py-3 border-2 border-red-600 text-red-600 font-semibold rounded-lg hover:bg-red-600 hover:text-white transition-all duration-300">
                Lihat Struktur Lengkap <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
    </div>
</section>

<!-- ═══ ORGANIZATIONAL DIVISIONS ═══ -->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="text-center mb-12 psi-animate fade-up">
            <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">Organisasi</span>
            <h2 class="text-3xl md:text-4xl font-black text-gray-900" style="font-family:'Poppins',sans-serif;">Bidang Organisasi</h2>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
            <?php
            $divisions = get_posts( [
                'post_type'      => 'psi_division',
                'posts_per_page' => 8,
                'orderby'        => 'meta_value_num',
                'meta_key'       => '_psi_div_order',
                'order'          => 'ASC',
            ] );
            $default_divs = [
                'Hubungan Antar Lembaga Hukum dan HAM', 'UMKM, Koperasi dan Kepariwisataan',
                'Media, Teknologi dan Informatika', 'Pemuda, Olahraga, Seni & Budaya',
                'Buruh, Petani, Nelayan dan SDA', 'Kesehatan dan Lingkungan Hidup',
                'Keagamaan', 'Perempuan dan Anak',
            ];
            $items = $divisions ?: [];
            $icon_classes = ['fa-gavel','fa-store','fa-laptop-code','fa-futbol','fa-leaf','fa-heartbeat','fa-pray','fa-female'];
            $idx = 0;
            if ( empty( $items ) ) {
                foreach ( $default_divs as $dd ) {
                    $items[] = (object) [
                        'post_title' => $dd,
                        'ID'         => 0,
                        'post_excerpt' => '',
                    ];
                }
            }
            foreach ( $items as $item ) :
                $icon = $icon_classes[ $idx % count( $icon_classes ) ] ?? 'fa-cubes';
                $thumb = $item->ID ? get_the_post_thumbnail_url( $item->ID, 'psi-thumb' ) : '';
                $head  = $item->ID ? get_post_meta( $item->ID, '_psi_div_head', true ) : '';
                $idx++;
            ?>
            <div class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( ( $idx - 1 ) * 0.08 ); ?>s;">
                <div class="psi-division-card bg-gray-50 rounded-2xl p-6 hover:bg-white hover:shadow-xl transition-all duration-500 hover:-translate-y-1 border border-gray-100 group h-full flex flex-col">
                    <div class="w-14 h-14 bg-red-600/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-red-600 transition-colors duration-500">
                        <i class="fas <?php echo esc_attr( $icon ); ?> text-xl text-red-600 group-hover:text-white transition-colors duration-500"></i>
                    </div>
                    <h3 class="font-bold text-gray-900 mb-2 text-sm leading-snug"><?php echo esc_html( $item->post_title ); ?></h3>
                    <?php if ( $head ) : ?>
                    <p class="text-xs text-gray-500 mt-auto pt-2 border-t border-gray-100"><i class="fas fa-user text-red-500 mr-1"></i> <?php echo esc_html( $head ); ?></p>
                    <?php endif; ?>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- ═══ NEWS HIGHLIGHT ═══ -->
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-4 psi-animate fade-up">
            <div>
                <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">Berita</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900" style="font-family:'Poppins',sans-serif;">Berita Terkini</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/berita' ) ); ?>" class="text-red-600 font-semibold hover:text-red-700 transition-colors flex items-center gap-1">
                Lihat Semua <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $news = new WP_Query( [
                'post_type'      => 'post',
                'posts_per_page' => 6,
                'ignore_sticky_posts' => true,
            ] );
            if ( $news->have_posts() ) :
                $ni = 0;
                while ( $news->have_posts() ) : $news->the_post();
                    $ni++;
            ?>
            <article class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( $ni * 0.1 ); ?>s;">
                <div class="bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group h-full flex flex-col">
                    <a href="<?php the_permalink(); ?>" class="block aspect-video bg-gray-200 overflow-hidden relative">
                        <?php if ( has_post_thumbnail() ) : ?>
                        <img src="<?php the_post_thumbnail_url( 'psi-thumb' ); ?>" alt="<?php the_title_attribute(); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center bg-gradient-to-br from-gray-100 to-gray-200">
                            <i class="fas fa-newspaper text-3xl text-gray-300"></i>
                        </div>
                        <?php endif; ?>
                    </a>
                    <div class="p-5 flex flex-col flex-1">
                        <?php
                        $cats = get_the_category();
                        if ( $cats ) :
                        ?>
                        <span class="inline-block text-xs font-bold text-red-600 uppercase tracking-wide mb-2"><?php echo esc_html( $cats[0]->name ); ?></span>
                        <?php endif; ?>
                        <h3 class="font-bold text-gray-900 mb-2 leading-snug line-clamp-2">
                            <a href="<?php the_permalink(); ?>" class="hover:text-red-600 transition-colors"><?php the_title(); ?></a>
                        </h3>
                        <p class="text-sm text-gray-500 mb-4 flex-1 line-clamp-2"><?php echo esc_html( get_the_excerpt() ); ?></p>
                        <div class="flex items-center justify-between text-xs text-gray-400">
                            <span><i class="far fa-calendar mr-1"></i> <?php echo get_the_date(); ?></span>
                            <a href="<?php the_permalink(); ?>" class="text-red-600 font-semibold hover:text-red-700">Baca <i class="fas fa-arrow-right text-xs ml-1"></i></a>
                        </div>
                    </div>
                </div>
            </article>
            <?php endwhile; wp_reset_postdata(); ?>
            <?php else : ?>
            <div class="col-span-full text-center py-12 text-gray-400">
                <i class="fas fa-newspaper text-4xl mb-3"></i>
                <p>Belum ada berita. Tambahkan berita melalui Posts di admin panel.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ VIDEO ACTIVITIES ═══ -->
<section class="py-16 md:py-24 bg-white">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-4 psi-animate fade-up">
            <div>
                <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">Video</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900" style="font-family:'Poppins',sans-serif;">Kegiatan Video</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/video' ) ); ?>" class="text-red-600 font-semibold hover:text-red-700 transition-colors flex items-center gap-1">
                Semua Video <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php
            $videos = get_posts( [
                'post_type'      => 'psi_video',
                'posts_per_page' => 3,
            ] );
            if ( $videos ) :
                $vi = 0;
                foreach ( $videos as $vid ) :
                    $vi++;
                    $yt_url  = get_post_meta( $vid->ID, '_psi_video_youtube', true );
                    $thumb   = get_the_post_thumbnail_url( $vid->ID, 'psi-thumb' ) ?: '';
                    $yt_id   = '';
                    if ( $yt_url && preg_match( '/embed\/([a-zA-Z0-9_-]+)/', $yt_url, $m ) ) {
                        $yt_id = $m[1];
                    }
                    $thumb_src = $thumb ?: ( $yt_id ? 'https://img.youtube.com/vi/' . $yt_id . '/hqdefault.jpg' : '' );
            ?>
            <div class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( $vi * 0.1 ); ?>s;">
                <div class="psi-video-card bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group">
                    <div class="aspect-video bg-gray-900 relative overflow-hidden cursor-pointer" data-video-url="<?php echo esc_url( $yt_url ); ?>">
                        <?php if ( $thumb_src ) : ?>
                        <img src="<?php echo esc_url( $thumb_src ); ?>" alt="<?php echo esc_attr( $vid->post_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-black/30 flex items-center justify-center group-hover:bg-black/40 transition-colors">
                            <div class="w-16 h-16 bg-red-600 rounded-full flex items-center justify-center shadow-xl group-hover:scale-110 transition-transform duration-300">
                                <i class="fas fa-play text-white text-xl ml-1"></i>
                            </div>
                        </div>
                    </div>
                    <div class="p-5">
                        <h3 class="font-bold text-gray-900 mb-1 leading-snug line-clamp-2"><?php echo esc_html( $vid->post_title ); ?></h3>
                        <p class="text-xs text-gray-400"><i class="far fa-calendar mr-1"></i> <?php echo get_the_date( '', $vid->ID ); ?></p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="col-span-full text-center py-12 text-gray-400">
                <i class="fas fa-video text-4xl mb-3"></i>
                <p>Belum ada video. Tambahkan melalui menu Video Kegiatan di admin panel.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ DPD SECTION ═══ -->
<section class="py-16 md:py-24 bg-gray-50">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-12 gap-4 psi-animate fade-up">
            <div>
                <span class="inline-block px-4 py-1.5 bg-red-50 text-red-600 text-xs font-bold uppercase tracking-widest rounded-full mb-4">DPD</span>
                <h2 class="text-3xl md:text-4xl font-black text-gray-900" style="font-family:'Poppins',sans-serif;">DPD PSI Se-Papua Pegunungan</h2>
            </div>
            <a href="<?php echo esc_url( home_url( '/dpd-psi' ) ); ?>" class="text-red-600 font-semibold hover:text-red-700 transition-colors flex items-center gap-1">
                Lihat Semua DPD <i class="fas fa-arrow-right text-sm"></i>
            </a>
        </div>
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php
            $dpds = get_posts( [
                'post_type'      => 'psi_dpd',
                'posts_per_page' => 8,
            ] );
            if ( $dpds ) :
                $di = 0;
                foreach ( $dpds as $dpd ) :
                    $di++;
                    $ketua = get_post_meta( $dpd->ID, '_psi_dpd_ketua', true );
                    $thumb = get_the_post_thumbnail_url( $dpd->ID, 'psi-dpd' ) ?: '';
            ?>
            <div class="psi-animate fade-up" style="animation-delay:<?php echo esc_attr( $di * 0.08 ); ?>s;">
                <a href="<?php echo esc_url( get_permalink( $dpd->ID ) ); ?>" class="block bg-white rounded-2xl overflow-hidden shadow-md hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group">
                    <div class="aspect-[4/5] bg-gradient-to-br from-gray-100 to-gray-200 relative overflow-hidden">
                        <?php if ( $thumb ) : ?>
                        <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $dpd->post_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                        <?php else : ?>
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-building text-4xl text-gray-300"></i>
                        </div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/70 via-transparent to-transparent"></div>
                        <div class="absolute bottom-0 left-0 right-0 p-4">
                            <h3 class="text-white font-bold text-sm leading-snug"><?php echo esc_html( $dpd->post_title ); ?></h3>
                            <?php if ( $ketua ) : ?>
                            <p class="text-gray-300 text-xs mt-1"><i class="fas fa-user-tie mr-1"></i> <?php echo esc_html( $ketua ); ?></p>
                            <?php endif; ?>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
            <?php else : ?>
            <div class="col-span-full text-center py-12 text-gray-400">
                <i class="fas fa-map-marker-alt text-4xl mb-3"></i>
                <p>Belum ada data DPD. Tambahkan melalui menu Data DPD di admin panel.</p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</section>

<!-- ═══ MEMBERSHIP CTA ═══ -->
<section class="py-16 md:py-24 relative overflow-hidden">
    <div class="absolute inset-0 bg-gradient-to-br from-red-700 via-red-600 to-red-800"></div>
    <div class="absolute inset-0 opacity-10" style="background-image:url('data:image/svg+xml,%3Csvg width=\'60\' height=\'60\' viewBox=\'0 0 60 60\' xmlns=\'http://www.w3.org/2000/svg\'%3E%3Cg fill=\'none\' fill-rule=\'evenodd\'%3E%3Cg fill=\'%23ffffff\' fill-opacity=\'0.4\'%3E%3Cpath d=\'M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z\'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E');"></div>
    <div class="relative z-10 max-w-4xl mx-auto px-4 text-center psi-animate fade-up">
        <h2 class="text-3xl md:text-5xl font-black text-white mb-6" style="font-family:'Poppins',sans-serif;">Bergabunglah Bersama Kami</h2>
        <p class="text-lg text-red-100 mb-8 max-w-2xl mx-auto leading-relaxed">Jadilah bagian dari perubahan nyata untuk Papua Pegunungan. Daftarkan diri Anda sebagai anggota Partai Solidaritas Indonesia.</p>
        <a href="<?php echo esc_url( get_theme_mod( 'psi_member_url', 'https://psi.id/menjadi-anggota' ) ); ?>" class="inline-flex items-center gap-3 px-10 py-4 bg-white text-red-600 font-bold text-lg rounded-xl hover:bg-gray-100 transition-all duration-300 shadow-2xl hover:shadow-3xl hover:-translate-y-1">
            <i class="fas fa-user-plus"></i> Daftar Anggota
        </a>
    </div>
</section>

<!-- ═══ VIDEO MODAL ═══ -->
<div class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/90 p-4" id="psiVideoModal">
    <button class="absolute top-4 right-4 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xl" id="psiVideoModalClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <div class="w-full max-w-4xl aspect-video">
        <iframe id="psiVideoIframe" class="w-full h-full rounded-xl" src="" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
    </div>
</div>

<!-- ═══ LEADER MODAL ═══ -->
<div class="fixed inset-0 z-[9999] hidden items-center justify-center bg-black/80 p-4" id="psiLeaderModal">
    <button class="absolute top-4 right-4 w-12 h-12 bg-white/10 rounded-full flex items-center justify-center text-white hover:bg-white/20 transition-colors text-xl" id="psiLeaderModalClose" aria-label="Tutup"><i class="fas fa-times"></i></button>
    <div class="bg-white rounded-2xl max-w-lg w-full max-h-[90vh] overflow-y-auto shadow-2xl" id="psiLeaderModalContent"></div>
</div>

<?php get_footer(); ?>
