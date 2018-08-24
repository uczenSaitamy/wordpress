<?php
/**
Plugin Name: Druga wtyczka
Description: Opis drugiej wtyczki
*/

add_action( 'widgets_init', 'mdw_register_first_widget' );

function mdw_register_first_widget() {
    register_widget( 'Pierwszy_Widget');
}

class Pierwszy_Widget extends WP_Widget
{
    function Pierwszy_Widget()
    {
        $widget_ops = array(
            'classname' => 'Pierwszy_Widget',
            'description' => 'Opis pierwszego widgetu',
        );

        parent::__construct('Pierwszy_Widget', 'Pierwszy Widget', $widget_ops);
    }

    function form($instance)
    {
        // formularz w kokpicie admina
        $defaults = array(
            'tekst' => 'Mój tekst'
        );
        $instance = wp_parse_args((array)$instance, $defaults);
        $tekst = $instance['tekst'];
        ?>
        <p>
            <label>Twój tekst</label>
            <input type="text" name="<?= $this->get_field_name('tekst');?>" value="<?= esc_attr($tekst);?>" />
        </p>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        // zapis opcji widgetu
        $instance = $old_instance;
        $instance['tekst'] = strip_tags($new_instance['tekst']);
        return $instance;
    }

    function widget($args, $instance)
    {
        // wyświetlanie widgetu
        extract($args);
        echo $before_widget;
        if(!empty($instance['tekst'])){
            echo '<p>' . $instance['tekst'] . '<p>';
        }
        echo $after_widget;
    }
}