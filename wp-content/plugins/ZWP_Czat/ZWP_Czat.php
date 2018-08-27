<?php
/**
 * Plugin Name: ZWP Czat
 * Version: 1.0
 * Description: ZaawansowanyWordpress Czat - prosty czat z edytowalnymi wypowiedziami
 * Author: Daniel Kuczewski
 * Author URI: http://zaawansowanywordpress.pl
 * Plugin URI: http://zaawansowanywordpress.pl/6-baza-danych/
 */

define("ZWPC_PATH", plugin_dir_path(__FILE__));


class ZWP_Czat
{
    private $wpdb;
    private $table_name;

    function ZWP_Czat()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'zwpc_posts';
        add_action('admin_menu', array(&$this, 'zwpc_add_menu'));
    }

    function zwpc_add_menu()
    {
        add_menu_page('ZWP Czat', 'ZWP Czat', 'administrator', 'zwp-czat', array(&$this, 'zwpc_main_page'), '', 33);
    }

    function zwpc_main_page()
    {
        if (isset($_POST['zwpc_action'])) {
            if ($_POST['zwpc_action'] == 'add') {
                //Dodawanie wiadomości
                if ($this->add_post($_POST['post_content'])) {
                    $notice = '<div class="notice notice-success">Dodano wiadomość o treści: ' . $_POST['post_content'] . '</div>';
                } else {
                    $notice = '<div class="notice notice-error">Nie dodano wiadomość o treści: ' . $_POST['post_content'] . '</div>';
                }
            } else if ($_POST['zwpc_action'] == 'edit') {
                //edycja wiadomości
                if ($this->edit_post($_POST['zwpc_post_id'], $_POST['post_content'])) {
                    $notice = '<div class="notice notice-success">Edytowano wiadomość o treści: ' . $_POST['post_content'] . '</div>';
                } else {
                    $notice = '<div class="notice notice-error">Nie udało się zaktualizować wiadomości o treści: ' . $_POST['post_content'] . '</div>';
                }
            }
        }

        if (isset($_POST['zwpc_delete'])) {
            //usuwanie wiadomości
            if ($this->delete_post($_POST['zwpc_post_id'])) {
                $notice = '<div class="notice notice-success">Usunięto wiadomość id: ' . $_POST['zwpc_post_id'] . '</div>';
            } else {
                $notice = '<div class="notice notice-error">Nie usunięto wiadomość o id: ' . $_POST['zwpc_post_id'] . '</div>';
            }
        }

        //pobieram wiadomość do edycji
        $edit = FALSE;
        if (isset($_POST['zwpc_to_edit'])) {
            $edit = $this->get_zwpc_post($_POST['zwpc_post_id']);
        }

        ?>
        <div class="warp">
            <h2><span class="dashicons dashicons-admin-comments"></span>ZWP Czat</h2>
            <?= isset($notice) ? $notice : ''; ?>
            <form method="POST">
                <?= $edit ? '<input type="hidden" name="zwpc_post_id" value="' . $edit->id . '" />' : ''; ?>
                <input type="hidden" name="zwpc_action" value="<?= $edit ? 'edit' : 'add'; ?>"/>
                <label for="post_content">Treść posta</label><br>
                <input type="text" name="post_content" value="<?= $edit ? $edit->post_content : ''; ?>"
                       placeholder="Treść posta"/>
                <input type="submit" value="<?= $edit ? 'Edytuj' : 'Dodaj'; ?> post" class="button-primary"/>
            </form>
            <?php
            $all_posts = $this->get_zwpc_posts();
            if ($all_posts) {
                echo '<table class="widefat">';
                echo '<thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>ID użytkownika</th>
                                        <th>Treść wiadomości</th>
                                        <th>Kiedy dodano</th>
                                        <td>Akcja</td>
                                    </tr>
                                </thead>';
                echo '<tfoot>
                                    <tr>
                                        <th>ID</th>
                                        <th>ID użytkownika</th>
                                        <th>Treść wiadomości</th>
                                        <th>Kiedy dodano</th>
                                        <th>Akcja</th>
                                    </tr>
                                </tfoot>';
                echo '<tbody>';
                foreach ($all_posts as $p) {
                    echo '<tr>';
                    echo '<td>' . $p->id . '</td>';
                    echo '<td>' . $p->user_id . '</td>';
                    echo '<td>' . $p->post_content . '</td>';
                    echo '<td>' . $p->create_date . '</td>';
                    echo '<td><form method="POST">
                                        <input type="hidden" name="zwpc_post_id" value="' . $p->id . '" />
                                        <input type="submit" name="zwpc_to_edit" value="Edytuj" class="button-primary" />
                                        <input type="submit" name="zwpc_delete" value="Usuń" class="button-primary error" />
                                    </form></td>';
                    echo '</tr>';
                }
                echo '</tbody>';
                echo '</table>';
            }
            ?>
        </div>
        <?php
    }

    function add_post($post_content)
    {
        //sprawdzam czy nie pusty i czy jest zalogowany
        if (trim($post_content) != '' && is_user_logged_in()) {
            $user_id = get_current_user_id();
            $post_content = esc_sql($post_content);
            $this->wpdb->insert($this->table_name, array('user_id' => $user_id, 'post_content' => $post_content));
            return TRUE;
        }
        return FALSE;
    }

    function get_zwpc_posts()
    {
        return $this->wpdb->get_results("SELECT * FROM $this->table_name ORDER BY create_date DESC LIMIT 0,100");
    }

    //funkcja służąca do pobrania wiadomości o konkretnym id
    //zwraca obiekt
    function get_zwpc_post($id)
    {
        $id = esc_sql($id);
        $zwpc_post = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE id = '" . $id . "'");
        if (isset($zwpc_post[0])) {
            return $zwpc_post[0];
        } else {
            return FALSE;
        }
    }

    //funkcja edycji wiadomości pobiera id oraz nową treść
    function edit_post($id, $content)
    {
        if (trim($content) != '' && is_user_logged_in()) {
            $id = esc_sql($id);
            $content = esc_sql($content);
            $res = $this->wpdb->update($this->table_name, array('post_content' => $content), array('id' => $id));
            return $res;
        } else {
            return FALSE;
        }
    }

    //funkcja odpowiedzialna za usuwanie wiadomości
    function delete_post($id)
    {
        $id = esc_sql($id);
        if (is_user_logged_in()) {
            return $this->wpdb->delete($this->table_name, array('id' => $id));
        } else {
            return FALSE;
        }
    }

    function show_zwpc_html($place)
    {
        if (isset($_POST['post_content' . $place])) {
            $this->add_post($_POST['post_content' . $place]);
        }
        if (is_user_logged_in()) {
            echo '<form method="POST">
                <input type="hidden" name="zwpc_action" value="add"/>
                <label for="post_content' . $place . '">Treść posta</label><br>
                <input type="text" name="post_content' . $place . '" value="" placeholder="Treść posta"/>
                <input type="submit" value="Napisz" class="button-primary"/>
            </form>';
        }
        echo '<table>';
        $zwpc_posts = $this->get_zwpc_posts();
        if ($zwpc_posts) {
            foreach ($zwpc_posts as $zwpc_p) {
                echo '<tr>';
                echo '<td>' . $zwpc_p->create_date . ' <br> ' . $zwpc_p->post_content . '</td>';
                echo '</tr>';
            }
        }
        echo '</table>';
    }
}

