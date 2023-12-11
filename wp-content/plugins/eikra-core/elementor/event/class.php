<?php

use Elementor\Controls_Manager;
use Elementor\Group_Control_Typography;
use Elementor\Scheme_Typography;

if ( ! defined('ABSPATH' ) ) exit;

class Event extends Custom_Widget_Base {

    public function __construct( $data = [], $args = null ) {
        $this->rt_name  = __( 'Event', 'eikra-core' );
        $this->rt_base  = 'rt-event';
        parent::__construct( $data, $args );
    }

    public function sort_by_time( $a, $b ) {
        return $a['timestamp'] - $b['timestamp'];
    }

    public function sort_by_time_past( $a, $b ) {
        return $b['timestamp'] - $a['timestamp'];
    }

    public function get_events( $cat, $type, $number ) {
        $events = array();

        $args = array(
            'posts_per_page'      => -1,
            'post_type'           => 'ac_event',
            'suppress_filters'    => false,
            'ignore_sticky_posts' => 1,
        );

        if ( !empty( $cat ) ) {
            $args['tax_query'] = array(
                array(
                    'taxonomy' => 'ac_event_category',
                    'field'    => 'term_id',
                    'terms'    => $cat,
                )
            );
        }

        $all_events = get_posts( $args );

        $current_time = current_time( 'timestamp' );

        foreach ( $all_events as $event ) {
            $start_date = get_post_meta( $event->ID, 'ac_event_start_date', true );
            $start_time = get_post_meta( $event->ID, 'ac_event_start_time', true );
            $end_date   = get_post_meta( $event->ID, 'ac_event_end_date', true );
            $end_time   = get_post_meta( $event->ID, 'ac_event_end_time', true );

            if ( empty( $start_date ) || empty( $start_time ) ) {
                continue;
            }

            $event_time = $start_date . ' ' . $start_time;
            $event_time = strtotime( $event_time );

            if ( $type == 'past' && $event_time >= $current_time) {
                continue;
            }
            if ( $type == 'upcoming' && $event_time < $current_time ) {
                continue;
            }

            $time_pattern = RDTheme::$options['event_time_format'] == '12' ? 'g:ia' : 'H:i';
            $start_time   = date_i18n( $time_pattern, strtotime( $start_time ) );
            $end_time     = $end_time ? date_i18n( $time_pattern, strtotime( $end_time ) ) : '';

            $events[] = array(
                'id'         => $event->ID,
                'title'      => $event->post_title,
                'content'    => has_excerpt( $event->ID ) ? $event->post_excerpt : $event->post_content,
                'start_date' => $start_date,
                'start_time' => $start_time,
                'end_date'   => $end_date,
                'end_time'   => $end_time,
                'location'   => get_post_meta( $event->ID, 'ac_event_location', true ),
                'timestamp'  => $event_time,
            );
        }

        if ( $type == 'past'){
            usort( $events, array( $this, 'sort_by_time_past' ) );
        }
        else {
            usort( $events, array( $this, 'sort_by_time' ) );
        }

        if ( $number == 'grid' ) {
            return $events;
        }

        return array_slice( $events, 0, $number );
    }

