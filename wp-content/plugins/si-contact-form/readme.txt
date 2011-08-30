=== Fast Secure Contact Form ===
Contributors: Mike Challis
Author URI: http://www.642weather.com/weather/scripts.php
Donate link: http://www.FastSecureContactForm.com/donate
Tags: Akismet, captcha, contact, contact form, form, mail, email, spam, multilingual, wpmu, buddypress
Requires at least: 2.8
Tested up to: 3.2.1
Stable tag: trunk

A super customizable contact form that lets your visitors send you email. Blocks all automated spammers. No templates to mess with.

== Description ==

This plugin allows a webmaster to easily create and add contact forms to WordPress. The contact form will let the user send emails to a site's admin. An administration panel is present, where the webmaster can create and preview unlimited forms. 

Features a super easy admin panel, multi-form feature, autoresponder, no templates to mess with, and an option to redirect visitors to any URL after the message is sent. Includes CAPTCHA and Akismet support to block all common spammer tactics. Spam is no longer a problem. You can add extra fields of any type: text, textarea, checkbox, checkbox-multiple, radio, select, select-multiple, attachment, date, time, hidden, password, and fieldset.

* [FastSecureContactForm.com](http://www.fastsecurecontactform.com/)
* [Download WordPress Plugin Version](http://downloads.wordpress.org/plugin/si-contact-form.zip)
* [Download PHP Script Version](http://www.fastsecurecontactform.com/download-php-script)

= Help Keep This Plugin Free =

If you find this plugin useful to you, please consider [__making a small donation__](http://www.fastsecurecontactform.com/donate) to help contribute to my time invested and to further development. Thanks for your kind support! - [__Mike Challis__](http://profiles.wordpress.org/users/MikeChallis/)

Features:
--------
 * Super easy customizable Options from Admin settings page.
 * Multi-Form feature that allows you to have as many different forms as you need.[See FAQ](http://www.fastsecurecontactform.com/multiple-e-mail-recipients)
 * Optional extra fields of any type: text, textarea, checkbox, checkbox-multiple, radio, select, select-multiple, attachment, date, time, hidden, password, fieldset(box). [See FAQ](http://www.fastsecurecontactform.com/how-to-add-extra-fields)
 * File attachments are supported, see here for details: http://wordpress.org/support/topic/416371
 * Backup/restore tool. You can backup/restore all your forms or single forms and settings.[See FAQ](http://www.fastsecurecontactform.com/backup-restore-forms)
 * Easy to hide subject and message fields for use as a newsletter signup.
 * Supports sending mail to multiple departments.[See FAQ](http://www.fastsecurecontactform.com/tip-form-preview)
 * Optional - redirect to any URL after message sent.
 * Optional - posted data can be sent as a query string on the redirect URL. [See faq](http://www.fastsecurecontactform.com/sending-data-by-query-string)
 * Optional - autoresponder E-mail message.[See FAQ](http://www.fastsecurecontactform.com/tip-add-email-autoresponder)
 * Valid coding for HTML, XHTML, HTML STRICT, Section 508, and WAI Accessibility.
 * Uses simple inline error messages.
 * Reloads form data and warns user if user forgets to fill out a field.
 * Validates syntax of E-mail address.
 * CAPTCHA can be turned off or hidden from logged in users and or admins.
 * Multi "E-mail to" contact support.
 * Auto form fill for logged in users.
 * Customizable form field titles.
 * Customizable CSS style.
 * Sends E-mail with UTF-8 character encoding for US and International character support.
 * Pre-fill in form fields from a URL query string. [See FAQ](http://www.fastsecurecontactform.com/query-string-parameters)
 * Save emails to the WordPress database, or export to CSV or Excel. [See FAQ](http://www.fastsecurecontactform.com/save-to-database)
 * I18n language translation support. [See FAQ](http://www.fastsecurecontactform.com/how-to-translate)

Security:
--------
 * It has very tight security, stops all automated spammers.
 * Akismet spam protection support.
 * Spam checks E-mail address input from common spammer tactics...
prevents spammer forcing to:, cc:, bcc:, newlines, and other E-mail injection attempts to spam the world.
 * Makes sure the contact form was posted from your blog domain name only.
 * Filters all form inputs from HTML and other nasties.
 * E-mail message footer shows blog username(if logged on), Date/Time timestamp, IP address, and user agent (browser version) of user who contacted you.

Captcha Image Support:
---------------------
 * Uses Open-source free PHP CAPTCHA library by www.phpcaptcha.org (customized version included)
 * Abstract background with multi colored, angled, and transparent text
 * Arched lines through text
 * Refresh button to reload captcha if you cannot read it
 * CAPTCHA can be disabled in Options

Requirements/Restrictions:
-------------------------
 * Works with Wordpress 2.8+, WPMU, and BuddyPress (Wordpress 3.0+ is highly recommended)
 * PHP5 
 * PHP register_globals and safe_mode should be set to "Off".

== Installation ==

1. Install automatically through the `Plugins`, `Add New` menu in WordPress, or upload the `si-contact-form` folder to the `/wp-content/plugins/` directory. 

2. Activate the plugin through the `Plugins` menu in WordPress. Look for the Settings link to configure the Options. 

3. Add the shortcode `[si-contact-form form='1']` in a Page, Post, or Text Widget. Here is how: Log into your blog admin dashboard. Click `Pages`, click `Add New`, add a title to your page, enter the shortcode `[si-contact-form form='1']` in the page, uncheck `Allow Comments`, click `Publish`. 

4. Test an email from your form.

5. Updates are automatic. Click on "Upgrade Automatically" if prompted from the admin menu. If you ever have to manually upgrade, simply deactivate, uninstall, and repeat the installation steps with the new version.


= I just installed this and do not get any email from it, what could be wrong? =


[See FAQ page: How to troubleshoot mail delivery](http://www.fastsecurecontactform.com/email-does-not-send)


== Screenshots ==

1. screenshot-1.gif is the contact form.

2. screenshot-2.gif is the contact form showing the inline error messages.

3. screenshot-3.gif is the `Contact Form options` tab on the `Admin Plugins` page.

4. screenshot-4.gif adding the shortcode `[si-contact-form form='1']` in a Page.


== Frequently Asked Questions ==

[See the official FAQ at FastSecureContactForm.com](http://www.fastsecurecontactform.com/faq-wordpress-version)

= I just installed this and do not get any email from it, what could be wrong? =

[See FAQ page: How to troubleshoot email delivery](http://www.fastsecurecontactform.com/email-does-not-send)


= Is this plugin available in other languages? =

Yes. To use a translated version, you need to obtain or make the language file for it.
At this point it would be useful to read [Installing WordPress in Your Language](http://codex.wordpress.org/Installing_WordPress_in_Your_Language "Installing WordPress in Your Language") from the Codex. You will need an .mo file for this plugin that corresponds with the "WPLANG" setting in your wp-config.php file. Translations are listed below -- if a translation for your language is available, all you need to do is place it in the `/wp-content/plugins/si-contact-form/languages` directory of your WordPress installation. If one is not available, and you also speak good English, please consider doing a translation yourself (see the next question).

The following translations are included in the download zip file:

* Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)
* Arabic (ar) partial translation - Translated by Jasmine Hassan
* Bulgarian (bg_BG) - Translated by [Dimitar Atanasov](http://chereshka.net)
* Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
* Danish (da_DK) - Translated by [GeorgWP](http://wordpress.blogos.dk/wpdadkdownloads/)
* Farsi(Persian)(fa_IR) partial translation - Translated by Ramin Firooz
* Finnish (fi) - Translated by [Mikko Vahatalo](http://www.guimikko.com/) 
* French (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)
* German (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)
* Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)
* Hebrew, Israel (he_IL) - Translated by [Asaf Chertkoff FreeAllWeb GUILD](http://web.freeall.org) 
* Hungarian (hu_HU) - Translated by [Jozsef Burgyan](http://dmgmedia.hu)
* Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
* Japanese (ja) - Translated by [Ichiro Kozuka]
* Norwegian Bokmal (nb_NO) - Translated by [Tore Johnny Bratveit](http://punktlig-ikt.no)
* Polish (pl_PL) - Translated by [Pawel Mezyk]
* Portuguese (pt_PT) - Translated by [AJBFerreira Blog](http://pws.op351.net/)
* Portuguese Brazil (pt_BR) - Translated by [Rui Alao]
* Romanian (ro_RO) - Translated by [Anunturi Jibo](http://www.jibo.ro)
* Russian (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)
* Spanish (es_ES) - Translated by [Valentin Yonte Rodriguez](http://www.activosenred.com/)
* Swedish (sv_SE) - Translated by [Daniel Persson](http://walktheline.boplatsen.se/)
* Traditional Chinese, Taiwan (zh_TW) - Translated by [Cjh]
* Turkish (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)
* Ukrainian (uk_UA) - Translated by [Wordpress.Ua](http://wordpress.ua/)
* More are needed... Please help translate.

= Can I provide a translation? =

Of course! 
How to translate Fast Secure Contact Form for WordPress
http://www.fastsecurecontactform.com/how-to-translate

= Is it possible to update the translation files for newest version? =

How to update a translation of Fast Secure Contact Form for WordPress
http://www.fastsecurecontactform.com/how-to-update-translation


= This contact form sends E-mail with UTF-8 character encoding for US and International character support. =

English-language users will experience little to no impact. Any non-English questions or messages submitted will have unicode character encoding so that when you receive the e-mail, the language will still be viewable.

If you receive an email with international characters and the characters look garbled with symbols and strange characters, your e-mail program may need to be set as follows: 

How to set incoming messages character encoding to Unicode(UTF-8) in various mail clients:

Evolution:
View > Character Encoding > Unicode

Outlook Express 6, Windows Mail:
Please check "Tools->Options->Read->International Settings". Un-check "Use default encoding format for all incoming messages" 
Now select "View->Encoding", select "Unicode(UTF-8)"

Mozilla Thunderbird:
Click on Inbox.
Select "View->Character Encoding", select "Unicode(UTF-8)"

Gmail:
No setting necessary, it just works.

For more help... [See the official FAQ at FastSecureContactForm.com](http://www.fastsecurecontactform.com/faq-wordpress-version)

== Changelog ==

- Fix default text could not be 0.
- Fix HTML validation with date field.
- Fix rows and cols extra field atrributes.
- Fix javascript console error on date calendar popup.

= 3.0.3.2 =
- (27 Jul 2011) - Fixed medium XSS HTB23036 - as advised by High-Tech Bridge SA Security Research Lab.
- Fix, added missing 00 to 24 hour time select.

= 3.0.3.1 =
- (05 Jul 2011) - Tested / fixed to be compatible with WP 3.2
- Fix for `&apos` entity showing for single quotes on IE7, IE8
- Fix for CAPTCHA input field out of position on IE7 with labels left CSS setting.

= 3.0.3 =
- (16 Jun 2011) - Tested compatible with WP 3.2 Beta 2
- "Number of available extra fields" setting is now for each form # instead of all forms.
- CAPTCHA audio feature removed.
- Misc. admin Fixes.
- Another fix for empty autoresponder field tags were showing.
- Calendar style improvements.

= 3.0.2 =
- (08 Jun 2011) - Fixed error: Undefined variable: securimage_url 
- Added id tags to submit and reset buttons.
- Updated Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 3.0.1 =
- (02 Jun 2011) - CAPTCHA Audio feature is disabled by Mike Challis until further notice because a proof of concept code CAPTCHA solving exploit was released - Security Advisory - SOS-11-007. CAPTCHA image is not involved.
- Security enhancements for possible low level XSS exploit in admin settings: thanks to [Julio Potier](http://secu.boiteaweb.fr/).
- Fix javascript error when CAPTCHA audio is disabled.
- Fixed missing width/height attributes for CAPTCHA images.
- Fixed backslash problem on Restore Settings tool.
- Fixed empty autoresponder field tags were showing.
- Fixed various style improvements for admin error messages.
- Added a popup alert for when changing the number of forms or extra fields.
- Added setting: CSS style for CAPTCHA input field on the contact form.
- Added Norwegian Bokmal (nb_NO) - Translated by [Tore Johnny Bratveit](http://punktlig-ikt.no)

= 3.0 =
- (25 Apr 2011) - Added (extra fields) default text can be set for text or textarea.
- Added (extra fields) max length can be set for password, text or textarea.
- Added (extra fields) attributes can be set for password, text or textarea.
- Added (extra fields) validation regex can be set for password, text or textarea.
- Added (extra fields) validation regex fail message can be set for password, text or textarea.
- Added (extra fields) label or input CSS can be set individually for any extra field.
- Added (extra fields) HTML before and after can be set.
- Added (extra fields) 'email' extra field type with validation.
- Added (extra fields) 'url' extra field type with validation.
- Added (extra fields) first option of select field type can be in brackets to indicate [Please select].
- Added 'email' field pulls up proper keyboard on iphone/ipad.
- Added optional [form_label] tag for subject. 
- Added time format option for 'time' extra field (select 12 or 24 hour).
- Improved extra fields admin GUI.
- Added Field labels are bold when HTML email is enabled.
- Fix - trim spaces on extra field multiple options.
- Fix - attached field allowed types separated by spaces.
- Fix - bug in restore tool that could cause data loss.
- Email validate DNS check disabled by default because some servers have big delay.

= 2.9.8.6 =
- (27 Mar 2011) - Fix HTML notes would not appear before a fieldset open.
- Fix horizontal display feature for radio and multiple checkbox after post.
- Fixed date calender on some themes by adding a high z-index to the CSS.
- Added ability to use comma in "Email To:" name. If you need to use a comma besides the one needed to separate the name and email, escape it with a back slash, like this: \,
- Added ability to specify cc and bcc in "Email To:". Example: Webmaster,user1@example.com;[cc]user2@example.com;[bcc]user3@example.com
- Added more WordPress logged in user details to the email message footer.
- Added optional "Are you sure?" form submit popup message (message can also be changed).
- Added Arabic (ar) partial translation - Translated by Jasmine Hassan

= 2.9.8.5 =
- (08 Mar 2011) - Improved required date fields
- Added optional form "reset" button.
- Fixed escaped comma bug in Restore Tool

= 2.9.8.4 =
- (23 Feb 2011) - Fixed broken "fields to ignore" feature. 
- File attach type and size labels can be changed.

= 2.9.8.3 =
- (14 Feb 2011) - Improvement: javascript is only loaded on pages when it is conditionally needed.
- Fixed 2 label alignment problems with some themes.
- Fixed possible javascript conflict that can break the redirect feature.
- Fixed extra fields were not accepting zero.
- More name labels can be changed.
- Added new feature: Copy Settings Tool. This tool can copy your contact form settings from one form number to any of your other forms. Use to copy just the style settings, or all the settings from one form.
- Added more shortcode optional settings [see FAQ page](http://www.fastsecurecontactform.com/shortcode-options) 
- Added hidden field can accept query input. [see FAQ page](http://www.fastsecurecontactform.com/query-string-parameters)
- Other fixes and enhancements

= 2.9.8.2 =
- (07 Feb 2011) - Fix critical error "invalid argument supplied foreach () /si-contact-form.php on line 474". Sorry for the inconvenience.
- Fix "Enable data export after the message" setting would not uncheck.
- Changed default setting: If you use Contact Form 7 to DB Extension Plugin, be sure to visit the form edit page and enable this setting: "Enable data export after the message", because it is not enabled by default.

= 2.9.8.1 =
- (07 Feb 2011) - Added Silent Send feature: Use to send the posted data to another form or 3rd party API [See FAQ](http://www.fastsecurecontactform.com/send-form-data-elsewhere)
- Fixed blank from email when email address was set to "not available".
- Added features: Now you can add tags to print posted data for the email subject, autoresponder subject, or the autoresponder message.
To see instructions, just click "help" next to any of these fields on the form edit page. [See FAQ](http://www.fastsecurecontactform.com/tip-add-email-autoresponder)
- Added feature: Data Export settings to manage what fields are allowed to be exported or ignored for each form. Also email can be disabled when exporting data.

= 2.9.8 =
- (29 Jan 2011) - Added setting: "Enable to receive email as HTML instead of plain text." Enable if you want the email message sent as HTML format. HTML format is desired if you want to avoid a 70 character line wordwrap when you copy and paste the email message. Normally the email is sent in plain text wordwrapped 70 characters per line to comply with most email programs. 
- Added new settings in the "Redirect" section: You can Enable posted data to be sent as a query string on the redirect URL. This can be used to send the posted data via GET query string to a another form. 
- New settings for redirect: "Query string fields to ignore", "Query string fields to rename", and "Disable email sending (use only when required while you have enabled query string on the redirect URL)". [See FAQ](http://www.fastsecurecontactform.com/sending-data-by-query-string)
- Added version indicator and new version reminder to settings page.
- Fixed a multiple email problem if using the same form in several posts. Anyway, it is recommended to use the form in a page or sidebar, not a post.
- Fixed bug where form number was reset to 4 after trying to increase over 99.
- Fixed so now one CAPTCHA random position always has to be a number so that a 4 letter swear word could never appear.
- Many small fixes and enhancements. 
- Added Romanian (ro_RO) - Translated by [Anunturi Jibo](http://www.jibo.ro)

= 2.9.7.1 =
- (19 Jan 2011) - Added ability to pre-fill in form fields from a URL query string. [see FAQ page](http://www.fastsecurecontactform.com/query-string-parameters)
- Select Single posted data fix.

= 2.9.7 =
- (13 Jan 2011) - Added a new hook for other plugins to use (just after mail sent). This can be used to export the posted data and attachments to a database. A plugin for this is in the works, stay tuned.
- Added new Select-multiple extra field type.
- Added new feature to set the css style for Label and Field in the same row, or switch back to Label on Top [see FAQ](http://www.fastsecurecontactform.com/form-labels-left)
- Added new Setting for "Form Label". You can enter a label for your form to keep track of what you are using it for.
- Fixes and improvements for better mail delivery and to reduce the emails from going to the spam folder.
If you are not getting email, set the setting "E-mail From" to an email address on the same domain as your web site. This will improve mail delivery.
[mail problem faq](http://www.fastsecurecontactform.com/email-does-not-send).
- Added new setting: "Enable when web host requires "Mail From" strictly tied to domain email account". If your form does not send any email, then set the "E-mail From" setting to an address on the same web domain as your web site. If email still does not send, also check this setting. (ie: some users report this is required by yahoo small business web hosting) .
- Added ability to use comma in extra field labels for Checkbox, Radio, Select, or Select-multiple. If you need to use a comma besides the one needed to separate the label, escape it with a back slash, like this: \,
- Improved Akismet spam check API code.
- Fixed required option for checkbox multiple, now all field types can be required as needed. 
- Fixed all fields with multiple options can have a default option: checkbox, radio, select, or select-multiple.
- Other small Fixes and improvements.    
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.9.6 =
- (29 Dec 2010) - Removed advanced options link. Too many people could not find it. All options are now always available on the form edit page.
- Added new setting for "Autoresponder E-mail "From" address:" 

= 2.9.5.4 =
- (17 Dec 2010) - Advanced Options are now open by default for new installs.
- Some changes to admin settings page.

= 2.9.5.3 =
- (14 Dec 2010) - Added Geolocation for the email footer. Install my Visitor Maps plugin and the sender's city, state, country will automatically be included in the email footer. 
- Rename CAPTCHA font files all lower case to avoid a rare FTP problem.

= 2.9.5.2 =
- (30 Nov 2010) - Fix - last update included an old file causing the new 'hidden' and 'password' fields not to show up on form.

= 2.9.5.1 =
- (19 Nov 2010) - Added extra field types for 'hidden' and 'password' entry.
- Added option to preserve white space on Message field. Normally the Message field will have all extra white space removed. Enabling this setting will allow all the Message field white space to be preserved.
- Updated Farsi(Persian)(fa_IR) partial translation - Translated by Ramin Firooz

= 2.9.5 =
- (05 Nov 2010) - Added extra field type for 'time' entry.
- Small code improvements for CAPTCHA function.
- Added Farsi(Persian)(fa) partial translation - Translated by Ramin Firooz

= 2.9.4.1 =
- (27 Oct 2010) - Fix Backup Settings tool download hang on Firefox.
- Added Japanese (ja) - Translated by [Ichiro Kozuka]

= 2.9.4 =
- (11 Oct 2010) - Added new option for what should happen if Akismet determines the message is spam. If you select "block spam messages". If Akismet determines the message is spam: An error will display "Invalid Input - Spam?" and the form will not send. If you select "tag as spam and send anyway". If Akismet determines the message is spam: The message will send and the subject will begin with "Akismet: Spam". This way you can have Akismet on and be sure not to miss a message.
- Added more years to the extra date field date selector (1930-2037).
- Fixed order of extra field options on admin settings page.
- Added Arabic (ar) - Translated by [Mouad] translated only the public side of the form
- Updated Russian (ru_RU) - Translated by [Petr Perstnev]
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.9.3 =
- (20 Sep 2010) - Added new setting so Akismet can now be set to off for the forms. The new setting is individual to each form.
- Added new email footer message for "Akismet Spam Check: passed".
- Added more date format types for the calendar date setting.

= 2.9.2 =
- (05 Sep 2010) - Fixed bug that caused form radio or select default to always be 0.
- Fixed form action URL posted to wrong page when form is in a sidebar widget.
- New transparent audio and refresh images for the CAPTCHA, will look better on dark themes.
- Updated Danish (da_DK) - Translated by [GeorgWP](http://wordpress.blogos.dk/wpdadkdownloads/)
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
- Updated French (fr_FR) - Translated by [Daniel](http://Rhialto.com/)

= 2.9.1 =
- (28 Aug 2010) - Fixed critical HTML errors on admin settings page.
- Improved autoresponder settings (now in new fieldset) on admin settings page.
- Added new setting: "Enable using HTML in autoresponder E-mail message".
- Added new setting: "Advanced Options - Stay Open" to make the Advance Options always stay open.

= 2.9 =
- (23 Aug 2010) - New style for admin settings page.
- New preview form feature for admin settings page. [more about preview mode](http://www.fastsecurecontactform.com/tip-form-preview)
- Added automatic SSL support for the CAPTCHA URL.
- Added download count and star rating on admin options page. 
- Improved error messages when detecting possible email injection attempts.
- [New official FAQ at FastSecureContactForm.com](http://www.fastsecurecontactform.com/faq-wordpress-version)

= 2.8.3 =
- (15 Aug 2010) - New homepage: http://www.FastSecureContactForm.com/
- Added attachment directory permissions check.
- Fixed error when increasing number of fields.
- Renamed plugin to "Fast Secure Contact Form"
- Added rel="nofollow" tag to CAPTCHA Audio and Refresh links for better SEO.
- Removed CAPTCHA WAV sound files, mp3 ones smaller size.
- Updated Dutch
- Updated Italian

= 2.8.2 =
- (07 Aug 2010) - More improvements to the CAPTCHA test page. 
- Added captcha-temp directory permission check to alert the admin if there is a problem. This check is on the admin settings page, the captcha test page, and when posting the captcha.

= 2.8.1 =
- (01 Aug 2010) - PHP Sessions are no longer required for the CAPTCHA. The new method uses temporary files to store the CAPTCHA codes until validation. PHP sessions can still be reactivated by unchecking the setting: "Use CAPTCHA without PHP session".
- Added Akismet spam prevention status to the contact form settings page, so you can know if Akismet is protecting or not.
- Added more help notes to the admin settings page.
- Fixed extra forms were not deleted when when decreasing the number of available forms.
- Fixed to redirect to Form 1 when changing the number of available forms.
- Improved the CAPTCHA Test Page.

= 2.8 =
- (25 Jul 2010) - Added much requested Backup/restore tool. You can backup/restore all your forms or single forms and settings using a tool at the bottom of the settings page.
- Added new settings: 'Autoresponder E-mail From name', and 'Autoresponder E-mail "Reply To" address'.
- Added ability to set subject by URL GET method: ?si_contact_subject=I would like to adopt Freddy.
- Fixed email "Reply to" header to be from sender when appropriate so that replying to the email goes back to sender.
- More improvements to email sending and testing functions.
- Improved fieldset feature. Renamed fieldset(group) to fieldset(box-open) and added fieldset(box-close).  
- Minor improvements to display.
- Updated German

= 2.7.4 =
- (19 Jul 2010) - Fixed blank subject when using optional email subject list.
- Fixed AutoResponder is from person filling the form instead of from WordPress admin.
- Fixed email delivery for some servers that require 5th parameter -f to PHP mail function.
- Updated FAQ page for better help when mail is not sending: http://wordpress.org/extend/plugins/si-contact-form/faq/
- Improved the E-mail test feature at the bottom of the settings page, use to troubleshooting mail delivery problems.
- Added option to use geekMail library for sending mail, try when having mail delivery problems.
- Added more style settings.
- Other small improvements.

= 2.7.3 =
- (12 Jul 2010) - Critical fix for broken checkbox feature in IE8 caused by version 2.7.2
- Added more date format types for the date extra field.

= 2.7.2 =
- (11 Jul 2010) - Added "Name field format:" so you can select how the name field is formatted on the form. Name, First Name, Last Name, etc. 
- Added autoresponder E-mail message feature. Enable when you want the form to automatically answer with an autoresponder E-mail message.
- Added Optional notes/help for extra form fields. Use the optional notes/help to print some notes or instructions before a form field. This is for the form display only, not E-mail. HTML is allowed. 
- Fixed "Attachment upload failed" message when attachment is not set to required.
- Improved email newline handling(some windows servers are really picky).
- Improved CAPTCHA image alignment and other minor fixes.
- Updated Danish (da_DK) - Translated by [GeorgWP](http://wordpress.blogos.dk/wpdadkdownloads/)
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
- Updated German (de_DE) - Translated by [Rico]

= 2.7.1 =
- (04 Jul 2010) - Added fieldset(group) feature to extra fields. The fieldset is used to draw a box around related form elements.
- Added more language strings to date field.
- Added setting: "Date field - Calendar Start Day of the Week:"
- Added new setting: "Move extra fields to after the Message field."
- Fixed rare problem on some servers, CAPTCHA image had missing letters.

= 2.7 =
- (27 Jun 2010) - Added file attachment feature, see here for details: http://wordpress.org/support/topic/416371
- Added setting: "Enable users to send HTML code in the textarea extra field types."(off by default).
- Added setting: "Enable sender information in E-mail footer."(on by default).
- Added HTML anchor tags to bring focus to the form when used on a page with lots of content before the form. 

= 2.6.5 =
- (23 Jun 2010) - CSS fix form field margins were too big on Twenty Ten Theme in WP 3.0
- Single checkbox can have a comma in the label(as long is there is no semicolon because then it becomes a multi-checkbox).

= 2.6.4 =
- (11 Jun 2010) - Added ability to set both a name and email on the "E-mail From (optional):" field .You can enter just an email: user1@example.com
Or enter name and email: webmaster,user1@example.com 
- Fixed missing shortcode example on admin page.
- Fixed so subject prefix can be blank.
- Added more field indicator options in Advanced Options - Fields
- Added Finnish (fi) - Translated by [Mikko Vahatalo](http://www.guimikko.com/) 
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.6.3 =
- (30 May 2010) - Added setting to switch from wordpress mail function to PHP mail function. This setting can resolve some rare mail delivery problems.
- Fixed so checkboxes can have default selected.
- Fixed HTML5 compatible(with CAPTCHA flash audio off).
- Fixed required indicator now has proper class `<span class="required">*</span>`. 
- Fixed syntax bug on extra 'date' fields.
- Fixed for Simple Facebook Connect compatibility (fixes broken CAPTCHA caused by SFC Like and Share plugins).

= 2.6.2 =
- (21 May 2010) - Fixed major bug: All text and textarea extra fields were missing from email. Sorry for the inconvenience. There are so many who use it,  I updated it right away.

= 2.6.1 =
- (19 May 2010) - Fixed bug: all checkboxes appeared selected in the email. 
- Fixed to allow HTML in extra field labels.

= 2.6 =
- (19 May 2010) - Fix for XHTML Strict compliance.
- Improved CAPTCHA CSS code (better alignment captcha, refresh, and audio images).
- Added advanced options for date format on extra 'date' fields (mm/dd/yyyy, dd/mm/yyyy).
- Added advanced options to set checkboxes with children (Pizza Toppings:,olives;mushrooms;cheese;ham;tomatoes).
- Added advanced options to set a default selected item for select and radio fields.
- Added advanced options to make name, email, subject, or message fields (not_available, not_required, or required).
this feature can be used to make an anonymous comment form. Also can be used to disable name and email to make them reordered when using extra fields.
- Updated Spanish (es_ES) - Translated by Sergio Torres.

= 2.5.6 =
- (15 May 2010) - Made WP3 Compatible.

= 2.5.5 =
- (07 May 2010) - Fixed to be compatible with *www.com domain name.
- Added extra field type for "date", this new field can be used for a hotel registration form and uses a popup "Epoch DHTML Calendar" 1.06 by Nick Baicoianu from meanfreepath.com

= 2.5.4 =
- (01 May 2010) - Fixed small issue with "enable hidden message" option.
- Fixed small issue with "email from" option.
- Improved CAPTCHA testpage.

= 2.5.3 =
- (23 Apr 2010) - Added Dutch (nl_NL) - Translated by [Mark Visser]
- Added Swedish (sv_SE) - Translated by [Daniel Persson](http://walktheline.boplatsen.se/)


= 2.5.2 = 
- (16 Apr 2010) - Added Hungarian (hu_HU) - Translated by [Jozsef Burgyan](http://dmgmedia.hu)
- Updated Polish (pl_PL) - Translated by [Pawel Mezyk]

= 2.5.1 =
- (09 Apr 2010) - Fixed bug in reset styles feature.
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 2.5.0 =
- (05 Apr 2010) - Added setting to add optional drop down list for email subject.
- Added setting to make the CAPTCHA image smaller.
- Added settings to increase number of forms and have more extra fields (editing code is no longer needed).
- Fixed so multiple forms can be on the same page. 
- Added editable text label setting for (* denotes required field).
- Added more style settings in Advanced Options. You can use inline css, or add a class property to be used by your own stylsheet.
Acceptable Examples:
text-align:left; color:#000000; background-color:#CCCCCC;
style="text-align:left; color:#000000; background-color:#CCCCCC;"
class="input"
- Split code into 4 smaller files for better performance.
- Other bug fixes.

= 2.0.2 =
- (16 Mar 2010) - Added radio and select configuration error checking. Fix display of radio input fields to be on separate lines.
- Fixed multiple BCC feature, it was only accepting one BCC.
- Updated German (de_DE) and Bulgarian (bg_BG)

= 2.0.1 =
- (06 Feb 2010) - Fix Invalid Input error when the word "donkey" is in the input string.
- Added Polish (pl_PL) - Translated by [Pawel Mezyk]
- Fixed Greek language file name.

= 2.0 =
- (26 Jan 2010) - Added required field indicators (can be disabled in settings if you do not like them).
- Added setting to adjust redirect delay seconds(range of 1-5 recommended).
- Added setting to hide message entry, now you can hide subject and message fields for use as a newsletter signup.
- Added selectable extra field types: text, textarea, checkbox, radio, select. Note: When using select or radio field types, first enter the label and a comma. Next include the options separating with a semicolon like this example: Color:,Red;Green;Blue 

= 1.9.6 =
- (31 Dec 2009) - New setting for a few people who had problems with the text transparency "Disable CAPTCHA transparent text (only if captcha text is missing on the image, try this)".
- Added Hebrew, Israel (he_IL) - Translated by [Asaf Chertkoff FreeAllWeb GUILD](http://web.freeall.org) 

= 1.9.5 =
- (04 Dec 2009) - Fix slashes issue on some servers.
- More improvements for CAPTCHA images and fonts.

= 1.9.4 =
- (30 Nov 2009) - Fix blank CAPTCHA text issue some users were having.
- Added CAPTCHA difficulty level setting on the settings page (Low, Medium, Or High).
- Added Portuguese (pt_PT) - Translated by [AJBFerreira Blog](http://pws.op351.net/)

= 1.9.3 =
- (23 Nov 2009) - Fix completely broke CAPTCHA, sorry about that

= 1.9.2 =
- (23 Nov 2009) - Added 5 random CAPTCHA fonts.
- Added feature to increase the number of extra form fields available (see faq if you need it).
- Fixed fail over to GD Fonts on the CAPTCHA when TTF Fonts are not enabled in PHP (it was broken).

= 1.9.1 =
- (21 Nov 2009) - Fixed Flash audio was not working.
- Added Spanish (es_ES) - Translated by [Valentin Yonte Rodriguez](http://www.activosenred.com/)

= 1.9 =
- (20 Nov 2009) - Updated to SecureImage CAPTCHA library version 2.0
- New CAPTCHA features include: increased CAPTCHA difficulty using mathematical distortion, streaming MP3 audio of CAPTCHA code using Flash, random audio distortion, better distortion lines, random backgrounds and more.
- Other minor fixes.

= 1.8.4 =
- (10 Nov 2009) - Added advanced option to edit the CAPTCHA input field size.
- Other minor fixes.

= 1.8.3 =
- (09 Nov 2009) - Fix Submit button spacing.

= 1.8.2 =
- (03 Nov 2009) - Added feature to increase the number of forms available (see faq if you need it).
- Fix for settings not being deleted when plugin is deleted from admin page.
- Updated Italian (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")
- Added Albanian (sq_AL) - Translated by [Romeo Shuka](http://www.romeolab.com)

= 1.8.1 =
- (02 Nov 2009) - Fixed error "Could not read CAPTCHA cookie" on some installs using version 1.8

= 1.8 =
- (02 Nov 2009) - Added Multi-Form feature that allows you to have up to four different forms on your site.
- Added Bulgarian (bg_BG) - Translated by [Dimitar Atanasov](http://chereshka.net/)

= 1.7.7 =
- (30 Oct 2009) - Fixed issue on some sites with blank css fields that caused image misalignment.
- Added advanced option to edit the CSS style for border on the contact form.

= 1.7.6 =
- (27 Oct 2009) - Added advanced option to edit the CSS style for contact drop down select on the contact form.
- HTML validation fix.

= 1.7.5 =
- (21 Oct 2009) - Added Chinese (zh_CN) - Translated by [Awu](http://www.awuit.cn/) 
- Added Greek (el) - Translated by [Ioannis](http://www.jbaron.gr/)

= 1.7.4 =
- (03 Oct 2009) - Fixed advanced setting: CSS style for form input fields. Changing background color did not work.
- Added setting to Enable hidden E-mail subject (removes subject field from contact form).  

= 1.7.3 =
- (01 Oct 2009) - Updated links to my other plugins.
- Danish (da_DK) - Updated by [Georg / Team Blogos](http://wordpress.blogos.dk)

= 1.7.2 =
- (30 Sep 2009) - Fixed settings were deleted at deactivation. Settings are now only deleted at uninstall.

= 1.7.1 =
- (29 Sep 2009) - Fix credit link position. 
- Some people wanted to change the error messages for the contact form. Advanced settings fields can be filled in to override the standard included error messages.

= 1.7 =
- (28 Sep 2009) - Added 8 optional extra fields. Some people requested extra contact form fields that could be used for phone number, company name, etc. To enable an extra field from the advanced options, just enter a label. Then check if you want the field to be required or not.

= 1.6.8 =
- (22 Sep 2009) - Fix, some sites reported a image path problem. (I think it is correct now). 

= 1.6.7 =
- (22 Sep 2009) - Fix, some sites reported a path problem with "Blog address" is different domain than "WordPress address".
- Added setting to enable upper case alphabet correction. 
- Added more fields in "advanced options".
- Minor code cleanup.

= 1.6.6 =
- (21 Sep 2009) - Fix "Invalid Input" error on installations where "Blog address" is different domain than "WordPress address".
- More sanity checks on Form DIV Width setting.
- Added ability to use dashes or underscores in shortcode: `[si-contact-form]` or `[si_contact_form]`.

= 1.6.5 =
- (18 Sep 2009) - Added proper nonce protection to options forms. 
- Added option to reset the styles to defaults (incase you tried to adjust them and did not like the results).
- Fixed typo in file name for Portuguese - Brazil language (pt_BR).
- Fixed several language files [BR, FR, NO, DE...] had word "Submit" spelled as "submit".

= 1.6.4 =
- (14 Sep 2009) - Added E-mail test feature in options, if you are not receiving mail, try it. It will display troubleshooting information.
- Added error check for wp_mail send, this is helful to troubleshoot mail delivery. 
- Added a warning message on Options page for when the web host has mail() function disabled.

= 1.6.3 =
- (13 Sep 2009) - Added new advanced options for CSS style of captcha image, audio image, reload image, and submit button.
- Fixed coding for XHTML Strict validation.
- Added Ukrainian language (uk_UA) - Translated by [Wordpress.Ua](http://wordpress.ua/)

= 1.6.2 =
- (11 Sep 2009) - Added new feature in options: "Enable checking DNS records for the domain name when checking for a
valid E-mail address." It looks for any of the following: A record, a CNAME record, or MX record.(enabled by default).
- Updated FAQ

= 1.6.1 =
- (11 Sep 2009) - Fixes error if you are upgrading from prior version: Fatal error: Call to a member function `si_contact_migrate()` on a non-object in si-contact-form.php on line 1461
- If you get this error and cannot access your WP site: the manual fix is to delete the `si-contact-form.php` file from the `plugins/si-contact-form/` directory, your site will start working again. Then you can install this new version.  
See this [forum post](http://wordpress.org/support/topic/309925)

= 1.6 =
- (10 Sep 2009) - Auto form fill is automatically skipped for any user with administrator role.
- New option: Auto form fill can be enabled/disabled in advanced options(enabled by default).
- Plugin options are now stored in a single database row instead of many. (it will auto migrate/cleanup old database rows).
- Language files are now stored in the `si-contact-form/languages` folder.
- Options are deleted when this plugin is deleted.
- Added help links on options page.
- Added Portuguese Brazil (pt_BR) - Translated by [Rui Alao]
- Updated Russian (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)
- Updated Turkish (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)

= 1.5 =
- (9 Sep 2009) - New feature: I added an "advanced options" section to the options page. Some people wanted to change the text labels for the contact form.
These advanced options fields can be filled in to override the standard included field titles.
- Other minor code changes.

= 1.4.4 =
- (08 Sep 2009) - Fixed possible error: "mail could not be sent because host may have disabled email function()"

= 1.4.3 =
- (08 Sep 2009) - Fixed redirect/logout problem on admin menu reported by a user.
- Removed blog name from top of email message body.

= 1.4.2 =
- (07 Sep 2009) - Added configurable email subject prefix in options.
- Added configurable border width in options.
- Auto form fill is now disabled for admin, but still works for other logged in users.
- Other minor fixes.

= 1.4.1 =
- (06 Sep 2009) - Added feature: Auto form fill email address and name (username) on the contact form for logged in users.
- Added feature: prints "From a WordPress user: `<username>`" on email footer for logged in users.
- Added feature: Date/Time timestamp on email footer (uses Date/Time format from general options setting).
- Added Russian Language (ru_RU) - Translated by [Bezraznizi](http://www.sprestij.ru/)

= 1.4 =
- (06 Sep 2009) Now uses wp_mail function so that users who use the SMTP mail plugins will be supported.
- Now sends email encoded in the character encoding you write your blog in, (UTF-8 is recommended) see `Settings`, `Reading` admin options page.
- New feature: Now you can have multiple E-mails per contact, this is called a CC(Carbon Copy). If you need to add more than one contact, see the example: click "help" on the `Contact Form Options Page`. 
- Fixed error "Bad parameters to mail() function" reported by a couple users.
- Fixed error "Call to undefined function `mb_detect_encoding()`" reported by one user.
- Many hours were put into this free plugin. Please donate, even small amounts like $2.99 are welcome.

= 1.3 =
- (04 Sep 2009) Added Feature: This contact form sends E-mail with UTF-8 character encoding for US and International character support.(fee FAQ)
- Added Danish Language (da_DK) - Translated by [Thomas J. Langer](http://www.ohyeah-webdesign.dk)
- fixed an issue with the "Welcome introduction" field translation not translating.

= 1.2.5 =
- (02 Sep 2009) Added Norwegian language (nb_NO) - Translated by [Roger Sylte](http://roger.inro.net/)

= 1.2.4 =
- (02 Sep 2009) Added German Language (de_DE) - Translated by [Sebastian Kreideweiss](http://sebastian.kreideweiss.info/)

= 1.2.3 =
- (01 Sep 2009) Fixed email validation on some windows servers
- Added Traditional Chinese, Taiwan Language (zh_TW) - Translated by [Cjh]
- Added French language (fr_FR) - Translated by [Pierre Sudarovich](http://pierre.sudarovich.free.fr/)

= 1.2.2 =
- (31 Aug 2009) Added Italian language (it_IT) - Translated by [Gianni Diurno](http://gidibao.net/ "Gianni Diurno")

= 1.2.1 =
- (31 Aug 2009) Added more diagnostic test scripts: a Cookie Test, Captcha test, and a PHP Requirements Test.
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`

= 1.2 =
- (31 Aug 2009) Translations were not working

= 1.1.7 =
- (31 Aug 2009) Cookie error improvements.

= 1.1.6 =
- (30 Aug 2009) Added a Cookie Test to help diagnose if a web browser has cookies disabled.
Click on the "Test if your PHP installation will support the CAPTCHA" link on the Options page.
or open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/test/index.php`

= 1.1.5 = 
- (30 Aug 2009) Improved Akismet function (checks for `wordpress_api_key`)
- Hide CAPTCHA for registered users is now disabled by default(configurable in Options)

= 1.1.4 =
- (29 Aug 2009) Improved `ctf_validate_email` function and fixed a bug that invalidated email address with upper case

= 1.1.3 =
- (29 Aug 2009) Added this script to be used to test if your PHP installation will support the CAPTCHA:
Open this URL in your web browser to run the test:
`/wp-content/plugins/si-contact-form/captcha-secureimage/secureimage_test.php`

= 1.1.2 =
- (28 Aug 2009) Updated Turkish language (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)

= 1.1.1 =
- (28 Aug 2009) Added Turkish language (tr_TR) - Translated by [Tolga](http://www.tapcalap.com/)
- CAPTCHA fix - Added Automatic fail over from TTF Fonts to GD Fonts if the PHP installation is configured without "--with-ttf".
  Some users were reporting there was no error indicating this TTF Fonts not supported condition and the captcha was not working.

= 1.1 =
- (28 Aug 2009) Added multi "email to" contact feature. Add as many contacts as you need in Options. The drop down list on the contact form will be made automatically.

= 1.0.3 =
- (28 Aug 2009) fix options permission bug introduced by last update, sorry

= 1.0.2 =
- (27 Aug 2009) Added Akismet spam protection. Checks the form input with Akismet, but only if Akismet plugin is also installed.
- added settings link to the plugin action links.

= 1.0.1 =
- (26 Aug 2009) fixed deprecated ereg_replace and eregi functions for PHP 5.3+ compatibility when error warnings are on

= 1.0 =
- (26 Aug 2009) Initial Release



