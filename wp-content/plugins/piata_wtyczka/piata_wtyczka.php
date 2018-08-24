<?php
/**
Plugin Name: Piąta wtyczka
Description: Opis piątej wtyczki
*/

add_action( 'admin_menu', 'pw_add_menu' );
add_action( 'wp_footer', 'pw_add_footer', 99 );
// add_action( 'wp_header', 'pw_add_header', 99);

function pw_add_menu()
{
    add_menu_page( 'Piata Wtyczka', 'piata wtyczka menu', 'administrator', 'piata-wtyczka', 'Main_Function', '', 7 );    
}

function pw_add_footer()
{
    echo '<br><p>To jest wtyczka w footerze PW</p>';
}

function pw_add_header()
{
    echo '<br><p>To jest wtyczka w headerze PW</p>';
}

function Main_Function()
{
    echo 'strona główna wtyki';
}

