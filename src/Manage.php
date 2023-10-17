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
use dcUtils;
use Dotclear\Core\Backend\Notices;
use Dotclear\Core\Backend\Page;
use Dotclear\Core\Process;
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
use Exception;

class Manage extends Process
{
    private static string $var_path          = '';
    private static string $part              = '';
    private static string $js_demo_content   = '';
    private static string $css_demo_content  = '';
    private static string $po_demo_content   = '';
    private static string $html_demo_content = '';
    private static string $js_file           = '';
    private static string $js_backup_file    = '';
    private static string $js_content        = '';
    private static bool $js_writable         = false;
    private static string $css_file          = '';
    private static string $css_backup_file   = '';
    private static string $css_content       = '';
    private static bool $css_writable        = false;
    private static string $po_file           = '';
    private static string $po_backup_file    = '';
    private static string $po_content        = '';
    private static bool $po_writable         = false;
    private static string $html_file         = '';
    private static string $html_backup_file  = '';
    private static string $html_content      = '';
    private static bool $html_writable       = false;
    /**
     * Initializes the page.
     */
    public static function init(): bool
    {
        // Manageable only by super-admin
        return self::status(My::checkContext(My::MANAGE));
    }

    /**
     * Processes the request(s).
     */
    public static function process(): bool
    {
        if (!self::status()) {
            return false;
        }

        // Init stuff

        // Get plugin var path

        self::$var_path = Path::reduce([(string) Path::real(DC_VAR), 'plugins', My::id()]) . DIRECTORY_SEPARATOR;
        Files::makeDir(self::$var_path, true);

        self::$part = '';

        // Get demo content (js, css, po)

        self::$js_demo_content   = (string) @file_get_contents(dcUtils::path([__DIR__,'..','demo','admin.js']));
        self::$css_demo_content  = (string) @file_get_contents(dcUtils::path([__DIR__,'..','demo','admin.css']));
        self::$po_demo_content   = (string) @file_get_contents(dcUtils::path([__DIR__,'..','demo','admin.po']));
        self::$html_demo_content = (string) @file_get_contents(dcUtils::path([__DIR__,'..','demo','admin.html']));

        // Get current content of JS file

        self::$js_file        = self::$var_path . 'admin.js';
        self::$js_backup_file = self::$var_path . 'admin-backup.js';
        if (!file_exists(self::$js_file)) {
            @touch(self::$js_file);
        }
        self::$js_content  = (string) @file_get_contents(self::$js_file);
        self::$js_writable = file_exists(self::$js_file) && is_writable(self::$js_file) && is_writable(dirname(self::$js_file));

        // Get current content of CSS file

        self::$css_file        = self::$var_path . 'admin.css';
        self::$css_backup_file = self::$var_path . 'admin-backup.css';
        if (!file_exists(self::$css_file)) {
            touch(self::$css_file);
        }
        self::$css_content  = (string) @file_get_contents(self::$css_file);
        self::$css_writable = file_exists(self::$css_file) && is_writable(self::$css_file) && is_writable(dirname(self::$css_file));

        // Get current content of PO file

        self::$po_file        = self::$var_path . 'admin.po';
        self::$po_backup_file = self::$var_path . 'admin-backup.po';
        if (!file_exists(self::$po_file)) {
            touch(self::$po_file);
        }
        self::$po_content  = (string) @file_get_contents(self::$po_file);
        self::$po_writable = file_exists(self::$po_file) && is_writable(self::$po_file) && is_writable(dirname(self::$po_file));

        // Get current content of HTML file

        self::$html_file        = self::$var_path . 'admin.html';
        self::$html_backup_file = self::$var_path . 'admin-backup.html';
        if (!file_exists(self::$html_file)) {
            touch(self::$html_file);
        }
        self::$html_content  = (string) @file_get_contents(self::$html_file);
        self::$html_writable = file_exists(self::$html_file) && is_writable(self::$html_file) && is_writable(dirname(self::$html_file));

        // Save options

        if (!empty($_POST['opts'])) {
            // Get interface setting
            $interface_pref = dcCore::app()->auth->user_prefs->interface;

            $interface_pref->put('minidcicon', !empty($_POST['user_ui_minidcicon']), 'boolean');
            $interface_pref->put('movesearchmenu', !empty($_POST['user_ui_movesearchmenu']), 'boolean');
            $interface_pref->put('clonesearchmedia', !empty($_POST['user_ui_clonesearchmedia']), 'boolean');
            $interface_pref->put('hovercollapser', !empty($_POST['user_ui_hovercollapser']), 'boolean');
            $interface_pref->put('pluginconfig', !empty($_POST['user_ui_pluginconfig']), 'boolean');

            Notices::addSuccessNotice(__('Options updated'));
            dcCore::app()->adminurl->redirect('admin.plugin.' . My::id(), [
                'part' => 'options',
            ]);
        }

        if (!empty($_POST['js'])) {
            // Try to write JS file
            try {
                # Write file
                $js_old_content   = self::$js_content;
                self::$js_content = $_POST['js_content'] . "\n";
                $fp               = @fopen(self::$js_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$js_file));
                }
                fwrite($fp, self::$js_content);
                fclose($fp);
                if ($fp = @fopen(self::$js_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, (string) $js_old_content);
                    fclose($fp);
                }
                Notices::addSuccessNotice(__('JS supplemental script updated'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id(), [
                    'part' => 'js-editor',
                ]);
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST['css'])) {
            // Try to write CSS rule
            try {
                # Write file
                $css_old_content   = self::$css_content;
                self::$css_content = $_POST['css_content'] . "\n";
                $fp                = @fopen(self::$css_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$css_file));
                }
                fwrite($fp, self::$css_content);
                fclose($fp);
                if ($fp = @fopen(self::$css_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, (string) $css_old_content);
                    fclose($fp);
                }
                Notices::addSuccessNotice(__('CSS supplemental rules updated'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id(), [
                    'part' => 'css-editor',
                ]);
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST['po'])) {
            // Try to write PO content
            try {
                # Write file
                $po_old_content   = self::$po_content;
                self::$po_content = $_POST['po_content'] . "\n";
                $fp               = @fopen(self::$po_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$po_file));
                }
                fwrite($fp, self::$po_content);
                fclose($fp);
                if ($fp = @fopen(self::$po_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, (string) $po_old_content);
                    fclose($fp);
                }
                Notices::addSuccessNotice(__('PO supplemental l10n updated'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id(), [
                    'part' => 'po-editor',
                ]);
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_POST['html'])) {
            // Try to write HTML head directives content
            try {
                # Write file
                $html_old_content   = self::$html_content;
                self::$html_content = $_POST['html_content'] . "\n";
                $fp                 = @fopen(self::$html_file, 'wb');
                if (!$fp) {
                    throw new Exception(sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$html_file));
                }
                fwrite($fp, self::$html_content);
                fclose($fp);
                if ($fp = fopen(self::$html_backup_file, 'wb')) {
                    // Backup file
                    fwrite($fp, (string) $html_old_content);
                    fclose($fp);
                }
                Notices::addSuccessNotice(__('HTML head supplemental directives updated'));
                dcCore::app()->adminurl->redirect('admin.plugin.' . My::id(), [
                    'part' => 'html-editor',
                ]);
            } catch (Exception $e) {
                dcCore::app()->error->add($e->getMessage());
            }
        }

        if (!empty($_GET['part']) && in_array($_GET['part'], ['options', 'css-editor', 'js-editor', 'po-editor', 'html-editor'])) {
            self::$part = $_GET['part'];
        }
        if (self::$part === '') {
            self::$part = 'options';
        }

        return true;
    }

    /**
     * Renders the page.
     */
    public static function render(): void
    {
        if (!self::status()) {
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

        $head = Page::jsModal() .
        Page::jsConfirmClose('css-form') .
        Page::jsPageTabs(self::$part);
        if ($user_ui_colorsyntax) {
            $head .= Page::jsLoadCodeMirror($user_ui_colorsyntax_theme);
        }
        $head .= My::cssLoad('style.css');

        Page::openModule(My::name(), $head);

        echo Page::breadcrumb(
            [
                __('System')                       => '',
                __('Tidy administration settings') => '',
            ]
        );
        echo Notices::getNotices();

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
                        ... My::hiddenFields(),
                    ]),
                ]),
            ])
            ->render();

        // Second tab (User-defined CSS)
        echo (new Div('css-editor'))
            ->class('multi-part')
            ->title(__('Supplemental CSS'))
            ->items([
                (new Text('h3', __('Supplemental CSS')))
                    ->class('out-of-screen-if-js'),
                (new Form('css-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('css_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(self::$css_content))
                                ->class('maximal')
                                ->disable(!self::$css_writable),
                        ]),
                        (new Para())->items([
                            (self::$css_writable ?
                            (new Submit(['css'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$css_file)))),
                            ... My::hiddenFields(),
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
                                ->value(Html::escapeHTML((string) self::$css_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        // Third tab (User-defined JS)
        echo (new Div('js-editor'))
            ->class('multi-part')
            ->title(__('Supplemental JS'))
            ->items([
                (new Text('h3', __('Supplemental JS')))
                    ->class('out-of-screen-if-js'),
                (new Form('js-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('js_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(self::$js_content))
                                ->class('maximal')
                                ->disable(!self::$js_writable),
                        ]),
                        (new Para())->items([
                            (self::$js_writable ?
                            (new Submit(['js'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$js_file)))),
                            ... My::hiddenFields(),
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
                                ->value(Html::escapeHTML((string) self::$js_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        // Frouth tab (User-defined PO)
        echo (new Div('po-editor'))
            ->class('multi-part')
            ->title(__('Supplemental PO'))
            ->items([
                (new Text('h3', __('Supplemental PO')))
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
                                ->value(Html::escapeHTML(self::$po_content))
                                ->class('maximal')
                                ->disable(!self::$po_writable),
                        ]),
                        (new Para())->items([
                            (self::$po_writable ?
                            (new Submit(['po'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$po_file)))),
                            ... My::hiddenFields(),
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
                                ->value(Html::escapeHTML((string) self::$po_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        // Frouth tab (User-defined HTML head)
        echo (new Div('html-editor'))
            ->class('multi-part')
            ->title(__('Supplemental HTML head directives'))
            ->items([
                (new Text('h3', __('Supplemental HTML head directives')))
                    ->class('out-of-screen-if-js'),
                (new Div(null, 'hr')),
                (new Form('html-form'))
                    ->action(dcCore::app()->admin->getPageURL())
                    ->method('post')
                    ->fields([
                        (new Para())->items([
                            (new Textarea('html_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML(self::$html_content))
                                ->class('maximal')
                                ->disable(!self::$html_writable),
                        ]),
                        (new Para())->items([
                            (self::$html_writable ?
                            (new Submit(['html'], __('Save')))
                                ->accesskey('s') :
                            (new Text(null, sprintf(__('Unable to write file %s. Please check the dotclear var folder permissions.'), self::$html_file)))),
                            ... My::hiddenFields(),
                        ]),
                        (new Para())->items([
                            (new Text(null, __('Note: this supplemental HTML head directives will added to the default HTML head.'))),
                        ])
                            ->class('info'),
                        (new Para())->items([
                            (new Text(null, __('Sample HTML head:'))),
                        ]),
                        (new Para())->items([
                            (new Textarea('html_demo_content'))
                                ->cols(72)
                                ->rows(25)
                                ->value(Html::escapeHTML((string) self::$html_demo_content))
                                ->class('maximal')
                                ->readonly(true),
                        ]),
                    ]),
            ])
            ->render();

        if ($user_ui_colorsyntax) {
            echo
            Page::jsRunCodeMirror(
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
                    [
                        'name'  => 'editor_html',
                        'id'    => 'html_content',
                        'mode'  => 'text/html',
                        'theme' => $user_ui_colorsyntax_theme,
                    ],
                ]
            );
        }

        Page::closeModule();
    }
}
