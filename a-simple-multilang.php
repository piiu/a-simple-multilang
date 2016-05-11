<?php
/*
Plugin Name: A Simple Multilanguage Plugin
Description: Multilanguage support for pages and posts. Displays content based on selected language. Includes widget and shortcode for language selection.
Version:    1.0
Author:     Piu
Author URI: http://www.piu.ee/
License:    GPL2
 */

defined( 'ABSPATH' ) or die( 'No.' );

function asmp_plugin_settings_menu()
{
    add_submenu_page('options-general.php', 'A Simple Multilanguage Plugin settings', 'Languages [ASMP]', 'administrator', 'asmp', 'asmp_settings_page');
    add_action('admin_init', 'asmp_register_settings');
}
add_action('admin_menu', 'asmp_plugin_settings_menu');

function asmp_add_action_links($links)
{
    $action_link = array(
        '<a href="' . admin_url('/options-general.php?page=asmp') . '">Settings</a>',
    );
    return array_merge($links, $action_link);
}
add_filter('plugin_action_links_' . plugin_basename(__FILE__), 'asmp_add_action_links');

function asmp_register_settings()
{
    register_setting('asmp-settings-group', 'original_lang');
    register_setting('asmp-settings-group', 'languages');
    register_setting('asmp-settings-group', 'lang_shorts');
    register_setting('asmp-settings-group', 'menu_action');
    register_setting('asmp-settings-group', 'ut_post_action');
    register_setting('asmp-settings-group', 't_indicator');
}

function asmp_settings_page()
{
    if (get_option('original_lang')) {
        $original = esc_attr(get_option('original_lang'));
    } else {
        $original = esc_attr(get_bloginfo("language"));
    }
    $languages = get_option('languages');
    $lang_shorts = get_option('lang_shorts');
    $t_indicator = esc_attr(get_option('t_indicator'));
    $ut_post_action = esc_attr(get_option('ut_post_action'));
    $menu_action = esc_attr(get_option('menu_action'));

    echo '<div class="wrap">';
    echo '<h2>A Simple Multilanguage Plugin settings</h2>';
    echo '<form method="post" action="options.php">';

    settings_fields('asmp-settings-group');

    echo '<h3 class="langh3">Languages</h3>';
    echo '<p>Display name is used in language selector widget and shortcode as well as post/page edit screen.<br />';
    echo 'Short is used in URLs i.e <i>http://www.yoursite.com/mypage/?lang=eng</i>.</p>';
    echo '<table id="langtable" class="langtable">';
    echo '<tr><th>Display name</th><th>Short</th></tr>';
    echo '<tr><td><input type="text" name="original_lang" value="' . $original . '" required /></td><td><i>default</i></td></tr>';
    $i = 0;
    if (!empty($languages)) {
        foreach ($languages as $i => $langname) {
            echo '<tr id="lang' . $i . '">';
            echo '<td><input type="text" name="languages[' . $i . ']" value="' . $langname . '" required /></td>';
            echo '<td><input type="text" name="lang_shorts[' . $i . ']" size="4" value="' . $lang_shorts[$i] . '" required /></td>';
            echo '<td><a href="#" class="no_underline" onclick="getElementById(\'lang' . $i . '\').remove()"><span class="dashicons dashicons-no-alt"></span></a></td>';
            echo '</tr>';
        }
    }
    echo '</table>';
    echo '<p><a href="#" class="no_underline" onclick="addRow()"><span class="dashicons dashicons-plus"></span>Add language</a></p>';

    echo '<h3 class="langh3">Additional settings</h3>';
    echo '<table class="langtable">';

    echo '<tr><th>Translation indicator in post list</th><td><select name="t_indicator">';
    echo '<option value="both"' . ($t_indicator == 'both' ? ' selected' : '') . '>Posts & pages</option>';
    echo '<option value="none"' . ($t_indicator == 'none' ? ' selected' : '') . '>Do not display</option>';
    echo '<option value="post"' . ($t_indicator == 'post' ? ' selected' : '') . '>Posts</option>';
    echo '<option value="page"' . ($t_indicator == 'page' ? ' selected' : '') . '>Pages</option>';
    echo '</select></td></tr>';

    echo '<tr><th>Untranslated post and pages</th><td><select name="ut_post_action">';
    echo '<option value="1"' . ($ut_post_action == '1' ? ' selected' : '') . '>Hide from lists and restrict direct access</option>';
    echo '<option value="2"' . ($ut_post_action == '2' ? ' selected' : '') . '>Display in default language</option>';
    echo '</select></td></tr>';

    echo '<tr><th>Untranslated menu items</th><td><select name="menu_action">';
    echo '<option value="1"' . ($menu_action == '1' ? ' selected' : '') . '>Remove from menu</option>';
    echo '<option value="2"' . ($menu_action == '2' ? ' selected' : '') . '>Display and link in default language</option>';
    echo '</select></td></tr>';

    echo '</table>';
    echo '<p><i>Please note that if you translate the title, but not the content, the post/page will still be marked as translated.</i></p>';

    echo '<h3 class="langh3">Language selector shortcode</h3>';
    echo '<p>Use <code>[asmp-switcher]</code> to add language selector to pages and posts. Use <code>do_shortcode(\'[asmp-switcher]\');</code> for theme and template files.</p>';
    echo '<h4 style="margin-bottom:0;">Attributes (optional):</h4><table class="langtable"><tr><th>separator</th><td>Separates languages in list. Default = |</td></tr>';
    echo '<tr><th>ex_current</th><td>If true, removes current language from list. Default: <i>false</i></td></tr></table>';
    
    submit_button();

    echo <<<EOT
        <script>
            var k = $i;
            function addRow() {
                k++;
                var table = document.getElementById("langtable");
                var row = table.insertRow(-1);
                row.id = "lang"+k;
                var cell1 = row.insertCell(0);
                var cell2 = row.insertCell(1);
                var cell3 = row.insertCell(2);
                cell1.innerHTML = '<input type="text" name="languages['+k+']" required />';
                cell2.innerHTML = '<input type="text" name="lang_shorts['+k+']" size="4" required />';
                cell3.innerHTML = '<a href="#" class="no_underline" onclick="getElementById(\'lang'+k+'\').remove()"><span class="dashicons dashicons-no-alt"></span></a>';
            }
            </script>
EOT;
    echo '</form></div>';
}

