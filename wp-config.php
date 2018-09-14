<?php
/** 
 * Configuración básica de WordPress.
 *
 * Este archivo contiene las siguientes configuraciones: ajustes de MySQL, prefijo de tablas,
 * claves secretas, idioma de WordPress y ABSPATH. Para obtener más información,
 * visita la página del Codex{@link http://codex.wordpress.org/Editing_wp-config.php Editing
 * wp-config.php} . Los ajustes de MySQL te los proporcionará tu proveedor de alojamiento web.
 *
 * This file is used by the wp-config.php creation script during the
 * installation. You don't have to use the web site, you can just copy this file
 * to "wp-config.php" and fill in the values.
 *
 * @package WordPress
 */

// ** Ajustes de MySQL. Solicita estos datos a tu proveedor de alojamiento web. ** //
/** El nombre de tu base de datos de WordPress */
define('DB_NAME', 'wp-piola');

/** Tu nombre de usuario de MySQL */
define('DB_USER', 'wp-piola');

/** Tu contraseña de MySQL */
define('DB_PASSWORD', 'sarasa');

/** Host de MySQL (es muy probable que no necesites cambiarlo) */
define('DB_HOST', 'localhost');

/** Codificación de caracteres para la base de datos. */
define('DB_CHARSET', 'utf8mb4');

/** Cotejamiento de la base de datos. No lo modifiques si tienes dudas. */
define('DB_COLLATE', '');

/**#@+
 * Claves únicas de autentificación.
 *
 * Define cada clave secreta con una frase aleatoria distinta.
 * Puedes generarlas usando el {@link https://api.wordpress.org/secret-key/1.1/salt/ servicio de claves secretas de WordPress}
 * Puedes cambiar las claves en cualquier momento para invalidar todas las cookies existentes. Esto forzará a todos los usuarios a volver a hacer login.
 *
 * @since 2.6.0
 */
define('AUTH_KEY', ']zk1>Cz?pj/YZ0ZM^]*;bB_&f5LIA^FW$L*Udva)ph{p?~q&iVzYDl<&X*p/%?8]');
define('SECURE_AUTH_KEY', 'l_}=:T0 sE4Oq @fj*e8oh=(lv=[p0e-sC_ cJwK<9V),N)9f0~>SYPkV1m_!k4E');
define('LOGGED_IN_KEY', 'Kt7LCt)OV-}9Tnz(Tum*C!F `Ma1K~:~2AEb2c:(iKlU~Y>P72t!2?LXv/Rw4ibq');
define('NONCE_KEY', '5O2,~K}dy>_R(]CGAoO[$RWD+1O[dH<i]UV?6VM)wsQ>5z&HonvxC!69rU7w=gpy');
define('AUTH_SALT', 'yhDQ}$-kH?pP,CF(;4<icW06L(c|DKfg^CewW ,6OR02M();c_Qpsg~D}A&TSz25');
define('SECURE_AUTH_SALT', '.kLn4/z.bKs}LP--`=5r:Y%=THW={4(63!)ty t^gP7ikBllfGla/C?}y3$u[bO&');
define('LOGGED_IN_SALT', '/q(ot@S9*tiO+yR~H!p9szgRf$[.D2dT`EMOJJ9Dy5Xp6xJ+Db`4tVYW+74mtid)');
define('NONCE_SALT', 'vjJTii }?~H~R^~`PmK.<)}<LAG/Q8sh*)OOhO;]A^>u(q+#9eEc&&},xXzGt4tJ');

/**#@-*/

/**
 * Prefijo de la base de datos de WordPress.
 *
 * Cambia el prefijo si deseas instalar multiples blogs en una sola base de datos.
 * Emplea solo números, letras y guión bajo.
 */
$table_prefix  = 'wp_';


/**
 * Para desarrolladores: modo debug de WordPress.
 *
 * Cambia esto a true para activar la muestra de avisos durante el desarrollo.
 * Se recomienda encarecidamente a los desarrolladores de temas y plugins que usen WP_DEBUG
 * en sus entornos de desarrollo.
 */
define('WP_DEBUG', false);

/* ¡Eso es todo, deja de editar! Feliz blogging */

/** WordPress absolute path to the Wordpress directory. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/** Sets up WordPress vars and included files. */
require_once(ABSPATH . 'wp-settings.php');