$ZWP_Czat = new ZWP_Czat();

add_action('widgets_init', 'zwpc_register_widget');

function zwpc_register_widget()
{
    register_widget('ZWPC_Widget');
}

class ZWPC_Widget extends WP_Widget
{
    function ZWPC_Widget()
    {
        // tablica opcji.
        $widget_ops = array(
            'classname' => 'ZWPC_Widget', //nazwa klasy widgetu
            'description' => 'ZWP Czat', //opis widoczny w panelu
        );
        //ładowanie
        parent::__construct('ZWPC_Widget', 'ZWP Czat', $widget_ops);
    }

    function form($instance)
    {
        ?>
        <p>
            Widget wyświetla czat.
        </p>
        <?php
    }

    function update($new_instance, $old_instance)
    {
        return $old_instance;
    }

    function widget($args, $instance)
    {
        global $ZWP_Czat;
        echo $args['before_widget'];
        $ZWP_Czat->show_zwpc_html('widget');
        echo $args['after_widget'];
    }
}


register_activation_hook(__FILE__, 'zwpc_activation');

function zwpc_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'zwpc_posts';

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") != $table_name) {
        $query = "CREATE TABLE " . $table_name . " (
        id int(9) NOT NULL AUTO_INCREMENT,
        user_id MEDIUMINT(6) NOT NULL,
        post_content TEXT NOT NULL,
        create_date TIMESTAMP NOT NULL,
        PRIMARY KEY  (id)
        )";

        $wpdb->query($query);
    }

    if (get_page_by_title("ZWP Czat") == null) {
        $postarr = [
            "post_title" => "ZWP Czat",
            "post_content" => "Opis czatu",
            "post_type" => "page",
            "post_status" => "publish"
        ];

        wp_insert_post($postarr);
    }
}

add_filter('page_template', 'zwpc_change_chat_template');


function zwpc_change_chat_template($template)
{
    global $post;
    $single_template = ZWPC_PATH . '/templates/zwp_czat.php';
    if (isset($post->post_name) && $post->post_name == 'zwp-czat') {
        return $single_template;
    } else {
        return $template;
    }
}