    public function rt_fields() {

        $terms = get_terms( array( 'taxonomy' => 'ac_event_category' ) );
        $category_dropdown = array( '0' => __( 'All Categories', 'eikra-core' ) );
        foreach ( $terms as $category ) {
            $category_dropdown[$category->term_id] = $category->name;
        }

        $fields = array(
            array(
                'mode'  => 'section_start',
                'id'    => 'section_general',
                'label' => __( 'General', 'eikra-core' )
            ),
            array(
                'id'    => 'layout',
                'label' => __( 'Style', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => array(
                    'list'   => __( 'List', 'eikra-core' ),
                    'grid'   => __( 'Grid', 'eikra-core'),
                    'box'   => __( 'Box', 'eikra-core'),
                ),
                'default'   => 'list',
            ),
            array(
                'id'    => 'type',
                'label' => __( 'Event Type', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => array(
                    'all'       => __( 'All Event', 'eikra-core' ),
                    'upcoming'  => __( 'Upcoming Events', 'eikra-core'),
                    'past'      => __( 'Past Events', 'eikra-core'),
                ),
                'default'   => 'all',
            ),
            array(
                'id'        => 'title',
                'label'     => __( 'Title', 'eikra-core' ),
                'type'      => Controls_Manager::TEXT,
                'condition'   => array( 'layout' => array( 'list') ),
            ),
            array(
                'id'    => 'cat',
                'label' => __( 'Categories', 'eikra-core' ),
                'type'  =>  Controls_Manager::SELECT,
                'options'   => $category_dropdown,
                'default'   => '0',
                'condition'   => array( 'layout' => array( 'list') ),
            ),
            array(
                'id'        => 'item_no',
                'label'     => __( 'Total number of items', 'eikra-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 2,
                'description' => __( 'Write -1 to show all', 'eikra-core' ),
                'condition'   => array( 'layout' => array( 'list', 'box') ),
            ),
            array(
                'id'        => 'grid_item_no',
                'label'     => __( 'Number of items per page', 'eikra-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 4,
                'description' => __( 'Write -1 to show all', 'eikra-core' ),
                'condition'   => array( 'layout' => array( 'grid') ),
            ),
            array(
                'id'        => 'length',
                'label'     => __( 'Excerpt Length', 'eikra-core' ),
                'type'      => Controls_Manager::NUMBER,
                'default'   => 35,
                'description' => __( 'Maximum number of words', 'eikra-core' ),
                'condition'   => array( 'layout' => array( 'grid', 'list') ),
            ),
            array(
                'type'        => Controls_Manager::SWITCHER,
                'id'          => 'pagination',
                'label'       => esc_html__( 'Pagination Display', 'eikra-core' ),
                'label_on'    => esc_html__( 'On', 'eikra-core' ),
                'label_off'   => esc_html__( 'Off', 'eikra-core' ),
                'default'     => 'yes',
                'condition'   => array( 'layout' => array( 'grid') ),
            ),
            array(
                'type'        => Controls_Manager::SWITCHER,
                'id'          => 'btn_display',
                'label'       => esc_html__( 'Button Display', 'eikra-core' ),
                'label_on'    => esc_html__( 'On', 'eikra-core' ),
                'label_off'   => esc_html__( 'Off', 'eikra-core' ),
                'default'     => 'yes',
                'condition'   => array( 'layout' => array( 'list', 'box') ),
            ),
            array(
                'type'        => Controls_Manager::TEXT,
                'id'          => 'btn_text',
                'label'       => esc_html__( 'Button Text', 'eikra-core' ),
                'default'     => 'All Events',
                'condition'   => array(
                    'layout'        => array( 'list', 'box'),
                    'btn_display'   => array( 'yes'),
                ),
            ),
            array(
                'type'  => Controls_Manager::URL,
                'id'    => 'buttonurl',
                'label' => esc_html__( 'Button Link', 'eikra-core' ),
                'default' => [
                    'url' => '#',
                    'is_external' => false,
                    'nofollow' => false,
                ],
                'placeholder' => 'https://your-link.com',
                'condition'   => array(
                    'layout'        => array( 'list'),
                    'btn_display'   => array( 'yes'),
                ),
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
                'label'   => esc_html__( 'Title', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rt-vc-title-left',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array(
                    'layout'        => array( 'list'),
                ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'event_title_typo',
                'label'   => esc_html__( 'Event Title', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-right h3',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'content_typo',
                'label'   => esc_html__( 'Content', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-content',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array( 'layout' => array( 'grid', 'list') ),
            ),
            array(
                'id'       => 'content_width',
                'label'    => __( 'Content Width', 'eikra-core' ),
                'type'     => Controls_Manager::SLIDER,
                'size_units' => [ 'px', '%' ],
                'range' => [
                    'px' => [
                        'min' => 0,
                        'max' => 360,
                        'step' => 5,
                    ],
                    '%' => [
                        'min' => 0,
                        'max' => 100,
                    ],
                ],
                'default' => [
                    'unit' => '%',
                    'size' => 100,
                ],
                'selectors' => array(
                    '{{WRAPPER}} .rtin-content' => 'width: {{SIZE}}{{UNIT}};',
                ),
                'condition'   => array( 'layout' => array( 'grid', 'list') ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'time_typo',
                'label'   => esc_html__( 'Time', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-time',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'location_typo',
                'label'   => esc_html__( 'Location', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-location',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'date_typo',
                'label'   => esc_html__( 'Date', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-calender h3',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array(
                    'layout'        => array( 'list', 'grid'),
                ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'year_typo',
                'label'   => esc_html__( 'Year', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-calender span',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array(
                    'layout'        => array( 'list', 'grid'),
                ),
            ),
            array (
                'mode'    => 'group',
                'type'    => Group_Control_Typography::get_type(),
                'id'    => 'date_year_typo',
                'label'   => esc_html__( 'Date', 'eikra-core' ),
                'selector' => '{{WRAPPER}} .rtin-calender h3',
                'scheme'   => Scheme_Typography::TYPOGRAPHY_3,
                'condition'   => array(
                    'layout'        => array( 'box'),
                ),
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
                    '{{WRAPPER}} .rt-vc-title-left' => 'color: {{VALUE}}',
                ),
                'condition'   => array(
                    'layout'        => array( 'list'),
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'event_title_color',
                'label'   => __( 'Event Title Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'event_title_hover_color',
                'label'   => __( 'Event title Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-title a:hover' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'content_color',
                'label'   => __( 'Content Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-content' => 'color: {{VALUE}}',
                ),
                'condition'   => array(
                    'layout'        => array( 'list', 'grid'),
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'time_color',
                'label'   => __( 'Time Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-time' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'location_color',
                'label'   => __( 'Location Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-location' => 'color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'box_bg',
                'label'   => __( 'Box Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-item' => 'background-color: {{VALUE}}',
                ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_bg',
                'label'   => __( 'Pagination Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .pagination-area ul li a' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('grid') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_color',
                'label'   => __( 'Pagination Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .pagination-area ul li a' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'layout' => array('grid') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_hover_bg',
                'label'   => __( 'Pagination Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .pagination-area ul li a:hover' => 'background-color: {{VALUE}} !important',
                    '{{WRAPPER}} .pagination-area ul li.active a' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('grid') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'pagination_hover_color',
                'label'   => __( 'Pagination Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .pagination-area ul li a:hover' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .pagination-area ul li.active a' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'layout' => array('grid') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_bg',
                'label'   => __( 'Button Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('list', 'box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_color',
                'label'   => __( 'Button Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a' => 'color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('list', 'box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_hover_bg',
                'label'   => __( 'Button Hover Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a:hover' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('list', 'box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_hover_color',
                'label'   => __( 'Button Hover Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a:hover' => 'color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('list', 'box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_border_color',
                'label'   => __( 'Button Border Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a' => 'border-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'button_hovr_border_color',
                'label'   => __( 'Button Hover Border Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-btn a:hover' => 'border-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('box') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'calender_top_bg',
                'label'   => __( 'Calendar Top Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-calender' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('grid', 'list') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'calender_top_color',
                'label'   => __( 'Calendar Top Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-calender h3' => 'color: {{VALUE}}',
                    '{{WRAPPER}} .rtin-calender p' => 'color: {{VALUE}}',
                ),
                'condition'   => array( 'layout' => array('grid', 'list') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'calender_bottom_bg',
                'label'   => __( 'Calendar Bottom Background', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-calender span' => 'background-color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('grid', 'list') ),
            ),
            array(
                'type'    => Controls_Manager::COLOR,
                'id'      => 'calender_bottom_color',
                'label'   => __( 'Calendar Bottom Color', 'eikra-core' ),
                'selectors' => array(
                    '{{WRAPPER}} .rtin-calender span' => 'color: {{VALUE}} !important',
                ),
                'condition'   => array( 'layout' => array('grid', 'list') ),
            ),
            array(
                'mode'  => 'section_end'
            ),
        );

        return $fields;

    }

    protected function render() {
        $data = $this->get_settings();

        $cat = $data['cat'];
        $type = $data['type'];
        $number = $data['item_no'];

        switch ( $data['layout'] ) {
            case 'grid':
                $events = $this->get_events( $cat, $type, 'grid' );
                $template = 'view-3';
                break;
            case 'box':
                $events = $this->get_events( $cat, $type, $number );
                $template = 'view-2';
                break;
            default:
                $events = $this->get_events( $cat, $type, $number );
                $template = 'view-1';
        }

        $data['events'] = $events;

        return $this->rt_template( $template, $data );
    }

}
