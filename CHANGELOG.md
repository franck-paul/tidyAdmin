# Changelog

## 11.6.1 — 2026-11-19

- Corrige la sélection du premier élément des accès directs aux paramètres du blog et aux préférences utilisateur.

## 11.6 — 2026-11-10

- Remplace l’appel déprécié `adminIcon()` par `dashboardIcon()`.

## 11.5 — 2026-11-07

- Renforce les contrôles de qualité du code PHP.

## 11.4.1 — 2026-09-14

- Corrige le positionnement du dock en mode RTL.

## 11.4 — 2026-09-13

- Améliore la prise en charge des langues RTL avec des propriétés CSS logiques.

## 11.3.1 — 2026-09-10

- Corrige la couleur de fond du bouton de contrôle des requêtes JavaScript.
- Évite de dupliquer la métadonnée `theme-color` lorsque les modes clair et sombre ont la même couleur.

## 11.3 — 2026-09-04

- Ajoute la métadonnée `theme-color` lorsque la couleur d’en-tête est personnalisée.

## 11.2 — 2026-08-28

- Ajoute une option pour envoyer automatiquement les modifications après l’échange du texte alternatif et de la description d’un média.

## 11.1 — 2026-08-19

- Ajoute une option pour garder visible la liste des widgets pendant le défilement.
- Améliore la prise en charge du bouton de débogage avec les couleurs d’en-tête personnalisées.
- Vérifie les conditions avant l’enregistrement du module.

## 11.0 — 2026-08-03

- Ajoute la compatibilité avec Dotclear 2.39.
- Trie les accès rapides aux préférences utilisateur et aux paramètres du blog.
- Améliore la gestion des icônes et des champs de recherche dans l’administration.

## 10.2.2 — 2026-06-21

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 10.2.1 — 2026-06-21

- Fix double scrollbar when using Codemirror (thanks Philippe)

## 10.2 — 2026-06-20

- Change look of active item in dock

## 10.1 — 2026-06-14

- Align quick access selects for blog parameters and user preferences to the right

## 10.0 — 2026-05-14

- Cope with favicon.svg/favicon.png (in head) rather than deleted favicon.ico (since DC 2.38)

## 9.42 — 2026-05-06

- Harmonize usage of App::blogWorkspace()::NS_... and App::userWorkspace()::WS_... (and use short version of constant name for types)

## 9.41 — 2026-04-02

- Use id as name cannot be an attribute of a div

## 9.40 — 2026-03-06

- Add vertical resize capabilities to textarea/Codemirror Smaller font for line numbers (Codemirror only)

## 9.39 — 2026-02-24

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.38 — 2026-02-23

- Remove old favicon.ico if present in head

## 9.37 — 2026-02-23

- Adapt the SVG favicon (or add one) with the background selected for the header if any, closes #30

## 9.36 — 2026-02-18

- Use white or black color for header text/links depending on brightness of chosen background colors, #closes #29

## 9.35 — 2026-01-15

- Add dirty forms monitoring

## 9.34 — 2026-01-13

- Give focus to 1st eligible element inside fieldset Take care of motion reduced for scrolling behavior

## 9.33 — 2026-01-13

- Add direct access menus to user preferences fieldsets
- Add a random number between 1000 and 9999 to the generated ID in order to avoid collision

## 9.32 — 2026-01-12

- Cope with fieldsets without ID

## 9.31 — 2026-01-12

- Add a label around select

## 9.30.1 — 2026-01-12

- Cleanup

## 9.30 — 2026-01-12

- Add direct access menus to blog settings fieldsets
- Update JShrink (tests only, no need to publish another version)

## 9.29 — 2025-12-29

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.28 — 2025-12-25

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.27 — 2025-11-16

- Try to not overlap standard editor toolbar with dock

## 9.26 — 2025-11-04

- Adjust backdrop filter of dock

## 9.25 — 2025-10-29

- Apply mini-icon setting to popup windows too

## 9.24 — 2025-10-23

- Fix options management

## 9.23 — 2025-10-23

- Don't reset inactive settings
- Fix current active item indicator vertical position

## 9.22 — 2025-10-23

- Cope with a11y settings (contrast, transparency) for dock

## 9.21 — 2025-10-23

- Reduce brightness under dock

