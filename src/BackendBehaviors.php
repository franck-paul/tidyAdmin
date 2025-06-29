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
use Dotclear\Core\Backend\Page;
use Dotclear\Helper\File\Files;
use Dotclear\Helper\File\Path;
use Exception;
use JShrink\Minifier;

class BackendBehaviors
{
    public static function adminPageHTMLHead(): string
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
            echo
                Page::jsJson('tidyadmin', [
                    'header_color' => (string) App::auth()->prefs()->interface->headercolor,
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
            echo Page::cssLoad(urldecode(Page::getVF('plugins/' . My::id() . '/admin.css')));
        }

        // User defined Javascript
        if (file_exists(Path::real(App::config()->varRoot()) . '/plugins/' . My::id() . '/admin.js')) {
            echo Page::jsLoad(urldecode(Page::getVF('plugins/' . My::id() . '/admin.js')));
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
        $css = preg_replace('/\s+/', ' ', $css);

        // 2. Remove newlines
        $css = str_replace(["\r\n", "\r", "\n", "\t"], '', $css);

        return trim($css);
    }

    public static function themeEditorWriteFile(string $file, string $type): string
    {
        if (App::auth()->prefs()->interface->minifythemeresources) {
            if ($type === 'js' || $type === 'css') {
                try {
                    $minified_file = self::getMinifiedFile($file);

                    // First delete existing minified version
                    if ($minified_file !== '' && file_exists($minified_file) && is_writable($minified_file)) {
                        unlink($minified_file);
                    }

                    // Then try to minify it
                    $content = file_get_contents($file);

                    $minified = match ($type) {
                        'js'    => Minifier::minify($content, ['flaggedComments' => false]),
                        'css'   => self::basicCSSMinify($content),
                        default => '',
                    };

                    if ($content !== $minified && $minified !== '') {
                        // Write the minified script
                        if (($fp = fopen($minified_file, 'wb')) === false) {
                            throw new Exception(sprintf('Unable to open file %s', $minified_file));
                        }
                        fwrite($fp, $minified);
                        fclose($fp);
                    }
                } catch (Exception) {
                    // Ignore exception, the minified version is not mandatory
                }
            }
        }

        return '';
    }

    public static function themeEditorDeleteFile(string $file, string $type): string
    {
        if (App::auth()->prefs()->interface->minifythemeresources) {
            if ($type === 'js' || $type === 'css') {
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
        }

        return '';
    }
}
