=== WordPress 3 Invoices ===
Contributors: elliot condon
Donate link: http://www.wordpress3invoice.com
Tags: wordpress, wordpress3, 3, invoice, invoices, customization, template, statistics, wp3i, quote, developer, designer, freelance, tool
Requires at least: 3.0
Tested up to: 3.1
Stable tag: 3.1

Invoice software designed specifically for freelance web designers / developers. With WordPress 3 Invoice you can create and manage quotes, invoices, statistics, emails, clients and more. This plugin also comes with options and help videos to make your life easier. Happy Invoicing.

== Description ==

Wp3i is a WordPress plugin to manage invoices for freelance web designers / developers.
 
* Create and track your invoices and quotes
* Send stylish invoices to clients via HTML email.
* Create a custom php templates for invoices, quotes and HTML emails
* invoice templates use native WordPress code to make life easy
* See live statistics of your income
* Well designed interface
* Customise Currency, tax and more
* Completely FREE! 
* Supports I18n
* Pay online though PayPal

= Need help? =
Support Forum
http://support.plugins.elliotcondon.com/categories/wordpress-3-invoice

= Please Vote and Enjoy =

== Installation ==

1. Upload 'wp3-invoice.php' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Copy the template to your active theme folder and rename to "invoice"
(the file '/wp-content/themes/xxxx/invoice/invoice.php' should exist)
4. Click on WP3 Invoice -> Help to view tutorials / API
5. Click on the new 'WP3 Invoice' menu item to get started

== Frequently Asked Questions ==

http://www.wordpress3invoice.com/support/

== Screenshots ==

See demo videos here: http://www.wordpress3invoice.com/tour/

== Changelog ==

= 2.0.4 =
* Fixed WP 3.1 Column Bug
* Included PayPal payment gateway!
* Opened Support forum http://support.plugins.elliotcondon.com/categories/wordpress-3-invoice

= 2.0.3 =
* Fixed Invoice type and detail type problem caused byI18l support
* Made change to invoice template if(get_invoice_type() ==). Please update your template to match

= 2.0.2 =
* Added I18l support for languages (fingers crossed it all works)
* General Housekeeping

= 2.0.1 =
* Invoice archive is now a blank page
* Added currency formating throughout wp3i
* New Currency drop down list on options page
* New Twitter feed on Help page (stay updated!)
* General Housekeeping
* Added Gateway folder (PayPal gateway $1.00)
* Added backup and restore functionality for gateway files on auto update
* All template functions that display a price, also display currency in appropriate format
* Remove all wp3i_currency() calls from your custom invoice and email templates.

= 2.0.0 =
* Re writen as an object oriented plugin
* Many bug fixes
* Many small improvements
* New task bar when viewing invoices
* Invoices now have password security
* New Help page + videos
* New sexy admin pages
* Fixed stats page bugs
* More small improvements

= 1.1.2 =
* Sexier Stats Page
* Sexier Invoice edit Page
* New Option: Email address (Emails appear sent from x)
* Fixed Email template bug (well, one of many...)

= 1.1.1 =
* Added Individual tax amounts
* New Option: Enable / disable the content editor
* Minor changes to stats and invoice edit pages
* Added Edit Client buttons to invoice admin pages
* Added Client Filter to Invoices admin page

= 1.1.0 =
* Extended client taxonomy to accept more information
* New client functions
* Updated invoice and email template files
* New option: turn on / off permalink encoding

= 1.0.9 =
* Fixed template url bug
* Added get_ functions to API

= 1.0.8 =
* Fixed sending email issues. (removed fopen functionality)!

= 1.0.7 =
* Fixed Custom Permalink issues!

= 1.0.6 =
* Fixed small invoice breakdown description bug

= 1.0.5 =
* Send HTML email invoices to clients
* Clients have email address field
* Customise emails with email.php template
* New option: Receive invoice email copy
* Encoded permalinks. (Publish old invoices to auto create new permalink)
* Quote.php is gone. Both quotes and invoice run on invoice.php
* Tax not shown if set to 0.00
* A new fresh invoice details interface
* Invoice type, sent, paid are all separate
* Invoices now use the content editor

= 1.0.4 =
* Fixed small invoice status bug

= 1.0.3 =
* Added support for tax
* Updated template files to show subtotal, tax, total
* Improved Admin pages

= 1.0.2 =
* Options page
* added ability to change currency symbol
* Fixed minor jQuery bugs in admin
* Added template API documentation

= 1.0.1 =
* Fixed Permalink Problem. Custom Permalinks should now work.
* Template files can now be read from your theme folder

= 1.0 =
* The first release of WP3I.