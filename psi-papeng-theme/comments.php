<?php
/**
 * Comments Template
 * @package PSI_Papeng
 */
defined( 'ABSPATH' ) || exit;

if ( post_password_required() ) return;

if ( have_comments() ) :
?>
<div id="comments" class="mt-10 pt-8 border-t border-gray-100">
    <h3 class="text-xl font-bold text-gray-900 mb-6">
        <?php
        $count = get_comments_number();
        printf( esc_html( _n( '%d Komentar', '%d Komentar', $count, 'psi-papeng' ) ), intval( $count ) );
        ?>
    </h3>
    <ol class="space-y-6">
        <?php
        wp_list_comments( [
            'style'       => 'ol',
            'short_ping'  => true,
            'avatar_size' => 48,
            'callback'    => 'psi_papeng_comment_callback',
        ] );
        ?>
    </ol>
    <?php the_comments_navigation(); ?>
</div>
<?php endif; ?>

<?php
if ( ! comments_open() && get_comments_number() && post_type_supports( get_post_type(), 'comments' ) ) :
?>
<p class="text-gray-400 text-sm mt-6"><?php esc_html_e( 'Komentar ditutup.', 'psi-papeng' ); ?></p>
<?php endif; ?>

<?php
comment_form( [
    'class_form'         => 'space-y-5 mt-8',
    'title_reply_before' => '<h3 id="reply-title" class="text-xl font-bold text-gray-900 mb-6">',
    'title_reply_after'  => '</h3>',
    'class_submit'       => 'px-6 py-3 bg-red-600 text-white font-semibold rounded-xl hover:bg-red-700 transition-all duration-300',
    'comment_field'      => '<div class="mb-5"><label for="comment" class="block text-sm font-medium text-gray-700 mb-1.5">Komentar <span class="text-red-500">*</span></label><textarea id="comment" name="comment" rows="5" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm resize-none"></textarea></div>',
    'fields'             => [
        'author' => '<div class="grid grid-cols-1 sm:grid-cols-2 gap-5"><div><label for="author" class="block text-sm font-medium text-gray-700 mb-1.5">Nama <span class="text-red-500">*</span></label><input type="text" id="author" name="author" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"></div>',
        'email'  => '<div><label for="email" class="block text-sm font-medium text-gray-700 mb-1.5">Email <span class="text-red-500">*</span></label><input type="email" id="email" name="email" required class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-transparent text-sm"></div></div>',
    ],
] );
?>

<?php
function psi_papeng_comment_callback( $comment, $args, $depth ) {
    $tag = ( 'div' === $args['style'] ) ? 'div' : 'li';
    ?>
    <<?php echo $tag; ?> <?php comment_class( empty( $args['has_children'] ) ? '' : 'parent' ); ?> id="comment-<?php comment_ID(); ?>">
    <div class="flex gap-4 p-5 bg-gray-50 rounded-xl">
        <div class="flex-shrink-0">
            <?php echo get_avatar( $comment, $args['avatar_size'] ); ?>
        </div>
        <div class="flex-1 min-w-0">
            <div class="flex items-center gap-3 mb-2">
                <span class="font-semibold text-gray-900 text-sm"><?php echo esc_html( get_comment_author() ); ?></span>
                <span class="text-xs text-gray-400"><?php echo esc_html( get_comment_date() ); ?></span>
            </div>
            <div class="text-sm text-gray-600 leading-relaxed"><?php comment_text(); ?></div>
            <div class="mt-2"><?php comment_reply_link( array_merge( $args, [ 'add_below' => 'comment', 'depth' => $depth, 'max_depth' => $args['max_depth'], 'before' => '', 'after' => '' ] ) ); ?></div>
        </div>
    </div>
    <?php
}
?>
