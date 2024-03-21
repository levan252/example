<?php 

function homeIndex() {
    $users = getAllUser();

    require_once PATH_VIEW . 'home.php';
}