=== Google URL Builder::Twitter ===
Contributors: danielrosca
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_s-xclick&hosted_button_id=4NYW34Z7U53PU
Tags: url, twitter, shorten
Requires at least: 3.0
Tested up to: 3.4.2
Stable tag: 1.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Adds a Twitter button at the beginning of each post. The link inside the tweet is built by Google URL Builder Campaigns.

== Description ==

Every time you post a new article, you have a meta box where you can specify the post name as it will be tracked for your Google Analytics reports. Based on the value that you insert into this box, the plugin will add a Twitter button at the beginning of your post which will contain the post title, a custom text and it will also display the URL built using your preferences. The link is built using Google URL Builder tool. The links are encoded & shorten using the bit.ly v3 API.

== Installation ==

1. Replace constants at the beginning of `url_twitter.php` file with your own values related to Twitter Account, Google Campaigns and Bitly API keys.
2. Upload `url_twitter.php` to the `/wp-content/plugins/` directory 
3. Activate the plugin through the 'Plugins' menu in WordPress

== Frequently asked questions ==

= Does the plugin affect the article content inside the database? =

No, the plugin does not affect the content of an article into the database. It displays the button and it creates the entire twitter message while WordPress loads the article content.

== Screenshots ==

1. http://bit.ly/X9zox0
2. http://bit.ly/SlhKla
3. http://bit.ly/QvWOtv

== Changelog ==

Version 1.0:
- there are no known bugs
- the constants inside the `url_twitter.php` file are easy configurable
- solved bit.ly url encode possible problems 

Version 0.3:
- added constants for easier edits

Version 0.2:
- was added support for bit.ly services

== Upgrade notice ==

- in case of upgrade the plugin right now requires manual edit of the file
- the plugin is compatible with the WordPress 3.5 RC