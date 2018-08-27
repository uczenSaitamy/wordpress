<?php
/**
 * Plugin Name: Piąta wtyczka
 * Description: Opis piątej wtyczki
 */

add_action('admin_menu', 'pw_add_menu');

function pw_add_menu()
{
    add_menu_page('Piata Wtyczka', 'piata wtyczka menu', 'administrator', 'piata-wtyczka', 'Main_Function', '', 7);
}

function Main_Function()
{
    echo '<h2>strona główna wtyki</h2>';
    echo '<p>To jest paragraf</p>';
}

function pw_add_main_func()
{
    echo '<br><p>To jest wtyczka w głownej PW</p>';
}

add_filter(  'comment_text', 'pw_comment_cenzure');

function pw_comment_cenzure( $comment ){
    $to_cenzure = array('pierwszy','drugi');
    $replace_cenz = array('trzeci', 'czwarty');
    $comment = str_replace($to_cenzure,$replace_cenz, $comment);
    return $comment;
}

remove_all_filters( 'wp_footer');

class M5w_Class {
    function __construct() {
        add_filter( 'comment_text' , array( &$this, 'add_p'), 0);
    }

    function add_p( $content ) {
        $content = '<p style="color:red;">' . $content;
        $content .= '</p>';
        return $content;
    }
}
$m5w_object = new M5w_Class();