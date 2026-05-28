<?php
/**
 * Template Name: Profil
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

<!-- Sub Navigation -->
<nav class="bg-white border-b border-gray-100 shadow-sm sticky top-16 md:top-20 z-40">
    <div class="max-w-7xl mx-auto px-4">
        <div class="flex items-center gap-1 overflow-x-auto py-1 scrollbar-hide" id="psiProfileTabs">
            <button class="psi-profile-tab active px-5 py-3 text-sm font-medium text-red-600 border-b-2 border-red-600 whitespace-nowrap transition-colors" data-target="psiSejarah">Sejarah PSI</button>
            <button class="psi-profile-tab px-5 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-red-600 whitespace-nowrap transition-colors" data-target="psiVisiMisi">Visi & Misi</button>
            <button class="psi-profile-tab px-5 py-3 text-sm font-medium text-gray-500 border-b-2 border-transparent hover:text-red-600 whitespace-nowrap transition-colors" data-target="psiStruktur">Struktur Organisasi</button>
        </div>
    </div>
</nav>

<div class="py-10 md:py-16 bg-white min-h-screen">
    <div class="max-w-7xl mx-auto px-4">

        <!-- Sejarah -->
        <div class="psi-profile-panel" id="psiSejarah">
            <div class="max-w-4xl mx-auto prose prose-lg prose-gray psi-content">
                <?php
                $sejarah = get_posts( [ 'post_type' => 'page', 'meta_key' => '_wp_page_template', 'meta_value' => 'page-profile.php', 'posts_per_page' => 1 ] );
                if ( have_posts() ) :
                    while ( have_posts() ) : the_post();
                        $content = get_the_content();
                        if ( $content ) {
                            echo wp_kses_post( $content );
                        } else {
                            echo '<h2>Sejarah Partai Solidaritas Indonesia</h2>';
                            echo '<p>Partai Solidaritas Indonesia (PSI) didirikan pada tanggal 14 November 2014. PSI hadir sebagai partai politik yang berkomitmen memperjuangkan keadilan sosial, solidaritas, dan kesetaraan bagi seluruh rakyat Indonesia.</p>';
                            echo '<p>Di Provinsi Papua Pegunungan, DPW PSI hadir sebagai perpanjangan tangan partai untuk memastikan bahwa suara masyarakat Papua Pegunungan terdengar dan aspirasinya diperjuangkan secara nyata.</p>';
                            echo '<p>PSI Papua Pegunungan berkomitmen pada nilai-nilai kebersamaan, kemanusiaan, dan pembangunan yang berkeadilan untuk seluruh masyarakat di wilayah Papua Pegunungan.</p>';
                        }
                    endwhile;
                endif;
                ?>
            </div>
        </div>

        <!-- Visi Misi -->
        <div class="psi-profile-panel hidden" id="psiVisiMisi">
            <div class="max-w-4xl mx-auto">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 mb-10">
                    <div class="bg-gradient-to-br from-red-600 to-red-800 rounded-2xl p-8 text-white">
                        <div class="w-16 h-16 bg-white/20 rounded-xl flex items-center justify-center mb-5">
                            <i class="fas fa-eye text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="font-family:'Poppins',sans-serif;">Visi</h3>
                        <p class="text-red-100 leading-relaxed">Mewujudkan Papua Pegunungan yang sejahtera, adil, dan bermartabat melalui politik yang bersih, akuntabel, dan berorientasi pada kepentingan rakyat.</p>
                    </div>
                    <div class="bg-gradient-to-br from-gray-800 to-gray-900 rounded-2xl p-8 text-white">
                        <div class="w-16 h-16 bg-white/10 rounded-xl flex items-center justify-center mb-5">
                            <i class="fas fa-bullseye text-3xl"></i>
                        </div>
                        <h3 class="text-2xl font-bold mb-4" style="font-family:'Poppins',sans-serif;">Misi</h3>
                        <ul class="space-y-3 text-gray-300 text-sm">
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Memperjuangkan keadilan sosial bagi seluruh masyarakat Papua Pegunungan</li>
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Membangun pendidikan berkualitas yang merata di seluruh kabupaten</li>
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Mengembangkan ekonomi kerakyatan dan UMKM</li>
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Meningkatkan akses kesehatan dan layanan publik</li>
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Melestarikan budaya dan kearifan lokal Papua Pegunungan</li>
                            <li class="flex items-start gap-2"><i class="fas fa-check-circle text-green-400 mt-0.5 flex-shrink-0"></i>Membangun politik yang transparan dan akuntabel</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Struktur Organisasi -->
        <div class="psi-profile-panel hidden" id="psiStruktur">
            <div class="max-w-6xl mx-auto">
                <?php
                $leaders = get_posts( [
                    'post_type'      => 'psi_leadership',
                    'posts_per_page' => 50,
                    'orderby'        => 'meta_value_num',
                    'meta_key'       => '_psi_lead_order',
                    'order'          => 'ASC',
                ] );
                if ( $leaders ) :
                ?>
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php foreach ( $leaders as $leader ) :
                        $pos   = get_post_meta( $leader->ID, '_psi_lead_position', true );
                        $thumb = get_the_post_thumbnail_url( $leader->ID, 'psi-leader' ) ?: '';
                    ?>
                    <div class="bg-gray-50 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-500 hover:-translate-y-1 group">
                        <div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 relative overflow-hidden">
                            <?php if ( $thumb ) : ?>
                            <img src="<?php echo esc_url( $thumb ); ?>" alt="<?php echo esc_attr( $leader->post_title ); ?>" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-700" loading="lazy">
                            <?php else : ?>
                            <div class="w-full h-full flex items-center justify-center"><i class="fas fa-user-tie text-4xl text-gray-400"></i></div>
                            <?php endif; ?>
                        </div>
                        <div class="p-5 text-center">
                            <h3 class="font-bold text-gray-900 mb-1"><?php echo esc_html( $leader->post_title ); ?></h3>
                            <p class="text-red-600 text-sm font-semibold"><?php echo esc_html( $pos ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php else : ?>
                <!-- Default Structure -->
                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                    <?php
                    $default_structure = [
                        [ 'name' => 'Ketua Dewan Pembina', 'pos' => 'Dewan Pembina' ],
                        [ 'name' => 'Yotam Wonda, S.H., M.Si', 'pos' => 'Ketua DPW' ],
                        [ 'name' => 'Wakil Ketua', 'pos' => 'Wakil Ketua DPW' ],
                        [ 'name' => 'Yotias Kobak, S.Sos', 'pos' => 'Sekretaris' ],
                        [ 'name' => 'Wakil Sekretaris', 'pos' => 'Wakil Sekretaris' ],
                        [ 'name' => 'Almina Wakur, S.IP', 'pos' => 'Bendahara' ],
                        [ 'name' => 'Ketua Bidang I', 'pos' => 'Hubungan Antar Lembaga Hukum dan HAM' ],
                        [ 'name' => 'Ketua Bidang II', 'pos' => 'UMKM, Koperasi dan Kepariwisataan' ],
                        [ 'name' => 'Ketua Bidang III', 'pos' => 'Media, Teknologi dan Informatika' ],
                        [ 'name' => 'Ketua Bidang IV', 'pos' => 'Pemuda, Olahraga, Seni & Budaya' ],
                        [ 'name' => 'Ketua Bidang V', 'pos' => 'Buruh, Petani, Nelayan dan SDA' ],
                        [ 'name' => 'Ketua Bidang VI', 'pos' => 'Kesehatan dan Lingkungan Hidup' ],
                        [ 'name' => 'Ketua Bidang VII', 'pos' => 'Keagamaan' ],
                        [ 'name' => 'Ketua Bidang VIII', 'pos' => 'Perempuan dan Anak' ],
                    ];
                    foreach ( $default_structure as $item ) :
                    ?>
                    <div class="bg-gray-50 rounded-2xl overflow-hidden hover:shadow-xl transition-all duration-500 hover:-translate-y-1">
                        <div class="aspect-[4/5] bg-gradient-to-br from-gray-200 to-gray-300 flex items-center justify-center">
                            <i class="fas fa-user-tie text-4xl text-gray-400"></i>
                        </div>
                        <div class="p-5 text-center">
                            <h3 class="font-bold text-gray-900 mb-1 text-sm"><?php echo esc_html( $item['name'] ); ?></h3>
                            <p class="text-red-600 text-xs font-semibold"><?php echo esc_html( $item['pos'] ); ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <p class="text-center text-gray-400 text-sm mt-6">* Tambahkan data pimpinan melalui menu <strong>Pimpinan</strong> di admin panel untuk menampilkan foto dan detail lengkap.</p>
                <?php endif; ?>
            </div>
        </div>

    </div>
</div>
<?php get_footer(); ?>
