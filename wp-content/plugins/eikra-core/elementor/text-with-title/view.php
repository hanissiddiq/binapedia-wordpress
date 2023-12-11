<?php
/**
 * @author  RadiusTheme
 * @since   1.0
 * @version 2.0
 */

$attr = $btn = '';

if ( !empty( $data['btn_url']['url'] ) ) {
    $attr  .= 'href="' . $data['btn_url']['url'] . '"';
    $attr .= !empty( $data['btn_url']['is_external'] ) ? ' target="_blank"' : '';
    $attr .= !empty( $data['btn_url']['nofollow'] ) ? ' rel="nofollow"' : '';
}

if ( !empty( $data['btn_text'] ) ) {
    $btn .= '<a ' . $attr . '>' . $data['btn_text'] . '</a>';
}

?>
<div class="rt-vc-text-title <?php echo esc_attr( $data['style'] ); ?>">
    <?php if( !empty( $data['title'] ) ): ?>
        <h2 class="rtin-title"><?php echo esc_html( $data['title'] ); ?></h2>
    <?php endif;?>
    <?php if( !empty( $data['content'] ) ): ?>
        <div class="rtin-content">
            <?php echo wp_kses_post( $data['content'] ); ?>
        </div>
    <?php endif;?>
    <?php if( $data['btn_display'] == 'yes' ): ?>
        <div class="rtin-btn">
            <?php echo wp_kses_post( $btn ); ?>
        </div>
    <?php endif;?>
</div>