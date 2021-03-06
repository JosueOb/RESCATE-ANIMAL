<?php
// Inicializando errores
ini_set('display_errors',1);
ini_set('display_starup_errors',1);
error_reporting(E_ALL);
session_start();

//Archivos requeridos
require_once '../vendor/autoload.php';
//use
use Aura\Router\RouterContainer;
use Illuminate\Database\Capsule\Manager as Capsule;

$dotenv = Dotenv\Dotenv::create(__DIR__ . '/..');
$dotenv->load();

//eloquent - Illuminate DB
$capsule = new Capsule;

$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => getenv('DB_HOST'),
    'database'  => getenv('DB_NAME'),
    'username'  => getenv('DB_USER'),
    'password'  => getenv('DB_PASS'),
    'charset'   => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix'    => '',
]);
// Make this Capsule instance available globally via static methods... (optional)
$capsule->setAsGlobal();

// Setup the Eloquent ORM... (optional; unless you've used setEventDispatcher())
$capsule->bootEloquent();

// Zend-diactoros PSR-7
// Se crea un objeto Zend-Diactoros, el cual contiene la información
//de un request (petición), En este caso se utiliza para obtener el
//path o destino y archivos como imágenes.
$request = Zend\Diactoros\ServerRequestFactory::fromGlobals(
    $_SERVER,
    $_GET,
    $_POST,
    $_COOKIE,
    $_FILES
);

//Aura Router
$routerContainer = new RouterContainer();
$map = $routerContainer->getMap();
// Rutas del visitante
$map->get('visitorIndex','/',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'getVisitorIndex'
]);
$map->post('postEmail','/',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'enviarCorreo'
]);
$map->get('getGalleryDog','/gallery',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'getGalleryDog'
]);
$map->post('postGalleryDog','/gallery',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'getGalleryDog'
]);
$map->get('getDogInfo','/dog/info/{dogId}',[
    'controller'=>'App\Controllers\VisitorController',
    'action'=>'getDogInfo'
]);



// Rutas del administrador
$map->get('adminIndex','/admin',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getAdminIndex',
    'auth'=>true
]);
$map->get('addUser','/user/add',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getUserAdd',
    'auth'=>true
]);
$map->post('postAddUser','/user/add',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getUserAdd',
    'auth'=>true
]);
$map->get('logUser','/user/log',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getLogUser',
    'auth'=>true
]);
$map->get('deleteUser','/user/delete/{userId}',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getDeleteUser',
    'auth'=>true
]);
$map->get('getUpdateUser','/user/update/{userId}',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getUpdateUser',
    'auth'=>true
]);
$map->post('postUpdateUser','/user/update/{userId}',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getUpdateUser',
    'auth'=>true
]);

$map->get('changePassUser','/user/changePass/{userId}',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getChangePassUser',
    'auth'=>true
]);
$map->post('postChangePassUser','/user/changePass/{userId}',[
    'controller'=>'App\Controllers\AdminController',
    'action'=>'getChangePassUser',
    'auth'=>true
]);
//Rutas del usuario de la fundación
$map->get('userIndex','/user',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getUserIndex',
    'auth'=>true
]);
$map->get('getEditProfile','/perfil',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getEditProfile',
    'auth'=>true
]);
$map->post('postEditProfile','/perfil',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getEditProfile',
    'auth'=>true
]);
$map->get('getChangePassword','/user/changePass',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getChangePassword',
    'auth'=>true
]);
$map->post('postChangePassword','/user/changePass',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getChangePassword',
    'auth'=>true
]);
$map->get('getLogDogs','/dog/log',[
    'controller'=>'App\Controllers\UserController',
    'action'=>'getLogDogs',
    'auth'=>true
]);
$map->get('getAdoptedDogs','/dog/adopted',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getAdoptedDogs',
    'auth'=>true
]);
$map->get('getInactiveDogs','/dog/inactive',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getInactiveDogs',
    'auth'=>true
]);

$map->get('getAddDog','/dog/add',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getAddDog',
    'auth'=>true
]);
$map->post('postAddDog','/dog/add',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getAddDog',
    'auth'=>true
]);
$map->get('getDeleteDog','/dog/delete/{dogId}',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getDeleteDog',
    'auth'=>true
]);
$map->get('getUpdateDog','/dog/update/{dogId}',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getUpdateDog',
    'auth'=>true
]);
$map->post('postUpdateDog','/dog/update/{dogId}',[
    'controller'=>'App\Controllers\DogController',
    'action'=>'getUpdateDog',
    'auth'=>true
]);



//Rutas de autenticación
$map->get('loginIndex','/login',[
    'controller'=>'App\Controllers\AccessController',
    'action'=>'getLogin'
]);
$map->get('logout','/logout',[
    'controller'=>'App\Controllers\AccessController',
    'action'=>'getLogout'
]);
$map->post('auth','/auth',[
    'controller'=>'App\Controllers\AccessController',
    'action'=>'postLogin'
]);


$matcher = $routerContainer->getMatcher();
$route = $matcher->match($request);

if(!$route){
    echo 'Entrada no válida';
}else{
    $handlerData = $route->handler;
    $needsAuth = $handlerData['auth'] ?? false;
    $sessionUser = $_SESSION['user'] ?? null;
    if($needsAuth && !$sessionUser){
        $controllerName = 'App\Controllers\AccessController';
        $actionName = 'getLogout';
        
    }else{
        $controllerName = $handlerData['controller'];
        $actionName = $handlerData['action'];
    }

    // add route attributes to the request
    foreach ($route->attributes as $key => $val) {
        $request = $request->withAttribute($key, $val);
    }

    $controller = new $controllerName;
    // $controller->$actionName($request);
    $response = $controller->$actionName($request);

    // en este caso se realiza el foreach para el redireccionamiento
    foreach ($response->getHeaders() as $name => $values) {
        foreach ($values as $value) {
            header(sprintf('%s: %s',$name, $value),false);//funcion que imprime los headers
        }
    }
    http_response_code($response->getStatusCode());//permite establecer cual es codigo de respuesta

    echo $response->getBody();//se imprime la respuesta HTML
}