function asmp_add_language_meta_boxes()
{
    $languages = get_option('languages');
    $lang_shorts = get_option('lang_shorts');
    foreach ($languages as $i => $langname) {
        add_meta_box($lang_shorts[$i], $langname, 'asmp_lang_callback', 'post', 'advanced', 'default', array($lang_shorts[$i]));
        add_meta_box($lang_shorts[$i], $langname, 'asmp_lang_callback', 'page', 'advanced', 'default', array($lang_shorts[$i]));
    }
}
add_action('add_meta_boxes', 'asmp_add_language_meta_boxes');

function asmp_lang_callback($post, $callback_args)
{
    $lang = $callback_args['args'][0];
    wp_nonce_field('save_' . $lang . '_content', $lang . '_content_nonce');
    echo '<input class="langinput" type="text" name="';
    echo $lang . '-title" value="';
    echo esc_attr(get_post_meta($post->ID, $lang . '-title', true));
    echo '" id="' . $lang . '-title" placeholder="' . __('Enter title here') . '" spellcheck="true" autocomplete="off" >';
    wp_editor(get_post_meta($post->ID, $lang . '-content', true), 'edit' . $lang);
}

function asmp_save_lang_content($post_id)
{
    $lang_shorts = get_option('lang_shorts');
    foreach ($lang_shorts as $lang) {
        if (!isset($_POST[$lang . '_content_nonce'])
            || !wp_verify_nonce($_POST[$lang . '_content_nonce'], 'save_' . $lang . '_content')
            || (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE)
        ) {
            return;
        }
        if (isset($_POST[$lang . '-title'])) {
            update_post_meta($post_id, $lang . '-title', $_POST[$lang . '-title']);
        }
        if (isset($_POST['edit' . $lang])) {
            update_post_meta($post_id, $lang . '-content', $_POST['edit' . $lang]);
        }
    }
}
add_action('save_post', 'asmp_save_lang_content');

