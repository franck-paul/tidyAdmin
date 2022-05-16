<?php
/**
 * @brief tidyAdmin, a plugin for Dotclear 2
 *
 * @package Dotclear
 * @subpackage Plugins
 *
 * @author Franck Paul
 *
 * @copyright Franck Paul carnet.franck.paul@gmail.com
 * @copyright GPL-2.0 https://www.gnu.org/licenses/gpl-2.0.html
 */
if (!defined('DC_CONTEXT_ADMIN')) {
    return;
}

$part = '';

// Get plugin var path

$var_path = path::real(DC_VAR) . '/plugins/tidyAdmin/';
files::makeDir($var_path, true);

// Get current content of JS file

$js_file        = $var_path . 'admin.js';
$js_backup_file = $var_path . 'admin-backup.js';
if (!file_exists($js_file)) {
    @touch($js_file);
}
$js_content  = @file_get_contents($js_file);
$js_writable = file_exists($js_file) && is_writable($js_file) && is_writable(dirname($js_file));

// Get demo JS content

$js_demo_file    = __DIR__ . '/js/admin-demo.js';
$js_demo_content = @file_get_contents($js_demo_file);

// Save options
if (!empty($_POST['opts'])) {
    $core->auth->user_prefs->addWorkspace('interface');

    $core->auth->user_prefs->interface->put('minidcicon', !empty($_POST['user_ui_minidcicon']), 'boolean');

    $core->auth->user_prefs->interface->put('movesearchmenu', !empty($_POST['user_ui_movesearchmenu']), 'boolean');
    $core->auth->user_prefs->interface->put('clonesearchmedia', !empty($_POST['user_ui_clonesearchmedia']), 'boolean');

    dcPage::addSuccessNotice(__('Options updated'));
    http::redirect($p_url . '&part=options');
}

if (!empty($_POST['js'])) {
    // Try to write JS file
    try {
        # Write file
        $js_old_content = $js_content;
        $js_content     = $_POST['js_content'] . "\n";
        $fp             = @fopen($js_file, 'wb');
        if (!$fp) {
            throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), $js_file));
        }
        fwrite($fp, $js_content);
        fclose($fp);
        if ($fp = @fopen($js_backup_file, 'wb')) {
            // Backup file
            fwrite($fp, $js_old_content);
            fclose($fp);
        }
        dcPage::addSuccessNotice(__('JS supplemental script updated'));
        http::redirect($p_url . '&part=js-editor');
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
}

// Get current content of CSS file

$css_file        = $var_path . 'admin.css';
$css_backup_file = $var_path . 'admin-backup.css';
if (!file_exists($css_file)) {
    touch($css_file);
}
$css_content  = @file_get_contents($css_file);
$css_writable = file_exists($css_file) && is_writable($css_file) && is_writable(dirname($css_file));

// Get demo CSS content

$css_demo_file    = __DIR__ . '/css/admin-demo.css';
$css_demo_content = @file_get_contents($css_demo_file);

if (!empty($_POST['css'])) {
    // Try to write CSS rule
    try {
        # Write file
        $css_old_content = $css_content;
        $css_content     = $_POST['css_content'] . "\n";
        $fp              = @fopen($css_file, 'wb');
        if (!$fp) {
            throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), $css_file));
        }
        fwrite($fp, $css_content);
        fclose($fp);
        if ($fp = @fopen($css_backup_file, 'wb')) {
            // Backup file
            fwrite($fp, $css_old_content);
            fclose($fp);
        }
        dcPage::addSuccessNotice(__('CSS supplemental rules updated'));
        http::redirect($p_url . '&part=css-editor');
    } catch (Exception $e) {
        $core->error->add($e->getMessage());
    }
}

if ($part == '') {
    if (!empty($_GET['part'])) {
        if (in_array($_GET['part'], ['options', 'css-editor', 'js-editor'])) {
            $part = $_GET['part'];
        }
    }
}
if ($part == '') {
    $part = 'options';
}

# Get interface setting
$core->auth->user_prefs->addWorkspace('interface');
$user_ui_colorsyntax       = $core->auth->user_prefs->interface->colorsyntax;
$user_ui_colorsyntax_theme = '';
if ($user_ui_colorsyntax) {
    $user_ui_colorsyntax_theme = $core->auth->user_prefs->interface->colorsyntax_theme ?: 'default';
}
$user_ui_minidcicon       = $core->auth->user_prefs->interface->minidcicon;
$user_ui_movesearchmenu   = $core->auth->user_prefs->interface->movesearchmenu;
$user_ui_clonesearchmedia = $core->auth->user_prefs->interface->clonesearchmedia;
?>

