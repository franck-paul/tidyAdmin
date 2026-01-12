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

use Dotclear\App;
use Dotclear\Core\Backend\Favorites;
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Dotclear\Helper\Html\Form\Div;
use Dotclear\Helper\Html\Form\Link;
use Dotclear\Helper\Html\Form\Text;
use Exception;
use JShrink\Minifier;

class BackendBehaviors
{
    public static function adminBlogPreferencesHeaders(): string
    {
        if (App::auth()->prefs()->interface->menusblogprefs) {
            return
                My::cssLoad('blog_prefs.css') .
                My::jsLoad('blog_prefs.js');
        }

        return '';
    }

    public static function adminPageHTMLHead(bool $main = false): string
    {
        // Reduce home button
        if (App::auth()->prefs()->interface->minidcicon) {
            echo
                My::cssLoad('dcicon.css') .
                My::jsLoad('dcicon.js');
        }

        // Load search form (menu) repositioning helper
        if (App::auth()->prefs()->interface->movesearchmenu) {
            echo
                My::cssLoad('search_menu.css') .
                My::jsLoad('search_menu.js');
        }

        // Load search form (media) repositioning helper
        if (App::auth()->prefs()->interface->clonesearchmedia) {
            echo
                My::cssLoad('search_media.css') .
                My::jsLoad('search_media.js');
        }

        // Add hover detection on collapser
        if (App::auth()->prefs()->interface->hovercollapser) {
            echo
                My::jsLoad('hover_collapser.js');
        }

        // Move plugin settings link to top
        if (App::auth()->prefs()->interface->pluginconfig) {
            echo
                My::cssLoad('plugin_config.css') .
                My::jsLoad('plugin_config.js');
        }

        // Allow double click on header to switch theme
        if (App::auth()->prefs()->interface->switchtheme) {
            echo
                My::jsLoad('switch_theme.js');
        }

        // Switch fetch requests
        if (App::auth()->prefs()->interface->switchfetch) {
            echo
                My::cssLoad('switch_fetch.css') .
                My::jsLoad('switch_fetch.js');
        }

        // Always display legacy editor toolbar
        if (App::auth()->prefs()->interface->stickytoolbar) {
            echo
                My::cssLoad('sticky_toolbar.css');
        }

        // Header color
        if (App::auth()->prefs()->interface->userheadercolor) {
            // Prepare header color
            $light        = App::auth()->prefs()->interface->headercolor;
            $dark         = App::auth()->prefs()->interface->headercolor_dark ?? App::auth()->prefs()->interface->headercolor;
            $header_color = sprintf(
                'light-dark(%s, %s)',
                (string) $light,
                (string) $dark
            );

            echo
                App::backend()->page()->jsJson('tidyadmin', [
                    'header_color' => $header_color,
                ]) .
                My::cssLoad('header_color.css') .
                My::jsLoad('header_color.js');
        }

        // Swap alt/desc of media details
        if (App::auth()->prefs()->interface->swapaltdescmedia) {
            echo
                My::cssLoad('swap_alt_desc_media.css') .
                My::jsLoad('swap_alt_desc_media.js');
        }

        // User defined head directives
        if (file_exists(Path::real(App::config()->varRoot()) . '/plugins/' . My::id() . '/admin.html')) {
            echo trim((string) file_get_contents(Path::real(App::config()->varRoot()) . '/plugins/' . My::id() . '/admin.html')) . "\n";
        }

        // User defined CSS rules
        if (file_exists(Path::real(App::config()->varRoot()) . '/plugins/' . My::id() . '/admin.css')) {
            echo App::backend()->page()->cssLoad(urldecode((string) App::backend()->page()->getVF('plugins/' . My::id() . '/admin.css')));
        }

        // User defined Javascript
        if (file_exists(Path::real(App::config()->varRoot()) . '/plugins/' . My::id() . '/admin.js')) {
            echo App::backend()->page()->jsLoad(urldecode((string) App::backend()->page()->getVF('plugins/' . My::id() . '/admin.js')));
        }

        if ($main && App::auth()->prefs()->interface->dock) {
            // Display favorites in dock
            echo
                My::cssLoad('dock.css') .
                My::jsLoad('dock.js');
        }

        return '';
    }