## 9.20 — 2025-10-23

- Experimental: use grid to display option tabs fieldsets

## 9.19 — 2025-10-23

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.18 — 2025-10-23

- Group user options (1st tab)

## 9.17 — 2025-10-21

- Add a note about dev mode for theme editor

## 9.16 — 2025-10-19

- Show auto-hidden dock if focus on it, in it

## 9.15 — 2025-10-19

- Add option for active favorite identification, and auto hide feature

## 9.14 — 2025-10-19

- Fix active indicator vertical position

## 9.13 — 2025-10-19

- Reduce gap between icons in dock

## 9.12 — 2025-10-19

- Add hover effect, use a non transparent background for each icon

## 9.11 — 2025-10-19

- Add a dot indicator below currently active favorite, if any

## 9.10 — 2025-10-18

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.9 — 2025-10-18

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.8 — 2025-10-18

- Add dashboard home at 1st item of dock

## 9.7 — 2025-10-18

- Add an option to display favorites in a dock (bottom of page)

## 9.6 — 2025-10-17

- Don't overlap alert messages (success, warning, error, …) if present when moving config button, addresses #19

## 9.5 — 2025-10-05

- Don't overlap new stuff (top add) button if present when moving config button, fixes #19

## 9.4 — 2025-09-30

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 9.3 — 2025-09-22

- Use backend services rather than static calls

## 9.2 — 2025-09-14

- Replace plugin setting buttons' text by icons when moved to top

## 9.1 — 2025-09-11

- Review context checking: super admin only for all module

## 9.0 — 2025-09-07

- DC 2.36 compliance

## 8.20 — 2025-09-05

- Cope with new layout of bottom plugin's settings link(s) in DC 2.36+ Still compatible with 2.31+

## 8.19 — 2025-08-18

- Add a setting to set theme editor in dev'mode (2.36+)

## 8.18 — 2025-08-12

- Better this way

## 8.17 — 2025-07-31

- Add dark mode header background color selector
- Bump up JShrink to 1.8.0

## 8.16.1 — 2025-07-04

- Fix plugin config button(s) position

## 8.16 — 2025-06-29

- Add JS/CSS minifier for themeEditor (and an option to activate it)
- Move up the theme (dark/light) notice

## 8.15 — 2025-03-29

- Add a warning notice for options tab (user only)

## 8.14 — 2025-02-04

- Use default monospace font

## 8.13 — 2025-02-04

- Back to nested rules in CSS (minify has been fixed in 12.1.0)

## 8.12 — 2025-02-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.11.3 — 2025-02-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.11.2 — 2025-02-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.11.1 — 2025-02-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.11 — 2025-02-01

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.10 — 2025-01-31

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.9 — 2025-01-31

- Add an optional button to swap alternate text and description in media details

## 8.8.1 — 2024-12-23

- Don't display the switch fetch button on popup

## 8.8 — 2024-12-15

- Add a button in header to stop/run JS fetch requests via dotclear.services() Usually calling REST methods

## 8.7.1 — 2024-12-07

- Fix colors (text and background) of search input when moved in header, using system colors

## 8.7 — 2024-11-28

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.6 — 2024-10-11

- Add user defined header background color option

## 8.5 — 2024-09-21

- Add an early return

## 8.4.2 — 2024-09-18

- Fix cloned search media when folder's selector is not displayed, fixes #11

## 8.4 — 2024-08-21

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.3 — 2024-06-24

- Add sticky CKEditor toolbar option

## 8.2 — 2024-06-02

- Add sticky legacy editor toolbar option

## 8.1 — 2024-05-31

- Add a margin before plugin config button

## 8.0.1 — 2024-04-02

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 8.0 — 2024-04-02

- Cope with 2.30+ mini icon

## 7.5 — 2024-03-30

- Fix background color of moved search input field

## 7.4 — 2024-03-19

- Switch from jQuery to Vanilla flavor for JS scripts

## 7.3 — 2024-03-18

- Add double-click on header to switch theme option

## 7.2.1 — 2024-01-13

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.2 — 2024-01-12

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.1 — 2023-10-27

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 7.0 — 2023-10-24

- Use instanced item constants rather than interface ones
- Use 2.28 Config methods
- 2.28 compliance

## 6.0 — 2023-08-26

