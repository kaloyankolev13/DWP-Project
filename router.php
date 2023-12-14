<?php
require_once 'controllers/Auth.php'; 
require_once 'controllers/User.php';
require_once 'controllers/Posts.php';
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

    $authObj = new Auth(); // Instantiate the User object
    $userProfileObj = new User(); // Instantiate the User object
    $postsObj = new Posts(); // Instantiate the Posts object


function route($uri, $authObj) {
    $path = parse_url($uri, PHP_URL_PATH);

    switch ($path) {
        case '':
            home();
            break;
        case '/register':
            register();
            break;

        case '/post-detail':
            $postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
            if ($postId) {
                singlePost($postId);
            } else {
                notFound();
            }
            break;

        case '/edit_post':
            $postId = isset($_GET['post_id']) ? $_GET['post_id'] : null;
            if ($postId) {
                editPost($postId);
            } else {
                notFound();
            }
            break;

        case '/login':
            login();
            break;

        case '/profile':
            if (!$authObj->isLoggedIn()) {
                login();
                    exit;
            }
            $userProfile = new User();
            $userId = isset($_GET['user_id']) ? $_GET['user_id'] : null;
            profile($userId, $userProfile);
            break;

        case '/about':
            about();
            break;

        case '/for_you':
            for_you();
            break;

        case '/followers':
            followers();
            break;

        default:
            notFound();
            break;
    }
}


function render($page, $variables = []) {
    extract($variables);
    require __DIR__ . "/pages/{$page}.php";
}

function home() {
    render('home');
}

function create_post() {
    render('create_post');
}

function about() {
    $welcomeMessage = "Welcome to our website!";
    render('about', ['message' => $welcomeMessage]);
}

function profile($userId, $userProfile) {
    if (!$userId) {
        login();
        exit;
    }

    $userDetails = $userProfile->getUserDetails($userId);
    $userPosts = $userProfile->getUserPosts($userId);

    if ($userDetails) {
        render('profile', ['user' => $userDetails, 'posts' => $userPosts]);
    } else {
        notFound();
    }
}


function register() {
    render('register');
}

function for_you() {
    render('for_you');
}

function followers() {
    render('followers');
}

function login() {
    render('login');
}

function post(){
    render('post');
}

function notFound() {
    http_response_code(404);
    render('404');
}

function singlePost($postId) {
    $postsObj = new Posts();  // Make sure to use the global keyword to access the global $postsObj
    $post = $postsObj->fetchPostById($postId);
    if ($post) {
        render('single_post', ['post' => $post]);
    } else {
        notFound();
    }
}

function editPost($postId) {
    // Assuming the user must be logged in to edit a post
    if (!isset($_SESSION['user_id'])) {
        header('Location: login.php');
        exit;
    }
    render('edit_post');

}



?>
