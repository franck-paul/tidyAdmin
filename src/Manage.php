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
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Dotclear\Helper\Html\Form\Checkbox;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Form;
use Dotclear\Helper\Html\Form\Label;
use Dotclear\Helper\Html\Form\Para;
use Dotclear\Helper\Html\Form\Submit;
use Dotclear\Helper\Html\Form\Text;
use Dotclear\Helper\Html\Form\Textarea;
use Dotclear\Helper\Html\Html;
use Dotclear\Helper\Network\Http;
use Exception;

//use form;

class Manage extends dcNsProcess
{
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        // Manageable only by super-admin
        static::$init = defined('DC_CONTEXT_ADMIN')
            && dcCore::app()->auth->isSuperAdmin()
            && My::phpCompliant();

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

        // Init stuff

        // Get plugin var path

        dcCore::app()->admin->var_path = dcUtils::path([Path::real(DC_VAR), 'plugins', 'tidyAdmin']) . DIRECTORY_SEPARATOR;
        Files::makeDir(dcCore::app()->admin->var_path, true);

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

        // Save options

        if (!empty($_POST['opts'])) {
            // Get interface setting
            $interface_pref = dcCore::app()->auth->user_prefs->interface;

            $interface_pref->put('minidcicon', !empty($_POST['user_ui_minidcicon']), 'boolean');
            $interface_pref->put('movesearchmenu', !empty($_POST['user_ui_movesearchmenu']), 'boolean');
            $interface_pref->put('clonesearchmedia', !empty($_POST['user_ui_clonesearchmedia']), 'boolean');
            $interface_pref->put('hovercollapser', !empty($_POST['user_ui_hovercollapser']), 'boolean');
            $interface_pref->put('pluginconfig', !empty($_POST['user_ui_pluginconfig']), 'boolean');

            dcPage::addSuccessNotice(__('Options updated'));
            Http::redirect(dcCore::app()->admin->getPageURL() . '&part=options');
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
                Http::redirect(dcCore::app()->admin->getPageURL() . '&part=js-editor');
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
                Http::redirect(dcCore::app()->admin->getPageURL() . '&part=css-editor');
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
                Http::redirect(dcCore::app()->admin->getPageURL() . '&part=po-editor');
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
        $interface_pref = dcCore::app()->auth->user_prefs->interface;

        $user_ui_colorsyntax       = $interface_pref->colorsyntax;
        $user_ui_colorsyntax_theme = '';
        if ($user_ui_colorsyntax) {
            $user_ui_colorsyntax_theme = $interface_pref->colorsyntax_theme ?: 'default';
        }
        $user_ui_minidcicon       = $interface_pref->minidcicon;
        $user_ui_movesearchmenu   = $interface_pref->movesearchmenu;
        $user_ui_clonesearchmedia = $interface_pref->clonesearchmedia;
        $user_ui_hovercollapser   = $interface_pref->hovercollapser;
        $user_ui_pluginconfig     = $interface_pref->pluginconfig;

        $head = dcPage::jsModal() .
        dcPage::jsConfirmClose('css-form') .
        dcPage::jsPageTabs(dcCore::app()->admin->part);
        if ($user_ui_colorsyntax) {
            $head .= dcPage::jsLoadCodeMirror($user_ui_colorsyntax_theme, false, ['css', 'javascript']);
        }
        $head .= dcPage::cssModuleLoad('tidyAdmin/css/style.css', 'screen', dcCore::app()->getVersion('tidyAdmin'));

        dcPage::openModule(__('Tidy administration settings'), $head);

        echo dcPage::breadcrumb(
            [
                __('System')                       => '',
                __('Tidy administration settings') => '',
            ]
        );
        echo dcPage::notices();

        // First tab (options)
        echo (new Div('options'))
            ->class('multi-part')
            ->title(__('Options'))
            ->items([
                (new Text('h3', __('Options')))
                ->class('out-of-screen-if-js'),
                (new Form('options-form'))
                ->action(dcCore::app()->admin->getPageURL())
                ->method('post')
                ->fields([
                    (new Para())->items([
                        (new Checkbox('user_ui_minidcicon', $user_ui_minidcicon))
                            ->value(1)
                            ->label((new Label(__('Use mini Dotclear icon (top left) in header'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('user_ui_movesearchmenu', $user_ui_movesearchmenu))
                            ->value(1)
                            ->label((new Label(__('Move the search form (main menu) in header'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('user_ui_clonesearchmedia', $user_ui_clonesearchmedia))
                            ->value(1)
                            ->label((new Label(__('Clone the media manager search input in always visible area'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('user_ui_hovercollapser', $user_ui_hovercollapser))
                            ->value(1)
                            ->label((new Label(__('Enabled mouse hover activation on collapser'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Checkbox('user_ui_pluginconfig', $user_ui_pluginconfig))
                            ->value(1)
                            ->label((new Label(__('Move plugin settings link to top of page'), Label::INSIDE_TEXT_AFTER))),
                    ]),
                    (new Para())->items([
                        (new Submit(['opts'], __('Save')))
                            ->accesskey('s'),
                        dcCore::app()->formNonce(false),
                    ]),
                ]),
            ])
            ->render();

        // Second tab (User-defined CSS)
        echo (new Div('css-editor'))
            ->class('multi-part')
            ->title(__('Supplemental CSS editor'))
            ->items([
                (new Text('h3', __('Supplemental CSS editor')))
                    ->class('out-of-screen-if-js'),
                (new Form('css-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('css_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(dcCore::app()->admin->css_content))
                                ->class('maximal')
                                ->disable(!dcCore::app()->admin->css_writable),
                        ]),
                        (new Para())->items([
                            (dcCore::app()->admin->css_writable ?
                            (new Submit(['css'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->css_file)))),
                            dcCore::app()->formNonce(false),
                        ]),
                        (new Para())->items([
                            (new Text(null, __('Note: this supplemental CSS rules will surcharge the default CSS rules.'))),
                        ])
                            ->class('info'),
                        (new Para())->items([
                            (new Text(null, __('Sample CSS:'))),
                        ]),
                        (new Para())->items([
                            (new Textarea('css_demo_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML((string) dcCore::app()->admin->css_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        // Second tab (User-defined JS)
        echo (new Div('js-editor'))
            ->class('multi-part')
            ->title(__('Supplemental JS editor'))
            ->items([
                (new Text('h3', __('Supplemental JS editor')))
                    ->class('out-of-screen-if-js'),
                (new Form('js-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('js_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(dcCore::app()->admin->js_content))
                                ->class('maximal')
                                ->disable(!dcCore::app()->admin->js_writable),
                        ]),
                        (new Para())->items([
                            (dcCore::app()->admin->js_writable ?
                            (new Submit(['js'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->js_file)))),
                            dcCore::app()->formNonce(false),
                        ]),
                        (new Para())->items([
                            (new Text(null, __('Note: this supplemental JS script will surcharge the default JS scripts.'))),
                        ])
                            ->class('info'),
                        (new Para())->items([
                            (new Text(null, __('Sample JS:'))),
                        ]),
                        (new Para())->items([
                            (new Textarea('js_demo_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML((string) dcCore::app()->admin->js_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        // THIRD tab (User-defined PO)
        echo (new Div('po-editor'))
            ->class('multi-part')
            ->title(__('Supplemental PO editor'))
            ->items([
                (new Text('h3', __('Supplemental PO editor')))
                    ->class('out-of-screen-if-js'),
                (new Div(null, 'hr')),
                (new Form('po-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('po_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(dcCore::app()->admin->po_content))
                                ->class('maximal')
                                ->disable(!dcCore::app()->admin->po_writable),
                        ]),
                        (new Para())->items([
                            (dcCore::app()->admin->po_writable ?
                            (new Submit(['js'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), dcCore::app()->admin->po_file)))),
                            dcCore::app()->formNonce(false),
                        ]),
                        (new Para())->items([
                            (new Text(null, __('Note: this supplemental PO l10n will surcharge the default l10n.'))),
                        ])
                            ->class('info'),
                        (new Para())->items([
                            (new Text(null, __('Sample PO:'))),
                        ]),
                        (new Para())->items([
                            (new Textarea('po_demo_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML((string) dcCore::app()->admin->po_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

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

        dcPage::closeModule();
    }
}