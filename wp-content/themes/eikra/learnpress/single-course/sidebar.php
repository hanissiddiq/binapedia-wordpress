<?php
/**
 * Template for displaying course sidebar.
 *
 * @author  ThimPress
 * @package LearnPress/Templates
 * @version 4.0.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * Hide sidebar if there is no content
 */
if ( ! is_active_sidebar( 'course-sidebar' ) && ! LP()->template( 'course' )->has_sidebar() ) {
	return;
}
?>
<aside class="course-summary-sidebar">
	<div class="sidebar-widget-area">
		<?php learn_press_get_template( 'custom/content-course-sidebar.php' ); ?>
	</div>
</aside>