<html>
<head>
    <title><?php echo __('Tidy administration settings'); ?></title>
<?php
echo
dcPage::jsModal() .
dcPage::jsConfirmClose('css-form') .
dcPage::jsPageTabs($part);
if ($user_ui_colorsyntax) {
    echo
    dcPage::jsLoadCodeMirror($user_ui_colorsyntax_theme, false, ['css', 'javascript']);
}
echo
dcPage::cssModuleLoad('tidyAdmin/css/style.css', 'screen', $core->getVersion('tidyAdmin'));
?>
</head>

<body>
<?php
echo dcPage::breadcrumb(
    [
        __('System')                       => '',
        __('Tidy administration settings') => '',
    ]
);
echo dcPage::notices();
?>

<div id="options"  class="multi-part" title="<?php echo __('Options'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Options'); ?></h3>
<?php
echo
'<form id="options" action="' . $p_url . '" method="post">' .
'<p><label for="user_ui_minidcicon" class="classic">' .
form::checkbox('user_ui_minidcicon', 1, $user_ui_minidcicon) . ' ' . __('Use mini Dotclear icon (top left) in header') . '</label></p>' .
'<p><label for="user_ui_movesearchmenu" class="classic">' .
form::checkbox('user_ui_movesearchmenu', 1, $user_ui_movesearchmenu) . ' ' . __('Move the search form (main menu) in header') . '</label></p>' .
'<p><label for="user_ui_clonesearchmedia" class="classic">' .
form::checkbox('user_ui_clonesearchmedia', 1, $user_ui_clonesearchmedia) . ' ' . __('Clone the media manager search input in always visible area') . '</label></p>';

echo
'<p><input type="submit" name="opts" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
$core->formNonce() .
'</p>';
?>
</div>

<div id="css-editor"  class="multi-part" title="<?php echo __('Supplemental CSS editor'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Supplemental CSS editor'); ?></h3>
<?php
echo
'<form id="css-form" action="' . $p_url . '" method="post">' .
'<p>' . form::textarea('css_content', 72, 25, html::escapeHTML($css_content), 'maximal', '', !$css_writable) . '</p>';
if ($css_writable) {
    echo
    '<p><input type="submit" name="css" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
    $core->formNonce() .
        '</p>';
} else {
    echo '<p>' . sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), $css_file) . '</p>';
}
echo
'<p class="info">' . __('Note: this supplemental CSS rules will surcharge the default CSS rules.') . '</p>' .

// Display demo CSS content
'<p>' . __('Sample CSS:') . '</p>' .
'<p>' . form::textarea('css_demo_content', 72, 25, html::escapeHTML($css_demo_content), 'maximal', '', false, 'readonly="true"') . '</p>' .

    '</form>';
?>
</div>

<div id="js-editor"  class="multi-part" title="<?php echo __('Supplemental JS editor'); ?>">
<h3 class="out-of-screen-if-js"><?php echo __('Supplemental JS editor'); ?></h3>
<?php
echo
'<form id="js-form" action="' . $p_url . '" method="post">' .
'<p>' . form::textarea('js_content', 72, 25, html::escapeHTML($js_content), 'maximal', '', !$js_writable) . '</p>';
if ($js_writable) {
    echo
    '<p><input type="submit" name="js" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
    $core->formNonce() .
        '</p>';
} else {
    echo '<p>' . sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), $js_file) . '</p>';
}
echo
'<p class="info">' . __('Note: this supplemental JS script will surcharge the default JS scripts.') . '</p>' .

// Display demo JS content
'<p>' . __('Sample JS:') . '</p>' .
'<p>' . form::textarea('js_demo_content', 72, 25, html::escapeHTML($js_demo_content), 'maximal', '', false, 'readonly="true"') . '</p>' .

    '</form>';
?>
</div>

<?php
if ($user_ui_colorsyntax) {
    echo
    dcPage::jsRunCodeMirror(
        [
            [
                'name'  => 'editor_css',
                'id'    => 'css_content',
                'mode'  => 'css',
                'theme' => $user_ui_colorsyntax_theme,
            ],
            [
                'name'  => 'editor_js',
                'id'    => 'js_content',
                'mode'  => 'javascript',
                'theme' => $user_ui_colorsyntax_theme,
            ],
        ]
    );
}
?>
</body>
</html>
