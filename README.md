# AddScript plugin for Typesetter CMS #

## About
* Originally made by [Florin Catalin](https://github.com/florincatalin) based on some code from [ppeterka's EasyMark plugin](https://github.com/ppeterka/easymark) 
* The AddScript plugin adds a section type that can be used to enter source code directly into the page without using CK Editor (Typesetter's default content editor)
* However, the AddScript plugin makes it much easier to insert any code without modifying it in any way. For example, you can easily insert any Facebook script
* After installation, you may insert AddScript sections similar to other content types
* Although we added some JavaScript validation in ver 1.1.0, you may still be able to enter script code that breaks some of your website's functionality. Therefore, some programming knowledge is required. Use it at your own risk!

See also [Typesetter Home](https://www.typesettercms.com), [Typesetter on GitHub](https://github.com/Typesetter/Typesetter)

## Current Version 
1.1.1

## Change Log ##
* 1.1.1 Admin page ready to manage global scripts, internationlization (currently en, de, fr, it)
* 1.1.0 Some new features and enhancements, see below
* 1.0.0 Intial version

## TODOs ##
* Opt-in Cookies (required by EU GDPR for all sorts of analytics and tracking, e.g. GA, Google Tag Manager, Facebook Pixel, you name it)
* Activate/Deactivate switch for global scripts.
* Actually link script section execution to global scripts (doesn't work yet).

## New in version 1.1.0 ##
* Added selection for 4 different script types to the editor area: 'Raw Output (in place)' corresponds to ver 1.0.0 output. The 3 new options 'JavaScript', 'jQuery' and 'Script URL' use Typesetter's native methods to add script code to a page or load scripts from remote sources.
* CodeMirror: We now use [CodeMirror](https://github.com/codemirror/CodeMirror) for a more pleasant UX and syntax highlighting for JavaScript/jQuery and mixed HTML/JS/CSS.
* JavaScript Check: Code entered using the script types 'JavaScript' and 'jQuery' will be checked for errors.
* Prevent autosave: Typesetter's default autosaving (every 5 seconds) may lead to fragmented code. If the new syntax check works reliably (which yet needs a bit of testing), we might turn it on again.
* AddScript sections will be hidden to regular visitors, except when using the 'Raw Output (in place)' type, which may contain and / or write HTML markup to the page via JS. On the other hand, when logged in, the sections are now clearly visible and only expand when being edited.

## Requirements ##
* Typesetter CMS 5.1.1-b1+ (as of ver. 1.1.1 it will use the new AdminLinklabel hook for i18n)

## Manual Installation ##
1. Download the master ZIP archive by clicking the green [ clone or download ] button on the top right of this page, then click 'Download ZIP' in the popover menu
2. Upload the extracted folder 'AddScriptPluginTypesetter-master' to your server into the /addons directory
3. Install using Typesetter's Admin Toolbox &rarr; Plugins &rarr; Manage &rarr; Available &rarr; AddScript

## Authors
[florincatalin](https://github.com/florincatalin), [juek](https://github.com/juek)
