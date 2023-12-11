<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined('ABSPATH' ) ) exit;

class Course_Isotope extends Custom_Widget_Base {

    public function __construct( $data = [], $args = null ) {
        $this->rt_name  = __( 'Course Isotope', 'eikra-core' );
        $this->rt_base  = 'rt-course-isotope';
        parent::__construct( $data, $args );
    }

    private function rt_load_scripts(){
        wp_enqueue_style( 'course-review' );
        wp_enqueue_style( 'dashicons' );
        wp_enqueue_script( 'isotope-pkgd' );
    }

    public function render_query( $query, $clsss, $style ){
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                echo '<div class="col-lg-3 col-md-4 col-sm-6 col-xs-12 ' . $clsss . '">';
                if ( RDTheme_Helper::is_LMS() ) {
                    if ( $style != 1 ) {
                        learn_press_get_template( "custom/course-box-{$style}.php" );
                    }
                    else {
                        learn_press_get_template( 'custom/course-box.php' );
                    }
                }
                else {
                    get_template_part( 'template-parts/content', 'course-box' );
                }
                echo '</div>';
            }
        }
        wp_reset_query();
    }

    public function rt_fields() {

        $fields = array(
            array(
                'mode'  => 'section_start',
                'id'    => 'section_general',
                'label' => __( 'General', 'eikra-core' )
            ),
            array(
                'id'    => 'style',
                'label' => __( 'Navigation Style', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => array(
                    'style1'   => __( 'Style 1 (Category Navigation)', 'eikra-core' ),
                    'style2'   => __( 'Style 2', 'eikra-core'),
                ),
                'default'   => 'style1',
            ),
            array(
                'id'    => 'box_style',
                'label' => __( 'Style', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => array(
                    '1'   => __( 'Style 1', 'eikra-core' ),
                    '2'   => __( 'Style 2', 'eikra-core'),
                    '3'   => __( 'Style 3', 'eikra-core'),
                ),
                'default'   => '1',
            ),
            array(
                'type'        => Controls_Manager::SWITCHER,
                'id'          => 'button_display',
                'label'       => esc_html__( 'Button Display', 'eikra-core' ),
                'label_on'    => esc_html__( 'On', 'eikra-core' ),
                'label_off'   => esc_html__( 'Off', 'eikra-core' ),
                'default'     => 'yes',
                'condition'   => array( 'style' => array( 'style2' ) ),
            ),
            array(
                'mode'  => 'section_end'
            ),

            // Style Tab

            array(
                'mode'    => 'section_start',
                'id'      => 'sec_general_style',
                'tab'     => Controls_Manager::TAB_STYLE,
                'label'   => __( 'General', 'eikra-core' ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'title_typo',
                'label'   => esc_html__( 'Title Style', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-content .rtin-title',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'content_typo',
                'label'   => esc_html__( 'Content Style', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-content .rtin-description',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array( 'style' => array('1', '2', '3') ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'price_typo',
                'label'   => esc_html__( 'Price Style', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-thumbnail .rtin-price',
                    '{{WRAPPER}} .rtin-meta .rtin-price ins',
                ),
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'author_typo',
                'label'   => esc_html__( 'Author Style', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-author',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array(
                'mode' => 'section_end',
            ),

            array(
                'mode'    => 'section_start',
                'id'      => 'sec_style',
                'tab'     => Controls_Manager::TAB_STYLE,
                'label'   => __( 'Color', 'eikra-core' ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'title_color',
                'label'   => __( 'Title Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'title_hover_color',
                'label'   => __( 'Title Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a:hover' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'author_icon_color',
                'label'   => __( 'Author Icon Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-author i' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'box_style' => array('1') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'author_text_color',
                'label'   => __( 'Author Text Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-author a' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'box_style' => array('1', '2') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'content_color',
                'label'   => __( 'Content Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-description' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'price_text_color',
                'label'   => __( 'Price Text Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-thumbnail .rtin-price' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .rtin-meta .rtin-price ins' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'price_bg_color',
                'label'   => __( 'Price Background Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-thumbnail .rtin-price' => 'background: {{VALUE}}',
                    '{{WRAPPER}} .rtin-meta .rtin-price ins' => 'background: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'rating_color',
                'label'   => __( 'Rating Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .review-stars-rated .review-stars.filled' => 'color: {{VALUE}}',
                    '{{WRAPPER}}  .review-stars-rated .review-stars.empty' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'others_icon_color',
                'label'   => __( 'Others Icon Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .rtin-meta .rtin-enrolled' => 'color: {{VALUE}}',
                    '{{WRAPPER}}  .rtin-meta span' => 'color: {{VALUE}}',
                    '{{WRAPPER}}  .rtin-meta i' => 'color: {{VALUE}}',
                    '{{WRAPPER}}  .rtin-meta .rt-wishlist-icon span:before' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'navigation_bg',
                'label'   => __( 'Navigation Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .entry-content .isotop-btn a' => 'background-color: {{VALUE}} !important',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'arrow_color',
                'label'   => __( 'Navigation Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .entry-content .isotop-btn a' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'navigation_hover_bg',
                'label'   => __( 'Navigation Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-content .isotop-btn a:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .entry-content .isotop-btn .current' => 'background-color: {{VALUE}} !important',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'navigation_hover_color',
                'label'   => __( 'Navigation Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .entry-content .isotop-btn a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .entry-content .isotop-btn .current' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'mode'  => 'section_end'
            ),
        );

        return $fields;

    }

    protected function render() {
        $data = $this->get_settings();

        $this->rt_load_scripts();

        switch ( $data['style'] ) {
            case 'style2':
                $template = 'view-2';
                break;
            default:
                $template = 'view-1';
                break;
        }

        return $this->rt_template( $template, $data );
    }

}