    public static function adminPageHTMLBody(bool $main = false): string
    {
        if ($main && App::auth()->prefs()->interface->dock) {
            // Display favorites in dock

            $show_active = App::auth()->prefs()->interface->dockactive;

            $url     = htmlspecialchars_decode((string) App::backend()->url()->get('admin.home'));
            $pattern = '@' . preg_quote($url, '@') . '(&.*)?' . '$@';

            /**
             * @var array<string, array{string, string, string|list<string>|null, string}>
             *
             * List of dock icons (user favorites)
             *
             * items structure:
             * [0] = title
             * [1] = url
             * [2] = icons (usually array (light/dark))
             * [3] = class
             */
            $dashboardIcons = [
                'home' => [
                    __('Go to dashboard'),
                    App::backend()->url()->get('admin.home'),
                    ['style/dashboard.svg', 'style/dashboard-dark.svg'],
                    $show_active ? (preg_match($pattern, (string) $_SERVER['REQUEST_URI']) ? 'active' : '') : '',
                ],
            ];

            // Get user favorites
            $favorites = App::backend()->favorites()->getUserFavorites();
            foreach ($favorites as $favorite_id => $favorite) {
                $url     = htmlspecialchars_decode((string) $favorite->url());
                $pattern = '@' . preg_quote($url, '@') . '(&.*)?' . '$@';

                $dashboardIcons[$favorite_id] = [
                    (string) $favorite->title(),
                    (string) $favorite->url(),
                    $favorite->largeIcon(),
                    $show_active ? (preg_match($pattern, (string) $_SERVER['REQUEST_URI']) ? 'active' : '') : '',
                ];
            }

            echo (new Div('dock'))
                ->class(App::auth()->prefs()->interface->dockautohide ? 'autohide' : '')
                ->items(
                    array_map(
                        fn (string $id, array $info) => (new Link('icon-process-' . $id . '-dock'))
                            /*
                             * $info item structure:
                             * [0] = title
                             * [1] = url
                             * [2] = icons (usually array (light/dark))
                             * [3] = class
                             */
                            ->href($info[1])
                            ->title($info[0])
                            ->class($info[3])
                            ->items([
                                (new Div())
                                    ->items([
                                        (new Text(null, App::backend()->helper()->adminIcon($info[2], alt:$info[0]))),
                                    ]),
                            ]),
                        array_keys($dashboardIcons),
                        array_values($dashboardIcons)
                    )
                )
            ->render();
        }

        return '';
    }

    public static function adminDashboardFavorites(Favorites $favs): string
    {
        $favs->register(My::id(), [
            'title'      => __('Tidy Administration'),
            'url'        => My::manageUrl(),
            'small-icon' => My::icons(),
            'large-icon' => My::icons(),
        ]);

        return '';
    }

    protected static function getMinifiedFile(string $file): string
    {
        if ($file !== '') {
            $extension     = Files::getExtension($file);
            $minified_base = substr($file, 0, strlen($file) - strlen($extension) - 1);
            if (Files::getExtension($minified_base) !== 'min') {
                return $minified_base . '.min.' . $extension;
            }
        }

        return '';
    }

    protected static function basicCSSMinify(string $css): string
    {
        // 1. Remove comments
        $css = preg_replace('!/\*[^*]*\*+([^/][^*]*\*+)*/!', '', $css);

        // 2. Reduce multiple whitespaces
        $css = preg_replace('/\s+/', ' ', (string) $css);

        // 2. Remove newlines
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', (string) $css);

        return trim((string) $css);
    }

    public static function themeEditorWriteFile(string $file, string $type): string
    {
        // List of supported extension for minification
        $types = ['js', 'css'];

        if (App::auth()->prefs()->interface->minifythemeresources && in_array($type, $types)) {
            try {
                $minified_file = self::getMinifiedFile($file);

                // First delete existing minified version
                if ($minified_file !== '' && file_exists($minified_file) && is_writable($minified_file)) {
                    unlink($minified_file);
                }

                // Then try to minify it
                $content = file_get_contents($file);
                if ($content) {
                    $minified = match ($type) {
                        'js'  => (string) Minifier::minify($content, ['flaggedComments' => false]),
                        'css' => self::basicCSSMinify($content),
                    };

                    if ($content !== $minified && $minified !== '') {
                        // Write the minified script
                        if (($fp = fopen($minified_file, 'wb')) === false) {
                            throw new Exception(sprintf('Unable to open file %s', $minified_file));
                        }
                        fwrite($fp, $minified);
                        fclose($fp);
                    } else {
                        throw new Exception(sprintf('Unable to read file %s', $file));
                    }
                }
            } catch (Exception) {
                // Ignore exception, the minified version is not mandatory
            }
        }

        return '';
    }

    public static function themeEditorDeleteFile(string $file, string $type): string
    {
        if (App::auth()->prefs()->interface->minifythemeresources && ($type === 'js' || $type === 'css')) {
            try {
                $minified_file = self::getMinifiedFile($file);

                // First delete existing minified version
                if ($minified_file !== '' && file_exists($minified_file) && is_writable($minified_file)) {
                    unlink($minified_file);
                }
            } catch (Exception) {
                // Ignore exception, the minified version is not mandatory
            }
        }

        return '';
    }

    public static function themeEditorDevMode(): string
    {
        if (App::auth()->prefs()->interface->themeeditordevmode) {
            // Will put theme editor in development mode
            return 'DEV';
        }

        return '';
    }
}
