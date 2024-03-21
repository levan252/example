<?php

function tagListAll()
{
    $title = 'Danh sách tag';
    $view = 'tags/index';
    $script = 'datatable';
    $script2 = 'tags/script';
    $style = 'datatable';
    
    $tags = listAll('tags');

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function tagShowOne($id)
{
    $tag = showOne('tags', $id);

    if(empty($tag)) {
        e404();
    }

    $title = 'Chi tiết tag: ' . $tag['name'];
    $view = 'tags/show';

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function tagCreate()
{
    $title = 'Thêm mới tag';
    $view = 'tags/create';

    if (!empty($_POST)) {
        
        $data = [
            "name" => $_POST['name'] ?? null,
        ];

        validateTagCreate($data);

        insert('tags', $data);

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=tags');
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateTagCreate($data) {
    // name - bắt buộc, độ dài tối đa 50 ký tự, Không được trùng

    $errors = [];

    if (empty($data['name'])) {
        $errors[] = 'Trường name là bắt buộc';
    } 
    else if(strlen($data['name']) > 50) {
        $errors[] = 'Trường name độ dài tối đa 50 ký tự';
    } 
    else if(! checkUniqueName('tags', $data['name'])) {
        $errors[] = 'Name đã được sử dụng';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['data'] = $data;

        header('Location: ' . BASE_URL_ADMIN . '?act=tag-create');
        exit();
    }
}

function tagUpdate($id)
{
    $tag = showOne('tags', $id);

    if(empty($tag)) {
        e404();
    }

    $title = 'Cập nhật tag: ' . $tag['name'];
    $view = 'tags/update';

    if (!empty($_POST)) {
        $data = [
            "name" => $_POST['name'] ?? null,
        ];

        validateTagUpdate($id, $data);
         
        update('tags', $id, $data);

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=tag-update&id=' . $id);
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateTagUpdate($id, $data) {
    // name - bắt buộc, độ dài tối đa 50 ký tự, Không được trùng

    $errors = [];

    if (empty($data['name'])) {
        $errors[] = 'Trường name là bắt buộc';
    } 
    else if(strlen($data['name']) > 50) {
        $errors[] = 'Trường name độ dài tối đa 50 ký tự';
    }
    else if(! checkUniqueNameForUpdate('tags', $id, $data['name'])) {
        $errors[] = 'Name đã được sử dụng';
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;

        header('Location: ' . BASE_URL_ADMIN . '?act=tag-update&id=' . $id);
        exit();
    }
}

function tagDelete($id)
{
    delete2('tags', $id);

    $_SESSION['success'] = 'Thao tác thành công!';
    
    header('Location: ' . BASE_URL_ADMIN . '?act=tags');
    exit();
}
