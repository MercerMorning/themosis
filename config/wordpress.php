<?php

/*
|--------------------------------------------------------------------------
| Notes - README
|--------------------------------------------------------------------------
|
| You can add as many WordPress constants as you want here. Just make sure
| to add them at the end of the file or at least after the "WordPress
| authentication keys and salts" section.
|
*/

/*
|--------------------------------------------------------------------------
| WordPress authentication keys and salts
|--------------------------------------------------------------------------
|
| @link https://api.wordpress.org/secret-key/1.1/salt/
|
*/
define('AUTH_KEY',         '/#x~Ox,?_[-?r]%gEm~TWp5MZC-uEnP-YW5OPM%([m,xD(k%0Az;47<|)qCb42Ic');
define('SECURE_AUTH_KEY',  '~z?uQqiG:,)w ZPS-()Nf,%s)#g>XmmVpApf>h+k9!<>([.qziPLs|1pMRWa/hP&');
define('LOGGED_IN_KEY',    'La_zJX~M.Z<SL)wGy.Y+LO_u+)!p>{bfC KF:)fa-#n`DfU<8eb6y+]SuU8$HisC');
define('NONCE_KEY',        '>lEwo#4S18F:eK(78-9cq1lyF-d.pe~7|?yfXD}vC]^6p~=oFp5yG0bII]*CZp`p');
define('AUTH_SALT',        'd+V]*I8j4<~+tf_}Z=U@/KX.%bm.+!gr@|S0-i8^Q2sj&BRsy%8_+6V(|yJ9%d-0');
define('SECURE_AUTH_SALT', 'L90nl;=Ztsi+#teG-vA&2`UGn~5@+!P|X{MfcB)/W-!g&rus@o77nE?yG^Rn+|Fe');
define('LOGGED_IN_SALT',   ' 1c-]OLT]EsU#R|^{Xz 8gARMpCQ/PRMokFc/~Fsv8O{(TZ5t1JIK20(%Q)QjA|a');
define('NONCE_SALT',       'X5Mfw9~z 3kYNqYLv;-%?#Nztf5:nmd{)|Fq+=3%kS@{hV9l^iZ{)i~)#yP-X}o7');

/*
|--------------------------------------------------------------------------
| WordPress database
|--------------------------------------------------------------------------
*/
define('DB_NAME', config('database.connections.mysql.database'));
define('DB_USER', config('database.connections.mysql.username'));
define('DB_PASSWORD', config('database.connections.mysql.password'));
define('DB_HOST', config('database.connections.mysql.host'));
define('DB_CHARSET', config('database.connections.mysql.charset'));
define('DB_COLLATE', config('database.connections.mysql.collation'));

/*
|--------------------------------------------------------------------------
| WordPress URLs
|--------------------------------------------------------------------------
*/
define('WP_HOME', config('app.url'));
define('WP_SITEURL', config('app.wp.url'));
define('WP_CONTENT_URL', WP_HOME.'/'.CONTENT_DIR);

/*
|--------------------------------------------------------------------------
| WordPress debug
|--------------------------------------------------------------------------
*/
define('SAVEQUERIES', config('app.debug'));
define('WP_DEBUG', config('app.debug'));
define('WP_DEBUG_DISPLAY', config('app.debug'));
define('SCRIPT_DEBUG', config('app.debug'));

/*
|--------------------------------------------------------------------------
| WordPress auto-update
|--------------------------------------------------------------------------
*/
define('WP_AUTO_UPDATE_CORE', false);

/*
|--------------------------------------------------------------------------
| WordPress file editor
|--------------------------------------------------------------------------
*/
define('DISALLOW_FILE_EDIT', true);

/*
|--------------------------------------------------------------------------
| WordPress default theme
|--------------------------------------------------------------------------
*/
define('WP_DEFAULT_THEME', 'my-theme');

/*
|--------------------------------------------------------------------------
| Application Text Domain
|--------------------------------------------------------------------------
*/
define('APP_TD', env('APP_TD', 'themosis'));

/*
|--------------------------------------------------------------------------
| JetPack
|--------------------------------------------------------------------------
*/
define('JETPACK_DEV_DEBUG', config('app.debug'));
