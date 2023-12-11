<?php
/**
 * The base configuration for WordPress
 *
 * The wp-config.php creation script uses this file during the installation.
 * You don't have to use the web site, you can copy this file to "wp-config.php"
 * and fill in the values.
 *
 * This file contains the following configurations:
 *
 * * Database settings
 * * Secret keys
 * * Database table prefix
 * * ABSPATH
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** Database settings - You can get this info from your web host ** //
/** The name of the database for WordPress */
define( 'DB_NAME', 'db_binapedia' );

/** Database username */
define( 'DB_USER', 'root' );

/** Database password */
define( 'DB_PASSWORD', '' );

/** Database hostname */
define( 'DB_HOST', 'localhost' );

/** Database charset to use in creating database tables. */
define( 'DB_CHARSET', 'utf8mb4' );

/** The database collate type. Don't change this if in doubt. */
define( 'DB_COLLATE', '' );

/**#@+
 * Authentication unique keys and salts.
 *
 * Change these to different unique phrases! You can generate these using
 * the {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}.
 *
 * You can change these at any point in time to invalidate all existing cookies.
 * This will force all users to have to log in again.
 *
 * @since 2.6.0
 */
define( 'AUTH_KEY',         '{>IRXE0]iH|g?CD[70K54$^lj@LqpnxJJq2 6l!b!/ltkPT,L7I]rS^Hn^u0q]OV' );
define( 'SECURE_AUTH_KEY',  'asN!S=<z^AP;;.> E54c%oHcz*Vhv@,OXB_H[lkf;[Yx{pNwRw3IcY+mjR g% nd' );
define( 'LOGGED_IN_KEY',    'N1Ch_>K^[p~FRn:Qe+R@ x$9nZ+!P x24~1,SyL~FI#0y3JB*4l3@?U`$BN=>AWP' );
define( 'NONCE_KEY',        '/BP84C-Zx@pi# @#E+jAymxHwsL{;Y* oLf16{HTNIWGUvb9:#t8.V~dG^3N .S<' );
define( 'AUTH_SALT',        '[-I!>>)s23WtzN>[_i&hbE||yqYP %DB$Nl>Kbra~p,YX]AS!MWH1ahn_vwz?dH5' );
define( 'SECURE_AUTH_SALT', 'L(JDH<fO;8ME2w }egk<mF>[bCnUR./Yv<5?@N!zXu)=` &dLx^~;]9^JCzqBqlG' );
define( 'LOGGED_IN_SALT',   '..VJH6eT+Np?A7YPGJog{B_svg@o8UZF}OsHPnGFBGfY?0;_%UZV,ob}N+w-i}*p' );
define( 'NONCE_SALT',       '2D(V*tsS(/70$R3&uj.%}B3y<Ka.bf:%dObb)=j>][Nm56k=10q4kkH-1tb[0*SP' );

/**#@-*/

/**
 * WordPress database table prefix.
 *
 * You can have multiple installations in one database if you give each
 * a unique prefix. Only numbers, letters, and underscores please!
 */
$table_prefix = 'wp_';

/**
 * For developers: WordPress debugging mode.
 *
 * Change this to true to enable the display of notices during development.
 * It is strongly recommended that plugin and theme developers use WP_DEBUG
 * in their development environments.
 *
 * For information on other constants that can be used for debugging,
 * visit the documentation.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Add any custom values between this line and the "stop editing" line. */



/* That's all, stop editing! Happy publishing. */

/** Absolute path to the WordPress directory. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Sets up WordPress vars and included files. */
require_once ABSPATH . 'wp-settings.php';
