<?php

function postListAll()
{
    $title      = 'Danh sách post';
    $view       = 'posts/index';
    $script     = 'datatable';
    $script2    = 'posts/script';
    $style      = 'datatable';

    $posts = listAllForPost();

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function postShowOne($id)
{
    $post = showOneForPost($id);

    if (empty($post)) {
        e404();
    }

    $title  = 'Chi tiết post: ' . $post['p_title'];
    $view   = 'posts/show';

    $tags = getTagsForPost($id);

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function postCreate()
{
    $title      = 'Thêm mới post';
    $view       = 'posts/create';
    $script     = 'datatable';
    $script2    = 'posts/script';

    $categories = listAll('categories');
    $authors    = listAll('authors');
    $tags       = listAll('tags');

    if (!empty($_POST)) {

        $data = [
            'category_id'   => $_POST['category_id']    ?? null,
            'author_id'     => $_POST['author_id']      ?? null,
            'title'         => $_POST['title']          ?? null,
            'excerpt'       => $_POST['excerpt']        ?? null,
            'content'       => $_POST['content']        ?? null,
            'is_trending'   => $_POST['is_trending']    ?? 0,
            'status'        => $_POST['status']         ?? STATUS_DRAFT,
            'img_thumnail'  => get_file_upload('img_thumnail'),
            'img_cover'     => get_file_upload('img_cover'),
        ];

        validatePostCreate($data);

        $imgThumnail = $data['img_thumnail'];
        if (is_array($imgThumnail)) {
            $data['img_thumnail'] = upload_file($imgThumnail, 'uploads/posts/');
        }

        $imgCover = $data['img_cover'];
        if (is_array($imgCover)) {
            $data['img_cover'] = upload_file($imgCover, 'uploads/posts/');
        }

        try {
            $GLOBALS['conn']->beginTransaction();

            $postID = insert_get_last_id('posts', $data);

            // Xử lý lưu Post - Tags
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tagID) {
                    insert('post_tag', [
                        'post_id' => $postID,
                        'tag_id' => $tagID,
                    ]);
                }
            }

            $GLOBALS['conn']->commit();
        } catch (Exception $e) {
            $GLOBALS['conn']->rollBack();

            if (is_array($imgThumnail) 
                && !empty($data['img_thumnail'])
                && file_exists(PATH_UPLOAD . $data['img_thumnail'])) {
                unlink(PATH_UPLOAD . $data['img_thumnail']);
            }

            if (is_array($imgCover) 
                && !empty($data['img_cover'])
                && file_exists(PATH_UPLOAD . $data['img_cover'])) {
                unlink(PATH_UPLOAD . $data['img_cover']);
            }
            
            debug($e);
        }

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=posts');
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validatePostCreate($data)
{
    $errors = [];

    if (empty($data['img_thumnail'])) {
        $errors[] = 'Trường img_thumnail là bắt buộc';
    }
    elseif (is_array($data['img_thumnail'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['img_thumnail']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường img_thumnail có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['img_thumnail']['type'], $typeImage)) {
            $errors[] = 'Trường img_thumnail chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    if (is_array($data['img_cover'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['img_cover']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường img_cover có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['img_cover']['type'], $typeImage)) {
            $errors[] = 'Trường img_cover chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['data'] = $data;

        header('Location: ' . BASE_URL_ADMIN . '?act=post-create');
        exit();
    }
}

function postUpdate($id)
{
    $post = showOneForPost($id);

    if (empty($post)) {
        e404();
    }

    $title      = 'Cập nhật post: ' . $post['p_title'];
    $view       = 'posts/update';
    $script     = 'datatable';
    $script2    = 'posts/script';

    $categories     = listAll('categories');
    $authors        = listAll('authors');
    $tags           = listAll('tags');

    $tagsForPost    = getTagsForPost($id);
    $tagIDsForPost  = array_column($tagsForPost, 't_id');

    if (!empty($_POST)) {
        $data = [
            'category_id'   => $_POST['category_id']    ?? $post['p_category_id'],
            'author_id'     => $_POST['author_id']      ?? $post['p_author_id'],
            'title'         => $_POST['title']          ?? $post['p_title'],
            'excerpt'       => $_POST['excerpt']        ?? $post['p_excerpt'],
            'content'       => $_POST['content']        ?? $post['p_content'],
            'is_trending'   => $_POST['is_trending']    ?? $post['p_is_trending'],
            'status'        => $_POST['status']         ?? $post['p_status'],
            'updated_at'    => date('Y-m-d H:i:s'),
            'img_thumnail'  => get_file_upload('img_thumnail', $post['p_img_thumnail']),
            'img_cover'     => get_file_upload('img_cover', $post['p_img_cover'])
        ];

        validatePostUpdate($id, $data);

        $imgThumnail = $data['img_thumnail'];
        if (is_array($imgThumnail)) {
            $data['img_thumnail'] = upload_file($imgThumnail, 'uploads/posts/');
        }

        $imgCover = $data['img_cover'];
        if (is_array($imgCover)) {
            $data['img_cover'] = upload_file($imgCover, 'uploads/posts/');
        }

        try {
            $GLOBALS['conn']->beginTransaction();

            update('posts', $id, $data);

            // Xử lý lưu Post - Tags

            deleteTagsByPostID($id);
            
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tagID) {
                    insert('post_tag', [
                        'post_id' => $id,
                        'tag_id' => $tagID,
                    ]);
                }
            }

            $GLOBALS['conn']->commit();
        } catch (Exception $e) {
            $GLOBALS['conn']->rollBack();

            if (is_array($imgThumnail) 
                && !empty($data['img_thumnail'])
                && file_exists(PATH_UPLOAD . $data['img_thumnail'])) {
                unlink(PATH_UPLOAD . $data['img_thumnail']);
            }

            if (is_array($imgCover) 
                && !empty($data['img_cover'])
                && file_exists(PATH_UPLOAD . $data['img_cover'])) {
                unlink(PATH_UPLOAD . $data['img_cover']);
            }

            debug($e);
        }

        if (
            !empty($imgThumnail)
            && !empty($post['img_thumnail'])
            && !empty($data['img_thumnail'])
            && file_exists(PATH_UPLOAD . $post['img_thumnail'])
        ) {
            unlink(PATH_UPLOAD . $post['img_thumnail']);
        }

        if (
            !empty($imgCover)
            && !empty($post['img_cover'])
            && !empty($data['img_cover'])
            && file_exists(PATH_UPLOAD . $post['img_cover'])
        ) {
            unlink(PATH_UPLOAD . $post['img_cover']);
        }

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=post-update&id=' . $id);
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validatePostUpdate($id, $data)
{
    $errors = [];

    if (empty($data['img_thumnail'])) {
        $errors[] = 'Trường img_thumnail là bắt buộc';
    }
    elseif (is_array($data['img_thumnail'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['img_thumnail']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường img_thumnail có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['img_thumnail']['type'], $typeImage)) {
            $errors[] = 'Trường img_thumnail chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    if (is_array($data['img_cover'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['img_cover']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường img_cover có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['img_cover']['type'], $typeImage)) {
            $errors[] = 'Trường img_cover chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        
        header('Location: ' . BASE_URL_ADMIN . '?act=post-update&id=' . $id);
        exit();
    }
}

function postDelete($id)
{
    $post = showOne('posts', $id);

    if (empty($post)) {
        e404();
    }

    try {
        $GLOBALS['conn']->beginTransaction();

        deleteTagsByPostID($id);

        delete2('posts', $id);

        $GLOBALS['conn']->commit();
    } catch (Exception $e) {
        $GLOBALS['conn']->rollBack();

        debug($e);
    }

    if (
        !empty($post['img_thumnail'])
        && file_exists(PATH_UPLOAD . $post['img_thumnail'])
    ) {
        unlink(PATH_UPLOAD . $post['img_thumnail']);
    }

    if (
        !empty($post['img_cover'])
        && file_exists(PATH_UPLOAD . $post['img_cover'])
    ) {
        unlink(PATH_UPLOAD . $post['img_cover']);
    }

    $_SESSION['success'] = 'Thao tác thành công!';

    header('Location: ' . BASE_URL_ADMIN . '?act=posts');
    exit();
}
