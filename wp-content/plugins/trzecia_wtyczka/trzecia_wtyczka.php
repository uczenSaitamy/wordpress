<?php
/**
Plugin Name: Trzecia wtyczka
Description: Opis Trzeciej wtyczki
*/

add_action('admin_menu', 'mtw_add_menu');

function mtw_add_menu()
{
    add_menu_page( 'Strona główna wtyczki', 'Moja trzecia wtyczka', 'administrator', 'moja-trzecia-wtyczka', 'glowna_trzecia_wtyczka', '', 100 );
}

function glowna_trzecia_wtyczka()
{
    // echo 'Formularz opcji';
    if  (isset($_POST['mtw_option'])){
        $option = esc_sql( $_POST['mtw_option'] );
        update_option( 'mtw_option', $option, 'no' );
        $message = 'Zaaktualizowano opcję.';
    }
    $opcion = get_option( ' mtw_option');
    ?>
        <div class="warp">
            <h2>Trzecia wtyczka</h2>
            <div class="row">
                <?= isset($message) ? '<div id="message" class="updated">' . $message . '</div>' : '';?>
            </div>
        </div>

        <div class="row">
            <form method="POST">
                <label>
                    Opcja <br>
                    <input type="text" name="mtw_option" value="<?= $option; ?>">
                </label>

                <input type="submit" value="Aktualizuj" class="button-primary">
            </form>
        </div>
    <?php
}

register_activation_hook(__FILE__, 'mtw_activation');

function mtw_activation()
{
    // kod aktywacji wtyczki
    if(!get_option('mtw_option')){
        add_option('mtw_option', 'tekst', '', 'no');
    }
}

register_deactivation_hook(__FILE__, 'mtw_deactivation');

function mtw_deactivation()
{
    // kod deaktywacji wtyczki
    $option = get_option('mtw_option');
    $option = strrev($option);
    update_option('mtw_option', $option);
}