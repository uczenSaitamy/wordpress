<?php
/**
Plugin Name: Czwarta wtyczka
Description: Opis czwartej wtyczki
*/
function cw_add_menu()
{
    add_menu_page( 'Czwarta Wtyczka', 'czwarta wtyczka menu', 'administrator', 'czwarta-wtyczka', 'Main_Function', '', 7 );    
}

function Main_Function()
{
    ?>
        <div class="warp">
            Czwarta Wtyczka
            <h2>Moja czwarta wtyczka</h2>
    <h3>nagłówek h3</h3>
    <h4>nagłówek h4</h4>
    <h5>nagłówek h5</h5>
    <h6>nagłówek h6</h6>
    <p>Przykładowy tekst paragrafu</p>
        </div>

<div class="notice notice-success"><p>Komunikat sukcesu</p></div>
<div class="notice notice-error"><p>Komunikat błędu</p></div>
<div class="notice notice-info"><p>Komunikat informujący</p></div>



<form method="POST">
    <h3>Sekcja formularza</h3>
    <p>Opis sekcji formularza</p>
    <table class="form-table">
        <tbody>
        <tr>
            <th scope="row">
                <label for="pierwsze_pole">Pierwsze pole</label>
            </th>
            <td>
                <input type="text" id="pierwsze_pole" name="pierwsze_pole" value=""
                       placeholder="Wpisz dowolny tekst"/><br>
                <span class="description">Przykładowa informacja</span>
            </td>
        </tr>
        <tr>
            <th scope="row">
                <label for="drugie_pole">Drugie pole</label>
            </th>
            <td>
                <select id="drugie_pole" name="drugie_pole">
                    <option value="opcja1">Opcja 1</option>
                    <option value="opcja2">Opcja 2</option>
                    <option value="opcja3">Opcja 3</option>
                </select><br>
            </td>
        </tr>
        <tr>
            <td colspan="2">
                <input type="submit" class="button-primary" value="podstawowy"/>
                <input type="submit" class="button-secondary" value="drugorzędny"/>
                <input type="submit" class="button-highlighted" value="wyróżniony"/>
                <input type="submit" value="bez klasy"/>
            </td>
        </tr>
        </tbody>
    </table>
</form>
<table class="widefat">
    <thead>
        <tr>
            <th>Kolumna 1</th>
            <th>Kolumna 2</th>
            <th>Kolumna 3</th>
        </tr>
    </thead>
    <tfoot>
        <tr>
            <th>Kolumna 1</th>
            <th>Kolumna 2</th>
            <th>Kolumna 3</th>
        </tr>
    </tfoot>
    <tbody>
        <tr>
            <td>dane 1</td>
            <td>dane 2</td>
            <td>dane 3</td>
        </tr>
        <tr>
            <td>dane 1</td>
            <td>dane 2</td>
            <td>dane 3</td>
        </tr>
    </tbody>
</table>

<div class="tablenav">
    <div class="tablenav-pages">
        <span class="displaying-num"> Wświetlam 1-50 z 349</span>
        <span class="page-numbers current">1</span>
        <a href="@" class="page-numbers">2</a>
        <a href="@" class="page-numbers">4</a>
        <a href="@" class="page-numbers">5</a>
        <a href="@" class="next page-numbers">&raquo;</a>
    </div>
</div>
    <?php
}

add_action( 'admin_menu', 'cw_add_menu' );
