<?php
/**
 * Plugin Name: Szósta wtyczka
 * Description: Opis szóstej wtyczki
 */

register_activation_hook(__FILE__, 'sp_activation');

function sp_activation()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'sp_posts';

    if ($wpdb->get_var("SHOW TABLES LIKE '" . $table_name . "'") != $table_name) {
        $query = "CREATE TABLE " . $table_name . " (
        id int(9) NOT NULL AUTO_INCREMENT,
        user_id MEDIUMINT(6) NOT NULL,
        post_content TEXT NOT NULL,
        create_date TIMESTAMP NOT NULL,
        PRIMARY KEY  (id))";

        $wpdb->query($query);
    }
}

class sp_czat
{
    private $wpdb;
    private $table_name;

    function sp_czat()
    {
        global $wpdb;
        $this->wpdb = $wpdb;
        $this->table_name = $wpdb->prefix . 'sp_posts';
        add_action('admin_menu', array($this, 'sp_add_menu'));
    }

    function sp_add_menu()
    {
        add_menu_page('Szosta Wtyczka', 'szosta wtyczka', 'administrator', 'szosta-wtyczka', array($this, 'sp_main_page'), '', 7);
    }

    function sp_main_page()
    {
        echo '<h2>Hello World</h2>';

        if (isset($_POST['sp_action'])) {
            if ($_POST['sp_posts'] == 'add') {
                if ($this->add_post($_POST['post_content'])) {
                    $notice = '<div class="notice notice-success">Dodano posta o treści: ' . $_POST['post_content'] . '</div>';
                } else {
                    $notice = '<div class="notice notice-error">Nie dodano posta o treści: ' . $_POST['post_content'] . '</div>';
                }
            } elseif ($_POST['sp_posts'] == 'edit') {
                if ($this->edit_post($_POST['sp_post_id'], $_POST['post_content'])) {
                    $notice = '<div class="notice notice-success">Edytowano wiadomość o treści: ' . $_POST['post_content'] . '</div>';
                } else {
                    $notice = '<div class="notice notice-error">Nie udało się zaktualizować wiadomości o treści: ' . $_POST['post_content'] . '</div>';
                }
            }
        }

        if (isset($_POST['zwpc_delete'])) {
            if ($this->delete_post($_POST['sp_post_id'])) {
                $notice = '<div class="notice notice-success">Usunięto wiadomość id: ' . $_POST['sp_post_id'] . '</div>';
            } else {
                $notice = '<div class="notice notice-error">Nie usunięto wiadomość o id: ' . $_POST['sp_post_id'] . '</div>';
            }
        }
        $edit = FALSE;
        if (isset($_POST['sp_to_edit'])) {
            $edit = $this->get_zwpc_post($_POST['sp_post_id']);
        } ?>
        <div class="warp">
            <?= isset($notice) ? $notice : ''; ?>
            <form method="POST">
                <?= $edit ? '<input type="hidden" name="zwpc_post_id" value="' . $edit->id . '" />' : ''; ?>
                <input type="hidden" name="zwpc_action" value="<?= $edit ? 'edit' : 'add'; ?>"/>
                <label for="post_content">Treść posta</label><br>
                <input type="text" name="post_content" value="<?= $edit ? $edit->post_content : ''; ?>"
                       placeholder="Treść posta"/>
                <input type="submit" value="<?= $edit ? 'Edytuj' : 'Dodaj'; ?>" class="button-primary"/>
            </form>
            <?php
            $all_posts = $this->get_zwpc_posts();
            if ($all_posts) {
                ?>
            <table class="widefat">
                <thead>
                <tr>
                    <th>ID</th>
                    <th>ID użytkownika</th>
                    <th>Treść wiadomości</th>
                    <th>Kiedy dodano</th>
                    <td>Akcja</td>
                </tr>
                </thead>
                <tfoot>
                <tr>
                    <th>ID</th>
                    <th>ID użytkownika</th>
                    <th>Treść wiadomości</th>
                    <th>Kiedy dodano</th>
                    <th>Akcja</th>
                </tr>
                </tfoot>
                <tbody>
                <?php
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
//        <?php

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

    function get_zwpc_post($id) {
        $id = esc_sql($id);
        $zwpc_post = $this->wpdb->get_results("SELECT * FROM $this->table_name WHERE id = '" . $id . "'");
        if(isset($zwpc_post[0])){
            return $zwpc_post[0];
        } else {
            return FALSE;
        }
    }
}

$sp_czat = new sp_czat();
