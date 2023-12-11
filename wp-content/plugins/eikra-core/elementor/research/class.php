<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined('ABSPATH' ) ) exit;

class Research extends Custom_Widget_Base {

    public function __construct( $data = [], $args = null ) {
        $this->rt_name  = __( 'Research', 'eikra-core' );
        $this->rt_base  = 'rt-research';
        parent::__construct( $data, $args );
    }

    public function rt_fields() {

        $terms = get_terms( array('taxonomy' => 'ac_research_category' ) );
        $category_dropdown = array( '0' => __( 'All Categories', 'eikra-core' ) );
        foreach ( $terms as $category ) {
            $category_dropdown[$category->term_id] = $category->name;
        }

        $orderby = array(
            'date'          => __( 'Date (Recents comes first)', 'eikra-core' ),
            'title'         =>  __( 'Title', 'eikra-core' ),
            'menu_order'    => __( 'Custom Order (Available via Order field inside Post Attributes box)', 'eikra-core' ),
        );

        $sortby = array(
            'ASC'       => __( 'Ascending', 'eikra-core' ),
            'DESC'      =>  __( 'Descending', 'eikra-core' ),
        );

        $column = array(
            '3' => __('4 Column', 'eikra-core'),
            '4' => __('3 Column', 'eikra-core'),
            '6' => __('2 Column', 'eikra-core'),
            '12' => __('1 Column', 'eikra-core'),
        );

        $fields = array(
            array(
                'mode'  => 'section_start',
                'id'    => 'section_general',
                'label' => __( 'General', 'eikra-core' )
            ),
            array(
                'id'    => 'style',
                'label' => __( 'Style', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => array(
                    'layout1'   => __( 'Style 1', 'eikra-core' ),
                    'layout2'   => __( 'Style 2', 'eikra-core'),
                    'layout3'   => __( 'Style 3', 'eikra-core'),
                ),
                'default'   => 'layout1',
            ),
            array(
                'id'        => 'item_no',
                'label'     => __( 'Items Per Page', 'eikra-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 9,
                'description' => __( 'Write -1 to show all', 'eikra-core' ),
            ),
            array(
                'id'        => 'orderby',
                'label'     => __( 'Order by', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $orderby,
                'default'   => 'date',
            ),
            array(
                'id'        => 'sortby',
                'label'     => __( 'Sort by', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $sortby,
                'default'   => 'DESC',
            ),
            array(
                'id'        => 'length',
                'label'     => __( 'Excerpt Length', 'eikra-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 35,
                'description' => __( 'Maximum number of words', 'eikra-core' ),
            ),
            array(
                'type'        => Controls_Manager::SWITCHER,
                'id'          => 'btn_display',
                'label'       => esc_html__( 'Button Display', 'eikra-core' ),
                'label_on'    => esc_html__( 'On', 'eikra-core' ),
                'label_off'   => esc_html__( 'Off', 'eikra-core' ),
                'default'     => 'no',
                'condition'   => array( 'style' => array( 'layout1' ) ),
            ),
            array(
                'type'    => Controls_Manager::TEXT,
                'id'      => 'buttontext',
                'label'   => esc_html__( 'Button Text', 'eikra-core' ),
                'default' => 'Read More',
                'condition'   => array( 'btn_display' => array( 'yes' ) ),
            ),
            array(
                'mode'  => 'section_end'
            ),

            // Grid Column

            array(
                'mode'    => 'section_start',
                'id'      => 'sec_grid',
                'label'   => __( 'Grid Column', 'eikra-core' ),
                'condition'   => array( 'style' => array( 'layout2', 'layout3' ) ),
            ),
            array(
                'id'        => 'col_lg',
                'label'     => __( 'Columns ( Desktops > 1199px )', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $column,
                'default'   => '4',
            ),
            array(
                'id'        => 'col_md',
                'label'     => __( 'Columns ( Desktops > 991px )', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $column,
                'default'   => '4',
            ),
            array(
                'id'        => 'col_sm',
                'label'     => __( 'Columns ( Tablets > 767px )', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $column,
                'default'   => '6',
            ),
            array(
                'id'        => 'col_xs',
                'label'     => __( 'Columns ( Phones < 768px )', 'eikra-core' ),
                'type'      => Controls_Manager::SELECT,
                'options'   => $column,
                'default'   => '12',
            ),
            array(
                'mode'  => 'section_end'
            ),

            // Style Tab

            array(
                'mode'      => 'section_start',
                'id'        => 'sec_general_style',
                'tab'       => Controls_Manager::TAB_STYLE,
                'label'     => __( 'General', 'eikra-core' ),
            ),
            array (
                'mode'      => 'group',
                'type'      => Group_Control_Typography::get_type(),
                'id'        => 'title_typo',
                'label'     => esc_html__( 'Title', 'eikra-core' ),
                'selector'  => '{{WRAPPER}} .rtin-title a',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'      => 'group',
                'type'      => Group_Control_Typography::get_type(),
                'id'        => 'content_typo',
                'label'     => esc_html__( 'Content', 'eikra-core' ),
                'selector'  => '{{WRAPPER}} .rtin-content',
                'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'      => 'group',
                'type'      => Group_Control_Typography::get_type(),
                'id'        => 'btn_typo',
                'label'     => esc_html__( 'Button', 'eikra-core' ),
                'selector'  => '{{WRAPPER}} .rtin-btn',
                'condition' => array( 'style' => array('layout1') ),
                'scheme'    => Scheme_Typography::TYPOGRAPHY_3,
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
                'id'      => 'title_bg',
                'label'   => __( 'Title Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a' => 'background: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout3') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'title_hover_bg',
                'label'   => __( 'Title Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a:hover' => 'background: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout3') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'content_color',
                'label'   => __( 'Content Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-content' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'btn_bg',
                'label'   => __( 'Button Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn' => 'background: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout1') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'btn_color',
                'label'   => __( 'Social Icon Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout1') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'btn_hover_bg',
                'label'   => __( 'Button Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn:hover' => 'background: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout1') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'btn_hover_color',
                'label'   => __( 'Button Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn:hover' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout1') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'seperator_color',
                'label'   => __( 'Bottom Line Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title::after' => 'background: {{VALUE}}',
                ),
                'condition'   => array( 'style' => array('layout1', 'layout2') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_bg',
                'label'   => __( 'Pagination Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .pagination-area ul li a' => 'background-color: {{VALUE}} !important',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_color',
                'label'   => __( 'Pagination Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .pagination-area ul li a' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_hover_bg',
                'label'   => __( 'Pagination Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .pagination-area ul li a:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}}  .pagination-area ul li.active a' => 'background-color: {{VALUE}} !important',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_hover_color',
                'label'   => __( 'Pagination Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}}  .pagination-area ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}}  .pagination-area ul li.active a' => 'color: {{VALUE}}',
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

        switch ( $data['style'] ) {
            case 'layout3':
                $template = 'view-3';
                break;
            case 'layout2':
                $template = 'view-2';
                break;
            default:
                $template = 'view-1';
        }

        return $this->rt_template( $template, $data );
    }

}