function asmp_custom_css()
{
    echo '<style>
        .langinput {
            width:100%;
            padding: 3px 8px;
            font-size: 1.7em;
            height: 1.7em;
            margin-bottom: 30px;
        }
        .no_underline {text-decoration:none;}
        .langtable {text-align:left;}
        .langh3 {
            border-bottom: 1px solid lightgray;
            margin-top: 20px;
        }
        .langshortcode {
            width: 200px;
            font-style: italic;
        }';
    $languages = get_option('lang_shorts');
    if (!empty($languages)) {
        foreach ($languages as $lang) {
            echo '.column-' . $lang . '{width: 40px;}';
        }
    }
    echo '</style>';
}
add_action('admin_head', 'asmp_custom_css');

function asmp_is_real_lang($lang)
{
    $lang_shorts = get_option('lang_shorts');
    if (!is_admin() && !empty($lang_shorts) && in_array($lang, $lang_shorts)) {
        return true;
    } else {
        return false;
    }
}

function asmp_menu_lang_filter($items)
{
    $lang = $_GET['lang'];
    if (asmp_is_real_lang($lang)) {
        foreach ($items as $key => $item) {
            $title = esc_attr(get_post_meta($item->object_id, $lang . '-title', true));
            if ($title != "") {
                $item->url = add_query_arg('lang', $lang, $item->url);
                $item->title = $title;
            } else if (get_option('menu_action') == '1') {
                unset($items[$key]);
            }
        }
    }
    return $items;
}
add_filter('wp_nav_menu_objects', 'asmp_menu_lang_filter');

function asmp_title_lang_filter($title, $id = null)
{
    $lang = $_GET['lang'];
    $langtitle = esc_attr(get_post_meta($id, $lang . '-title', true));
    if (asmp_is_real_lang($lang) && $langtitle != "") {
        return $langtitle;
    } else {
        return $title;
    }
}
add_filter('the_title', 'asmp_title_lang_filter', 10, 2);

function asmp_content_lang_filter($content)
{
    $lang = $_GET['lang'];
    $langcontent = apply_filters('meta_content', get_post_meta(get_the_id(), $lang . '-content', true));
    if (asmp_is_real_lang($lang) && $langcontent != "") {
        return $langcontent;
    } else {
        return $content;
    }
}
add_filter('the_content', 'asmp_content_lang_filter', 10, 2);

add_filter('meta_content', 'wptexturize');
add_filter('meta_content', 'convert_smilies');
add_filter('meta_content', 'convert_chars');
add_filter('meta_content', 'wpautop');
add_filter('meta_content', 'shortcode_unautop');
add_filter('meta_content', 'prepend_attachment');

function asmp_url_lang_filter($url)
{
    $lang = $_GET['lang'];
    if (asmp_is_real_lang($lang)) {
        return add_query_arg('lang', $lang, $url);
    } else {
        return $url;
    }
}
add_filter('the_permalink', 'asmp_url_lang_filter');

function asmp_exclude_posts_from_loop($query)
{
    $lang  = $_GET['lang'];
    $title = $lang . '-title';
    if (get_option('ut_post_action') == '1' && asmp_is_real_lang($lang) && $query->is_main_query()) {
        $meta_query = array(array(
            'key'     => $title,
            'value'   => '',
            'compare' => '!=',
        ));
        $query->set('meta_query', $meta_query);
    }
}
add_action('pre_get_posts', 'asmp_exclude_posts_from_loop');

function asmp_idicator($defaults)
{
    $languages = get_option('lang_shorts');
    if (!empty($languages)) {
        foreach ($languages as $lang) {
            $defaults[$lang] = strtoupper($lang);
        }
    }
    return $defaults;
}

function asmp_indicator_columns($column, $post_id)
{
    $languages = get_option('lang_shorts');
    if (!empty($languages)) {
        if (in_array($column, $languages) && get_post_meta($post_id, $column . '-title', true)) {
            echo '✔';
        }
    }
}

