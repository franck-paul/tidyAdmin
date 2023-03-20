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
declare(strict_types=1);

namespace Dotclear\Plugin\tidyAdmin;

use dcCore;
use dcNsProcess;
use dcPage;
use dcUtils;
use Exception;
use files;
use form;
use html;
use http;
use path;

class Manage extends dcNsProcess
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        // Get plugin var path

        dcCore::app()->admin->var_path = dcUtils::path([path::real(DC_VAR), 'plugins', 'tidyAdmin']) . DIRECTORY_SEPARATOR;
        files::makeDir(dcCore::app()->admin->var_path, true);

        dcCore::app()->admin->part = '';

        // Get demo content (js, css, po)

        dcCore::app()->admin->js_demo_content  = @file_get_contents(dcUtils::path([__DIR__,'..','js','admin-demo.js']));
        dcCore::app()->admin->css_demo_content = @file_get_contents(dcUtils::path([__DIR__,'..','css','admin-demo.css']));
        dcCore::app()->admin->po_demo_content  = @file_get_contents(dcUtils::path([__DIR__,'..','po','admin-demo.po']));

        // Get current content of JS file

        dcCore::app()->admin->js_file        = dcCore::app()->admin->var_path . 'admin.js';
        dcCore::app()->admin->js_backup_file = dcCore::app()->admin->var_path . 'admin-backup.js';
        if (!file_exists(dcCore::app()->admin->js_file)) {
            @touch(dcCore::app()->admin->js_file);
        }
        dcCore::app()->admin->js_content  = @file_get_contents(dcCore::app()->admin->js_file);
        dcCore::app()->admin->js_writable = file_exists(dcCore::app()->admin->js_file) && is_writable(dcCore::app()->admin->js_file) && is_writable(dirname(dcCore::app()->admin->js_file));

        // Get current content of CSS file

        dcCore::app()->admin->css_file        = dcCore::app()->admin->var_path . 'admin.css';
        dcCore::app()->admin->css_backup_file = dcCore::app()->admin->var_path . 'admin-backup.css';
        if (!file_exists(dcCore::app()->admin->css_file)) {
            touch(dcCore::app()->admin->css_file);
        }
        dcCore::app()->admin->css_content  = @file_get_contents(dcCore::app()->admin->css_file);
        dcCore::app()->admin->css_writable = file_exists(dcCore::app()->admin->css_file) && is_writable(dcCore::app()->admin->css_file) && is_writable(dirname(dcCore::app()->admin->css_file));

        // Get current content of PO file

        dcCore::app()->admin->po_file        = dcCore::app()->admin->var_path . 'admin.po';
        dcCore::app()->admin->po_backup_file = dcCore::app()->admin->var_path . 'admin-backup.po';
        if (!file_exists(dcCore::app()->admin->po_file)) {
            touch(dcCore::app()->admin->po_file);
        }
        dcCore::app()->admin->po_content  = @file_get_contents(dcCore::app()->admin->po_file);
        dcCore::app()->admin->po_writable = file_exists(dcCore::app()->admin->po_file) && is_writable(dcCore::app()->admin->po_file) && is_writable(dirname(dcCore::app()->admin->po_file));

        static::$init = true;

        return static::$init;
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!static::$init) {
            return false;
        }

        // Save options

        if (!empty($_POST['opts'])) {
            dcCore::app()->auth->user_prefs->interface->put('minidcicon', !empty($_POST['user_ui_minidcicon']), 'boolean');
            dcCore::app()->auth->user_prefs->interface->put('movesearchmenu', !empty($_POST['user_ui_movesearchmenu']), 'boolean');
            dcCore::app()->auth->user_prefs->interface->put('clonesearchmedia', !empty($_POST['user_ui_clonesearchmedia']), 'boolean');
            dcCore::app()->auth->user_prefs->interface->put('hovercollapser', !empty($_POST['user_ui_hovercollapser']), 'boolean');
            dcCore::app()->auth->user_prefs->interface->put('pluginconfig', !empty($_POST['user_ui_pluginconfig']), 'boolean');

            dcPage::addSuccessNotice(__('Options updated'));
            http::redirect(dcCore::app()->admin->getPageURL() . '&part=options');
        }

        if (!empty($_POST['js'])) {
            // Try to write JS file
            try {
                # Write file
                $js_old_content                  = dcCore::app()->admin->js_content;
                dcCore::app()->admin->js_content = $_POST['js_content'] . "\n";
                $fp                              = @fopen(dcCore::app()->admin->js_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->js_file));
                }
                fwrite($fp, dcCore::app()->admin->js_content);
                fclose($fp);
                if ($fp = @fopen(dcCore::app()->admin->js_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, $js_old_content);
                    fclose($fp);
                }
                dcPage::addSuccessNotice(__('JS supplemental script updated'));
                http::redirect(dcCore::app()->admin->getPageURL() . '&part=js-editor');
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST['css'])) {
            // Try to write CSS rule
            try {
                # Write file
                $css_old_content                  = dcCore::app()->admin->css_content;
                dcCore::app()->admin->css_content = $_POST['css_content'] . "\n";
                $fp                               = @fopen(dcCore::app()->admin->css_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->css_file));
                }
                fwrite($fp, dcCore::app()->admin->css_content);
                fclose($fp);
                if ($fp = @fopen(dcCore::app()->admin->css_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, $css_old_content);
                    fclose($fp);
                }
                dcPage::addSuccessNotice(__('CSS supplemental rules updated'));
                http::redirect(dcCore::app()->admin->getPageURL() . '&part=css-editor');
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST['po'])) {
            // Try to write PO content
            try {
                # Write file
                $po_old_content                  = dcCore::app()->admin->po_content;
                dcCore::app()->admin->po_content = $_POST['po_content'] . "\n";
                $fp                              = @fopen(dcCore::app()->admin->po_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->po_file));
                }
                fwrite($fp, dcCore::app()->admin->po_content);
                fclose($fp);
                if ($fp = @fopen(dcCore::app()->admin->po_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, $po_old_content);
                    fclose($fp);
                }
                dcPage::addSuccessNotice(__('PO supplemental l10n updated'));
                http::redirect(dcCore::app()->admin->getPageURL() . '&part=po-editor');
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_GET['part']) && in_array($_GET['part'], ['options', 'css-editor', 'js-editor', 'po-editor'])) {
            dcCore::app()->admin->part = $_GET['part'];
        }
        if (dcCore::app()->admin->part === '') {
            dcCore::app()->admin->part = 'options';
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!static::$init) {
            return;
        }

        // Get interface setting

        $user_ui_colorsyntax       = dcCore::app()->auth->user_prefs->interface->colorsyntax;
        $user_ui_colorsyntax_theme = '';
        if ($user_ui_colorsyntax) {
            $user_ui_colorsyntax_theme = dcCore::app()->auth->user_prefs->interface->colorsyntax_theme ?: 'default';
        }
        $user_ui_minidcicon       = dcCore::app()->auth->user_prefs->interface->minidcicon;
        $user_ui_movesearchmenu   = dcCore::app()->auth->user_prefs->interface->movesearchmenu;
        $user_ui_clonesearchmedia = dcCore::app()->auth->user_prefs->interface->clonesearchmedia;
        $user_ui_hovercollapser   = dcCore::app()->auth->user_prefs->interface->hovercollapser;
        $user_ui_pluginconfig     = dcCore::app()->auth->user_prefs->interface->pluginconfig;

        echo
        '<html>' .
        '<head>' .
        '    <title>' . __('Tidy administration settings') . '</title>';

        echo
        dcPage::jsModal() .
        dcPage::jsConfirmClose('css-form') .
        dcPage::jsPageTabs(dcCore::app()->admin->part);
        if ($user_ui_colorsyntax) {
            echo
            dcPage::jsLoadCodeMirror($user_ui_colorsyntax_theme, false, ['css', 'javascript']);
        }
        echo
        dcPage::cssModuleLoad('tidyAdmin/css/style.css', 'screen', dcCore::app()->getVersion('tidyAdmin'));

        echo
        '</head>' .
        '<body>';
        echo dcPage::breadcrumb(
            [
                __('System')                       => '',
                __('Tidy administration settings') => '',
            ]
        );
        echo dcPage::notices();

        echo
        '<div id="options"  class="multi-part" title="' . __('Options') . '">' .
        '<h3 class="out-of-screen-if-js">' . __('Options') . '</h3>';

        echo
        '<form id="options" action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
        '<p><label for="user_ui_minidcicon" class="classic">' .
        form::checkbox('user_ui_minidcicon', 1, $user_ui_minidcicon) . ' ' . __('Use mini Dotclear icon (top left) in header') . '</label></p>' .
        '<p><label for="user_ui_movesearchmenu" class="classic">' .
        form::checkbox('user_ui_movesearchmenu', 1, $user_ui_movesearchmenu) . ' ' . __('Move the search form (main menu) in header') . '</label></p>' .
        '<p><label for="user_ui_clonesearchmedia" class="classic">' .
        form::checkbox('user_ui_clonesearchmedia', 1, $user_ui_clonesearchmedia) . ' ' . __('Clone the media manager search input in always visible area') . '</label></p>' .
        '<p><label for="user_ui_hovercollapser" class="classic">' .
        form::checkbox('user_ui_hovercollapser', 1, $user_ui_hovercollapser) . ' ' . __('Enabled mouse hover activation on collapser') . '</label></p>' .
        '<p><label for="user_ui_pluginconfig" class="classic">' .
        form::checkbox('user_ui_pluginconfig', 1, $user_ui_pluginconfig) . ' ' . __('Move plugin settings link to top of page') . '</label></p>';

        echo
        '<p><input type="submit" name="opts" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
        dcCore::app()->formNonce() .
        '</p>';

        echo
        '</div>' .

        '<div id="css-editor"  class="multi-part" title="' . __('Supplemental CSS editor') . '">' .
        '<h3 class="out-of-screen-if-js">' . __('Supplemental CSS editor') . '</h3>';

        echo
        '<form id="css-form" action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
        '<p>' . form::textarea('css_content', 72, 25, html::escapeHTML(dcCore::app()->admin->css_content), 'maximal', '', !dcCore::app()->admin->css_writable) . '</p>';
        if (dcCore::app()->admin->css_writable) {
            echo
            '<p><input type="submit" name="css" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
            dcCore::app()->formNonce() .
                '</p>';
        } else {
            echo '<p>' . sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->css_file) . '</p>';
        }
        echo
        '<p class="info">' . __('Note: this supplemental CSS rules will surcharge the default CSS rules.') . '</p>' .

        // Display demo CSS content
        '<p>' . __('Sample CSS:') . '</p>' .
        '<p>' . form::textarea('css_demo_content', 72, 25, html::escapeHTML((string) dcCore::app()->admin->css_demo_content), 'maximal', '', false, 'readonly="true"') . '</p>' .

            '</form>';

        echo
        '</div>' .

        '<div id="js-editor"  class="multi-part" title="' . __('Supplemental JS editor') . '">' .
        '<h3 class="out-of-screen-if-js">' . __('Supplemental JS editor') . '</h3>';

        echo
        '<form id="js-form" action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
        '<p>' . form::textarea('js_content', 72, 25, html::escapeHTML(dcCore::app()->admin->js_content), 'maximal', '', !dcCore::app()->admin->js_writable) . '</p>';
        if (dcCore::app()->admin->js_writable) {
            echo
            '<p><input type="submit" name="js" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
            dcCore::app()->formNonce() .
                '</p>';
        } else {
            echo '<p>' . sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->js_file) . '</p>';
        }
        echo
        '<p class="info">' . __('Note: this supplemental JS script will surcharge the default JS scripts.') . '</p>' .

        // Display demo JS content
        '<p>' . __('Sample JS:') . '</p>' .
        '<p>' . form::textarea('js_demo_content', 72, 25, html::escapeHTML((string) dcCore::app()->admin->js_demo_content), 'maximal', '', false, 'readonly="true"') . '</p>' .

            '</form>';

        echo
        '</div>' .

        '<div id="po-editor"  class="multi-part" title="' . __('Supplemental PO editor') . '">' .
        '<h3 class="out-of-screen-if-js">' . __('Supplemental PO editor') . '</h3>';

        echo
        '<form id="po-form" action="' . dcCore::app()->admin->getPageURL() . '" method="post">' .
        '<p>' . form::textarea('po_content', 72, 25, html::escapeHTML(dcCore::app()->admin->po_content), 'maximal', '', !dcCore::app()->admin->po_writable) . '</p>';
        if (dcCore::app()->admin->po_writable) {
            echo
            '<p><input type="submit" name="po" value="' . __('Save') . ' (s)" accesskey="s" /> ' .
            dcCore::app()->formNonce() .
                '</p>';
        } else {
            echo '<p>' . sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->css_file) . '</p>';
        }
        echo
        '<p class="info">' . __('Note: this supplemental PO l10n will surcharge the default l10n.') . '</p>' .

        // Display demo PO content
        '<p>' . __('Sample PO:') . '</p>' .
        '<p>' . form::textarea('po_demo_content', 72, 25, html::escapeHTML((string) dcCore::app()->admin->po_demo_content), 'maximal', '', false, 'readonly="true"') . '</p>' .

            '</form>';

        echo
        '</div>';

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
                    [
                        'name'  => 'editor_po',
                        'id'    => 'po_content',
                        'mode'  => 'text/plain',
                        'theme' => $user_ui_colorsyntax_theme,
                    ],
                ]
            );
        }

        echo
    '</body>' .
'</html>';
    }
}