- Dotclear 2.27 compliance, PHP 8.1+
- use static properties rather than admin dynamic ones

## 5.3.1 — 2023-06-27

- Minify JS
- Add local static $init variable

## 5.3 — 2023-06-20

- Use dcCore::app()-&gt;adminurl-&gt;redirect() rather than Http::redirect()

## 5.2 — 2023-06-15

- Add void Install.php

## 5.1.2 — 2023-06-12

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 5.1.1 — 2023-05-31

- Cope with absence of collapser (popup)

## 5.1 — 2023-05-28

- Move demo files in their own demo folder

## 5.0 — 2023-05-28

- Add HTML head directives editor

## 4.2.1 — 2023-05-22

- Minify fix

## 4.2 — 2023-05-15

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 4.1 — 2023-05-10

- Use My::id() rather than string
- Remove comment

## 4.0 — 2023-04-28

- remove demo
- Prepare 2.26

## 3.1 — 2023-03-20

- Add an option to move up the plugin settings link

## 3.0 — 2023-02-18

- Switch to namespace

## 2.1 — 2023-01-31

- Add hover on collapser option
- Minified versions
- New install

## 2.0 — 2022-11-30

- Dotclear 2.24 compliance

## 1.5.1 — 2022-08-14

- Usual oups :-)

## 1.5 — 2022-08-10

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.4 — 2022-07-04

- Remove border-right to tidy DC mini icon

## 1.3 — 2022-05-31

- Reorganize code
- Fix comments

## 1.2 — 2022-05-16

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.1 — 2022-03-19

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.0.1 — 2022-03-18

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 1.0 — 2022-03-17

- Update default branch in _define.php for alternative repository
- Add alternative repository

## 0.12 — 2021-08-07

- Dotclear 2.19 compliance

## 0.11.1 — 2020-12-21

- Cope with color syntax disabled and/or no theme yet selected - fixes #2

## 0.11 — 2020-04-06

- No more inline JS

## 0.10 — 2020-03-20

- jQuery 3+ compliance

## 0.9 — 2019-10-08

- Add LICENSE
- Remove hg files
- Adjust regexp exclusion list
- Update for new way to launch CodeMirror editor
- short notation for array (array() → [])
- Preparing future 0.9 (Dotclear 2.14+)

## 0.8.1 — 2018-01-24

- Version de maintenance, sans changement fonctionnel notable relevé dans l’historique.

## 0.8 — 2018-01-23

- Use DC_VAR folder to store CSS and Js contents

## 0.7.1 — 2018-01-22

- Fix css for iconset information popup (via magnific-popup)

## 0.7 — 2018-01-22

- Fix information popup
- Mise en forme
- Add required attr when it is mandatory
- Dotclear 2.13 compatibility
- Cope with user defined crypt algo

## 0.6 — 2016-08-14

- The 2.10 has been released
- Implement load and run CodeMirror using dcPage public methods
- Add fullscreen mode to CodeMirror editor (using F11 switching key)
- Explicitly checking DC version is not more required as it is defined in require property
- Use Dotclear 2.10 integrated codemirror lib

## 0.5 — 2016-02-26

- Backup CSS/Js file (they will not be overwrite by further plugin updates)

## 0.4 — 2016-02-25

- Add JS editor for user-defined JS admin script
- Using dcPage::cssLoad, dcPage::jsLoad and dcPage::getPF is a better way to load plugin resources

## 0.3 — 2015-07-04

- Default admin.css (no rules)
- No more init of admin.css during install (or update), it's buggy and not necessary

## 0.2.1 — 2015-07-04

- Fixes an error on file existence checking

## 0.2 — 2015-07-04

- Set default admin.css
- More demo CSS rules
- Display content of CSS demo (readonly but copy is available)
- Add user defined CSS only if file exists
- Backout changeset 965fc293242fbaed1d1f3eb9842f257f00b28d7b
- Preserve already existed admin.css, otherwise create a new one from demo, next release will be 0.2

## 0.1 — 2014-12-20

- Ignore .hgcheck directory
- adjustment
- &lt;/div&gt; missing
- new _define.php
- Cope with new favorites system
- New icons
- New notices system
- Iconset installation, 2nd step
- Autres correctifs et améliorations.
