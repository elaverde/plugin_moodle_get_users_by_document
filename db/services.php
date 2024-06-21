<?php
$functions = array(
    'local_user_get_users_by_field' => array(
        'classname'   => 'local_user_get_users_by_field\external\get_users',
        'methodname'  => 'get_users',
        'classpath'   => 'local/user_get_users_by_field/classes/external/get_users.php',
        'description' => 'Obtener usuarios por correo electrónico, nombre de usuario o campos personalizados como identificación.',
        'type'        => 'read',
        'capabilities' => ''
    ),
);

$services = array(
    'Servicio Personalizado de API de Usuarios UDEC' => array(
        'functions' => array ('local_user_get_users_by_field'),
        'restrictedusers' => 0,
        'enabled' => 1
    ),
);