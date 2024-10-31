=== Plugin Showcase ===
Contributors: bradt
Tags: plugin development, plugin showcase, plugin list
Requires at least: 2.0
Tested up to: 2.8
Stable tag: 0.1.1
Last Updated: 2009-03-23

A plugin that simulates the Wordpress.org plugin directory, allowing plugin developers to easily showcase their work.

== Description ==

Ideally all open source Wordpress plugins could be hosted at Wordpress.org. Unfortunately, that is
not the case (not yet anyway) and publishing your own plugins can be a pain. This plugin solves
that problem by allowing plugin developers to showcase all their plugins just by dropping them into a folder.

Plugin Showcase works much the same as the Wordpress.org plugin directory. It looks through the given folder,
parses any 'readme.txt' files and PHP files containing meta data in the header, and returns a neat array of
all the meta data.

The readme.txt files must conform to [Wordpress.org's Readme Standard](http://wordpress.org/extend/plugins/about/readme.txt)
and can be validated using [Wordpress.org's Readme Validator](http://wordpress.org/extend/plugins/about/validator/).

== Installation ==

1. Download plugin-showcase.&lt;version&gt;.zip
2. Unzip the archive
3. Upload the plugin-showcase folder to your wp-content/plugins directory
4. Activate the plugin through the WordPress admin interface
5. Create custom templates in your Wordpress theme using Plugin Showcase's <em>ps&#95;get&#95;plugins</em> and <em>ps&#95;get&#95;plugin</em> API functions

== Theme API ==

The following functions can be used in your theme's page templates to display
your plugins.

<pre>function ps_get_plugin($slug, $plugins_dir = '')</pre>
Looks for a readme.txt file in <em>$plugins_dir/$slug</em> and parses it if it exists. It then
looks for a PHP file containing a header with the same 'Name:' field. Finally it returns
a single associative array with the meta data from both the readme.txt and the PHP file.

<pre>function ps_get_plugins($plugins_dir = '')</pre>
This function goes through all subfolders in the given folder, executes the
<em>ps&#95;get&#95;plugin</em> function, and returns all the plugins found in
<em>$plugins_dir</em> and their meta data.

There are two sample page templates located in the <em>sample-templates</em> folder once you
download and unzip the archive. One is for a simple listing of the plugins and
the other is for a view of the plugin details.

== Thanks ==

A thanks to <a href="http://markjaquith.com/">Mark Jaquith</a> for releasing the [Readme Parser](http://code.google.com/p/wordpress-plugin-readme-parser/).

== Release Notes ==

* 0.1 - 2009-03-23<br />
  First release
* 0.1.1 - 2009-12-13<br />
  Now hosted at Wordpress.org
