<?php 

function userDetail($id) {
    $user = getUserByID($id);

    require_once PATH_VIEW . 'users/detail.php';
}