if (get_option('t_indicator') == 'page' || get_option('t_indicator') == 'both') {
    add_filter('manage_pages_columns', 'asmp_idicator');
    add_action('manage_pages_custom_column', 'asmp_indicator_columns', 10, 2);
}

if (get_option('t_indicator') == 'post' || get_option('t_indicator') == 'both') {
    add_filter('manage_posts_columns', 'asmp_idicator');
    add_action('manage_posts_custom_column', 'asmp_indicator_columns', 10, 2);
}

function asmp_langswitch_shortcode($atts)
{
    $atts = shortcode_atts(array(
        'separator'  => '|',
        'ex_current' => false,
    ), $atts, 'asmp-switcher');
    $html = '';
    $separator = ' ' . $atts['separator'] . ' ';
    if (get_option('original_lang')) {
        global $wp;
        $original = esc_attr(get_option('original_lang'));
        $languages = get_option('languages');
        $lang = $_GET['lang'];
        $current_url = home_url(add_query_arg(array(), $wp->request));
        $orghidden = false;
        if (asmp_is_real_lang($lang)) {
            $html .= '<a href="' . $current_url . '">' . $original . '</a>';
        } else if ($atts['ex_current'] == false) {
            $html .= $original;
        } else {
            $orghidden = true;
        }
        if (!empty($languages)) {
            $lang_shorts = get_option('lang_shorts');
            $langlinks   = array();
            foreach ($languages as $i => $langname) {
                if ($lang != $lang_shorts[$i]) {
                    array_push($langlinks, '<a href="' . $current_url . '?lang=' . $lang_shorts[$i] . '">' . $langname . '</a>');
                } else if ($atts['ex_current'] == false) {
                    array_push($langlinks, $langname);
                }
            }
            if ($orghidden == false && !empty($langlinks)) {
                $html .= $separator;
            }
            $html .= implode($separator, $langlinks);
        }
    }
    return $html;
}
add_shortcode('asmp-switcher', 'asmp_langswitch_shortcode');

function asmp_register_widgets()
{
    register_widget('ASMPLanguageModule');
}
add_action('widgets_init', 'asmp_register_widgets');

class ASMPLanguageModule extends WP_Widget
{
    public function __construct()
    {
        $widget_ops = array(
            'classname' => 'asmp_langswitch_widget',
        );
        parent::__construct(false, 'ASMP language switcher', $widget_ops);
    }

    public function widget($args, $instance)
    {
        echo $args['before_widget'];
        echo do_shortcode('[asmp-switcher separator="' . $instance['separator'] . '" ex_current="' . $instance['ex_current'] . '"]');
        echo $args['after_widget'];
    }

    public function update($new_instance, $old_instance)
    {
        $instance = array();
        $instance['separator'] = (!empty($new_instance['separator'])) ? strip_tags($new_instance['separator']) : '';
        $instance['ex_current'] = (!empty($new_instance['ex_current'])) ? strip_tags($new_instance['ex_current']) : '';
        return $instance;
    }

    public function form($instance)
    {
        if (get_option('original_lang')) {
            if (isset($instance['separator'])) {
                $separator = $instance['separator'];
            } else {
                $separator = '|';
            }
            if (isset($instance['ex_current'])) {
                $ex_current = $instance['ex_current'];
            } else {
                $ex_current = 0;
            }
            echo '<p><table><tr><td style="width:110px;">Separator</td><td>';
            echo '<input style="width:50px;" id="' . $this->get_field_id('separator') . '" name="' . $this->get_field_name('separator') . '" type="text" value="' . esc_attr($separator) . '">';
            echo '</td></tr><tr><td>Exclude current</td><td>';
            echo '<select style="width:50px;" id="' . $this->get_field_id('ex_current') . '" name="' . $this->get_field_name('ex_current') . '">';
            echo '<option value="0">No</option><option value="1"' . ($ex_current == 1 ? ' selected' : '') . '>Yes</option></select>';
            echo '</td></tr></table></p>';
        }
    }
}
