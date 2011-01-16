<?php
/**
 * The base configurations of the WordPress.
 *
 * This file has the following configurations: MySQL settings, Table Prefix,
 * Secret Keys, WordPress Language, and ABSPATH. You can find more information
 * by visiting {@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} Codex page. You can get the MySQL settings from your web host.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** MySQL settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define('DB_NAME', 'lifesupport_dev');

/** MySQL database username */
define('DB_USER', 'lifesupport_dev');

/** MySQL database password */
define('DB_PASSWORD', 'lifesupport');

/** MySQL hostname */
define('DB_HOST', '192.168.1.148:8889');

/** Database Charset to use in creating database tables. */
define('DB_CHARSET', 'utf8');

/** The Database Collate type. Don't change this if in doubt. */
define('DB_COLLATE', '');

/**#@+
 * Authentication Unique Keys and Salts.
 *
 * Change these to different unique phrases!
 * You can generate these using the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * You can change these at any point in time to invalidate all existing cookies. This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         ' M>8|{7x5|5oE&f.`6*<M|Yp`R|U^hRX&Wvo%tmGtwujlr-r{([ffmUbEo` 8^RH');
define('SECURE_AUTH_KEY',  'Hr=dOgzU$_OYKK~qceFf@/,9:]+cEKA&r`z`#=E)X,*6M`@ibhW.edM~>d2yW/xv');
define('LOGGED_IN_KEY',    'RnrWt,mbBG>,x Fsty3F|(/e|Wa0(YyGq|Clqp6+AJHxM3M,QZCwBODWmH8G)1r+');
define('NONCE_KEY',        'pl*oAlw>hXcKEo&yr;g[t]|fMsxUJ[Gpq.&Tfu(iUDr@FwVHGtcds*/U&J1}IE{2');
define('AUTH_SALT',        'rJOk*;rD;_Q/hrbEs<7,%2CP4OlvY+QbJ`ApW_W@8:1nzH>}YrF@/ P/71c`j@KI');
define('SECURE_AUTH_SALT', 'Q_*V]H pEM|z ADaYfy3@I/l+_T1N#w[uQgAq-E?*CH3^~xch8pDfPLT, m$&xZ]');
define('LOGGED_IN_SALT',   '5`q1bD;%K%(3hht3*Jj;UipiSW9Q4[(]Hw$J@kjh K~8@i|GQzK</_+Dk2`AAlxy');
define('NONCE_SALT',       'vWpWazN3:Hmw,dkU/<jA)Pf>GWc%]VwiCN#*TEhxA`j@LZ#ydbBb<. N}[(GO^dJ');

/**#@-*/

/**
 * WordPress Database Table prefix.
 *
 * You can have multiple installations in one database if you give each a unique
 * prefix. Only numbers, letters, and underscores please!
 */
$table_prefix  = 'lifesupport_wp_';

/**
 * WordPress Localized Language, defaults to English.
 *
 * Change this to localize WordPress.  A corresponding MO file for the chosen
 * language must be installed to wp-content/languages. For example, install
 * de.mo to wp-content/languages and set WPLANG to 'de' to enable German
 * language support.
 */
define ('WPLANG', '');

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 */
define('WP_DEBUG', false);

/**
 * Since we're working on different platforms, I want the site URL to be dynamic.
 */
define('WP_SITEURL', 'http://' . $_SERVER['HTTP_HOST']);
define('WP_HOME', 'http://' . $_SERVER['HTTP_HOST']);

/* That's all, stop editing! Happy blogging. */

/** Absolute path to the WordPress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');
