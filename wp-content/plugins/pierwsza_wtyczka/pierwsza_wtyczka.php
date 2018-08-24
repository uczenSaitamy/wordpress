<?php
/**
Plugin Name: Pierwsza wtyczka
Description: Opis pierwszej wtyczki
*/

// $page_title = 'Pierwsza Wtyczka';
// $menu_title = 'Pierwsza Wtyczka Menu';
// $capability = 'administrator';
// $menu_slug = 'pierwsza-wtyczka';
// $icon_url= '';
// $position = 7;
// $function = 'glowna-pierwsza-wtyczka';

function mpw_add_menu(){
    add_menu_page( 'Strona główna wtyczki', 'Moja pierwsza wtyczka', 'administrator', 'moja-pierwsza-wtyczka', 'glowna_moja_pierwsza_wtyczka', '', 7 );
    add_submenu_page( 'moja-pierwsza-wtyczka', 'Strona podmenu', 'Podmenu', 'administrator', 'moja-pierwsza-wtyczka-podmenu1', 'podstrona_moja_pierwsza_wtyczka' );
}

function glowna_moja_pierwsza_wtyczka(){
    echo "Hello World";
}

function podstrona_moja_pierwsza_wtyczka(){
    echo "Hello World 2";
}

add_action( 'admin_menu', 'mpw_add_menu' );
