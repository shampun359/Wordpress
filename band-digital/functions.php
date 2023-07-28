<?php
//Конструкция установки логотипа
if (! function_exists('band_digital_setup')) {
    function band_digital_setup(){
        add_theme_support( 'custom-logo', [
            'height'      => 130,
            'width'       => 50,
            'flex-width'  => true,
            'flex-height' => true,
            'header-text' => '',
            'unlink-homepage-logo' => false, // WP 5.5
        ]);
    }
        add_action( 'after_setup_theme', 'band_digital_setup' );
}




/*
Подключение стилей искриптов
*/

add_action( 'wp_enqueue_scripts', 'band_digital_scripts' );
// add_action('wp_print_styles', 'theme_name_scripts'); // можно использовать этот хук он более поздний
function band_digital_scripts() {
	wp_enqueue_style( 'main', get_stylesheet_uri() );//Получает ссылку (URL) на файл стилей style.css текущей темы.
    // Bootstrap css
    wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/plugins/bootstrap/css/bootstrap.css' , array('main') );
    // fontawesome
    wp_enqueue_style( 'fontawesome', get_template_directory_uri() . '/plugins/fontawesome/css/all.css' , array('bootstrap') );
    // animate
    wp_enqueue_style( 'animate', get_template_directory_uri() . '/plugins/animate-css/animate.css' , array('fontawesome') );
    // Icofont
    wp_enqueue_style( 'Icofont', get_template_directory_uri() . '/plugins/icofont/icofont.css' , array('animate') );
    wp_enqueue_style( 'band-digital', get_template_directory_uri() . '/css/style.css' , array('Icofont') );


    //Переподключаем jQuery
    wp_deregister_script( 'jquery' );
	wp_register_script( 'jquery', get_template_directory_uri() . '/plugins/jquery/jquery.min.js');
	wp_enqueue_script( 'jquery' );

    wp_enqueue_script( 'popper' , get_template_directory_uri() . '/plugins/bootstrap/js/popper.min.js', array('jquery'), true);
    wp_enqueue_script( 'bootstrap' , get_template_directory_uri() . '/plugins/bootstrap/js/bootstrap.min.js', array('jquery'), true);
    wp_enqueue_script( 'wow' , get_template_directory_uri() . '/plugins/counterup/wow.min.js', array('jquery'), true);
    wp_enqueue_script( 'easing' , get_template_directory_uri() . '/plugins/counterup/jquery.easing.1.3.js', array('jquery'), true);
    wp_enqueue_script( 'waypoints' , get_template_directory_uri() . '/plugins/counterup/jquery.waypoints.js', array('jquery'), true);
    wp_enqueue_script( 'counterup' , get_template_directory_uri() . '/plugins/counterup/jquery.counterup.min.js', array('jquery'), true);
    wp_enqueue_script( 'google-map' , get_template_directory_uri() . '/plugins/google-map/gmap3.min.js', array('jquery'), true);
    wp_enqueue_script( 'contact' , get_template_directory_uri() . '/plugins/jquery/contact.js', array('jquery'), true);
    wp_enqueue_script( 'custom' , get_template_directory_uri() . '/js/custom.js', array('jquery'), true);

}

add_action('init', 'band_digital_menus');

//Регестрируем области  меню
function band_digital_menus() {
    //Собираем зоны меню
	$locations = array(
		'header'  => __( 'Header Menu', 'band_digital' ),
		'footer'  => __( 'Footer Menu', 'band_digital' ),
	);
    //Регестрируем области меню, которые лежат в переменной $Locations
	register_nav_menus( $locations );
}

add_action('init', 'band_digital_menus');

class bootstrap_4_walker_nav_menu extends Walker_Nav_menu {
    
    function start_lvl( &$output, $depth = 0, $args = array() ){ // ul
    $indent = str_repeat("\t",$depth); // indents the outputted HTML
    $submenu = ($depth > 0) ? ' sub-menu' : '';
    $output .= "\n$indent<ul class=\"dropdown-menu$submenu depth_$depth\">\n";
       }
     
    function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ){ // li a span
           
    $indent = ( $depth ) ? str_repeat("\t",$depth) : '';
       
    $li_attributes = '';
    $class_names = $value = '';
       
    $classes = empty( $item->classes ) ? array() : (array) $item->classes;
           
    $classes[] = ($args->walker->has_children) ? 'dropdown' : '';
    $classes[] = ($item->current || $item->current_item_anchestor) ? 'active' : '';
    $classes[] = 'nav-item';
    $classes[] = 'nav-item-' . $item->ID;
    if( $depth && $args->walker->has_children ){
    $classes[] = 'dropdown-menu';
           }
           
    $class_names = join(' ', apply_filters('nav_menu_css_class', array_filter( $classes ), $item, $args ) );
    $class_names = ' class="' . esc_attr($class_names) . '"';
           
    $id = apply_filters('nav_menu_item_id', 'menu-item-'.$item->ID, $item, $args);
    $id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
           
    $output .= $indent . '<li ' . $id . $value . $class_names . $li_attributes . '>';
           
           $attributes = ! empty( $item->attr_title ) ? ' title="' . esc_attr($item->attr_title) . '"' : '';
           $attributes .= ! empty( $item->target ) ? ' target="' . esc_attr($item->target) . '"' : '';
           $attributes .= ! empty( $item->xfn ) ? ' rel="' . esc_attr($item->xfn) . '"' : '';
           $attributes .= ! empty( $item->url ) ? ' href="' . esc_attr($item->url) . '"' : '';
           
           $attributes .= ( $args->walker->has_children ) ? ' class="nav-link dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false"' : ' class="nav-link"';
           
           $item_output = $args->before;
           $item_output .= ( $depth > 0 ) ? '<a class="dropdown-item"' . $attributes . '>' : '<a' . $attributes . '>';
           $item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
           $item_output .= '</a>';
           $item_output .= $args->after;
           
           $output .= apply_filters ( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
       
       }
       
   }


