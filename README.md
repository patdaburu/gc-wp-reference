# gc-wp-reference
GeoComm Wordpress Reference Plugin
==================================

This project is a sample WordPress plugin that can be used as a reference template for creating new plugins.  It demonstrates a number of processes including:

  - registering hooks and actions,
  - creating pages that use shortcodes,
  - registering options,
  - creating admin pages,
  - and other interesting stuff.

Getting Started
---------------
1.  Create a directory called `gc-wp-reference` in your [WordPress installation's plugins directory](https://codex.wordpress.org/Determining_Plugin_and_Content_Directories) which can almost certainly be found at `wp-content/plugins`.
2.  Place this project's files under that directory.
3.  Start modifying.

The Plugin Directory
--------------------
The main point of entry in this project is `gc-wp-reference.php`.  To allow WordPress to load it correctly, the directory in which it resides should also be called `gc-wp-reference` as per WordPress conventions.  When you start building your plugin, the first thing you'll want to do is change the names of the directory and the main file to reflect your plugin's name.

PHP Namepspaces
---------------
The reference project's [main class] (https://github.com/patdaburu/gc-wp-reference/blob/master/Plugin.php) (`Plugin.php`) is defined in a namespace that generally represents the plugin name (`gc\wp\reference`).  The namespace is, of course, used to avoid naming conflicts, but is further used to create WordPress slugs, option group names, administrative menu titles, and so on.

So, before you really get rolling, change the namespace assignments in the these modules to reflect your plugin's name.

  - [Options.php] (https://github.com/patdaburu/gc-wp-reference/blob/master/Plugin.php)
  - [Plugin.php] (https://github.com/patdaburu/gc-wp-reference/blob/master/Plugin.php)
  - [Post.php] (https://github.com/patdaburu/gc-wp-reference/blob/master/Post.php)
  - [Tables.php] (https://github.com/patdaburu/gc-wp-reference/blob/master/Tables.php)
  - [Widget.php] (https://github.com/patdaburu/gc-wp-reference/blob/master/Widget.php)

Encouragement
-------------
Go get 'em.

