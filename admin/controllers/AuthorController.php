<?php

function authorListAll()
{
    $title = 'Danh sách author';
    $view = 'authors/index';
    $script = 'datatable';
    $script2 = 'authors/script';
    $style = 'datatable';

    $authors = listAll('authors');

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function authorShowOne($id)
{
    $author = showOne('authors', $id);

    if (empty($author)) {
        e404();
    }

    $title = 'Chi tiết author: ' . $author['name'];
    $view = 'authors/show';

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function authorCreate()
{
    $title = 'Thêm mới author';
    $view = 'authors/create';

    if (!empty($_POST)) {

        $data = [
            'name' => $_POST['name'] ?? null,
            'avatar' => $_FILES['avatar'] ?? null
        ];

        validateAuthorCreate($data);

        $avatar = $_FILES['avatar'] ?? null;
        if (!empty($avatar) && $avatar['size'] > 0) {
            $data['avatar'] = upload_file($avatar, 'uploads/authors/');
        }

        insert('authors', $data);

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=authors');
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateAuthorCreate($data)
{
    // name - bắt buộc, độ dài tối đa 50 ký tự, Không được trùng
    // avatar - size <= 2M, chỉ chấp nhận PNG, JPG, JPEG

    $errors = [];

    if (empty($data['name'])) {
        $errors[] = 'Trường name là bắt buộc';
    } else if (strlen($data['name']) > 50) {
        $errors[] = 'Trường name độ dài tối đa 50 ký tự';
    } else if (!checkUniqueName('authors', $data['name'])) {
        $errors[] = 'Name đã được sử dụng';
    }


    if (!empty($data['avatar']) && $data['avatar']['size'] > 0) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['avatar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường avatar có dung lượng nhỏ hơn 2M';
        } else if (!in_array($data['avatar']['type'], $typeImage)) {
            $errors[] = 'Trường avatar chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['data'] = $data;

        header('Location: ' . BASE_URL_ADMIN . '?act=author-create');
        exit();
    }
}

function authorUpdate($id)
{
    $author = showOne('authors', $id);

    if (empty($author)) {
        e404();
    }

    $title = 'Cập nhật author: ' . $author['name'];
    $view = 'authors/update';

    if (!empty($_POST)) {
        $data = [
            "name" => $_POST['name'] ?? null,
            'avatar' => $_FILES['avatar'] ?? null
        ];

        validateAuthorUpdate($id, $data);

        $avatar = $_FILES['avatar'] ?? null;
        if (!empty($avatar)) {
            $data['avatar'] = upload_file($avatar, 'uploads/authors/');
        }

        update('authors', $id, $data);

        if (
            !empty($avatar)                                 // Có upload file
            && !empty($author['avatar'])                    // có giá trị
            && !empty($data['avatar'])                      // upload file thành công
            && file_exists(PATH_UPLOAD . $author['avatar']) // Phải còn file tồn tại trên hệ thống
        ) {
            unlink(PATH_UPLOAD . $author['avatar']);
        }

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=author-update&id=' . $id);
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateAuthorUpdate($id, $data)
{
    // name - bắt buộc, độ dài tối đa 50 ký tự, Không được trùng
    // avatar - size <= 2M, chỉ chấp nhận PNG, JPG, JPEG

    $errors = [];

    if (empty($data['name'])) {
        $errors[] = 'Trường name là bắt buộc';
    } else if (strlen($data['name']) > 50) {
        $errors[] = 'Trường name độ dài tối đa 50 ký tự';
    } else if (!checkUniqueNameForUpdate('authors', $id, $data['name'])) {
        $errors[] = 'Name đã được sử dụng';
    }

    if (!empty($data['avatar'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['avatar']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường avatar có dung lượng nhỏ hơn 2M';
        } else if (!in_array($data['avatar']['type'], $typeImage)) {
            $errors[] = 'Trường avatar chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;

        header('Location: ' . BASE_URL_ADMIN . '?act=author-update&id=' . $id);
        exit();
    }
}

function authorDelete($id)
{
    $author = showOne('authors', $id);

    if (empty($author)) {
        e404();
    }

    delete2('authors', $id);

    if (
        !empty($author['avatar'])                       // có giá trị
        && file_exists(PATH_UPLOAD . $author['avatar']) // Phải còn file tồn tại trên hệ thống
    ) {
        unlink(PATH_UPLOAD . $author['avatar']);
    }

    $_SESSION['success'] = 'Thao tác thành công!';

    header('Location: ' . BASE_URL_ADMIN . '?act=authors');
    exit();
}
