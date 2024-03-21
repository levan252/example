<?php 

session_start();

// Require file trong commons
require_once '../commons/env.php';
require_once '../commons/helper.php';
require_once '../commons/connect-db.php';
require_once '../commons/model.php';

// Require file trong controllers và models
require_file(PATH_CONTROLLER_ADMIN);
require_file(PATH_MODEL_ADMIN);

// Điều hướng
$act = $_GET['act'] ?? '/';

// Kiểm tra xem user đã đăng nhập chưa
middleware_auth_check($act);

match($act) {
    '/' => dashboard(),

    // Authen
    'login' => authenShowFormLogin(),
    'logout' => authenLogout(),

    // CRUD User
    'users' => userListAll(),
    'user-detail' => userShowOne($_GET['id']),
    'user-create' => userCreate(),
    'user-update' => userUpdate($_GET['id']),
    'user-delete' => userDelete($_GET['id']),

    // CRUD Category
    'categories' => categoryListAll(),
    'category-detail' => categoryShowOne($_GET['id']),
    'category-create' => categoryCreate(),
    'category-update' => categoryUpdate($_GET['id']),
    'category-delete' => categoryDelete($_GET['id']),

    // CRUD tag
    'tags' => tagListAll(),
    'tag-detail' => tagShowOne($_GET['id']),
    'tag-create' => tagCreate(),
    'tag-update' => tagUpdate($_GET['id']),
    'tag-delete' => tagDelete($_GET['id']),

    // CRUD author
    'authors' => authorListAll(),
    'author-detail' => authorShowOne($_GET['id']),
    'author-create' => authorCreate(),
    'author-update' => authorUpdate($_GET['id']),
    'author-delete' => authorDelete($_GET['id']),

     // CRUD author
    

    // CRUD post
    'posts' => postListAll(),
    'post-detail' => postShowOne($_GET['id']),
    'post-create' => postCreate(),
    'post-update' => postUpdate($_GET['id']),
    'post-delete' => postDelete($_GET['id']),

    // CRUD product
    'products' => productListAll(),
    'product-detail' => productShowOne($_GET['id']),
    'product-create' => productCreate(),
    'product-update' => productUpdate($_GET['id']),
    'product-delete' => productDelete($_GET['id']),

    // Setting
    'setting-form' => settingShowForm(),
    'setting-save' => settingSave(),
};

require_once '../commons/disconnect-db.php';