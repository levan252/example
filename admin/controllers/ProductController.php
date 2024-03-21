<?php

function productListAll()
{
    $title      = 'Danh sách product';
    $view       = 'products/index';
    $script     = 'datatable';
    $script2    = 'products/script';
    $style      = 'datatable';

    $products = listAllForProduct();

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function productShowOne($id)
{
    $post = showOneForProduct($id);

    if (empty($product)) {
        e404();
    }

    $title  = 'Chi tiết post: ' . $product['pr_title'];
    $view   = 'products/show';

    $tags = getTagsForProduct($id);

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function productCreate()
{
    $title      = 'Thêm mới product';
    $view       = 'products/create';
    $script     = 'datatable';
    $script2    = 'products/script';

    $categories = listAll('categories');
    $brands    = listAll('brandss');
    $tags       = listAll('tags');

    if (!empty($_POST)) {

        $data = [
            'category_id'   => $_POST['category_id']    ?? null,
            'brand_id'      => $_POST['brand_id']      ?? null,
            'title'         => $_POST['title']          ?? null,
            'descripsion'       => $_POST['descripsion']        ?? null,
            'created_at'       => $_POST['created_at']        ?? null,
            'updated_at'       => $_POST['updated_at']        ?? null,
            'quantity'   => $_POST['quantity']    ?? 0,
            'status'        => $_POST['status']         ?? STATUS_DRAFT,
            'image'  => get_file_upload('image'),
            
        ];

        validateProductCreate($data);

        $imagecover= $data['image'];
        if (is_array($imagecover)) {
            $data['image'] = upload_file($imagecover, 'uploads/products/');
        }

        

        try {
            $GLOBALS['conn']->beginTransaction();

            $productID = insert_get_last_id('products', $data);

            // Xử lý lưu Post - Tags
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tagID) {
                    insert('product_tag', [
                        'product_id' => $productID,
                        'tag_id' => $tagID,
                    ]);
                }
            }

            $GLOBALS['conn']->commit();
        } catch (Exception $e) {
            $GLOBALS['conn']->rollBack();

            if (is_array($imagecover) 
                && !empty($data['image'])
                && file_exists(PATH_UPLOAD . $data['image'])) {
                unlink(PATH_UPLOAD . $data['image']);
            }

           
            
            debug($e);
        }

        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=products');
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateProductCreate($data)
{
    $errors = [];

    if (empty($data['image'])) {
        $errors[] = 'Trường image là bắt buộc';
    }
    elseif (is_array($data['image'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường image có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['image']['type'], $typeImage)) {
            $errors[] = 'Trường image chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

    

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        $_SESSION['data'] = $data;

        header('Location: ' . BASE_URL_ADMIN . '?act=product-create');
        exit();
    }
}

function productUpdate($id)
{
    $post = showOneForProduct($id);

    if (empty($product)) {
        e404();
    }

    $title      = 'Cập nhật product: ' . $product['pr_title'];
    $view       = 'products/update';
    $script     = 'datatable';
    $script2    = 'products/script';

    $categories     = listAll('categories');
    $brands        = listAll('brands');
    $tags           = listAll('tags');

    $tagsForProduct    = getTagsForProduct($id);
    $tagIDsForProduct  = array_column($tagsForProduct, 't_id');

    if (!empty($_POST)) {
        $data = [
            'category_id'   => $_POST['category_id']    ?? $post['pr_category_id'],
            'brand_id'     => $_POST['brand_id']      ?? $post['pr_brand_id'],
            'title'         => $_POST['title']          ?? $post['pr_title'],
            'descripsion'       => $_POST['descriprion']        ?? $post['pr_descriprion'],
            'quantity'       => $_POST['quantity']        ?? $post['pr_quantity'],
            'status'   => $_POST['status']    ?? $post['pr_status'],
            'created_at'        => date('Y-m-d H:i:s'),
            'updated_at'    => date('Y-m-d H:i:s'),
            'image'  => get_file_upload('image', $post['pr_image']),
            
        ];

        validateProductUpdate($id, $data);

        $imagecover = $data['image'];
        if (is_array($imagecover)) {
            $data['image'] = upload_file($imagecover, 'uploads/products/');
        }


        try {
            $GLOBALS['conn']->beginTransaction();

            update('products', $id, $data);

            // Xử lý lưu Post - Tags

            deleteTagsByProductID($id);
            
            if (!empty($_POST['tags'])) {
                foreach ($_POST['tags'] as $tagID) {
                    insert('product_tag', [
                        'product_id' => $id,
                        'tag_id' => $tagID,
                    ]);
                }
            }

            $GLOBALS['conn']->commit();
        } catch (Exception $e) {
            $GLOBALS['conn']->rollBack();

            if (is_array($imagecover) 
                && !empty($data['image'])
                && file_exists(PATH_UPLOAD . $data['image'])) {
                unlink(PATH_UPLOAD . $data['image']);
            }


            debug($e);
        }

        if (
            !empty($imagecover)
            && !empty($post['image'])
            && !empty($data['image'])
            && file_exists(PATH_UPLOAD . $post['image'])
        ) {
            unlink(PATH_UPLOAD . $post['image']);
        }


        $_SESSION['success'] = 'Thao tác thành công!';

        header('Location: ' . BASE_URL_ADMIN . '?act=post-update&id=' . $id);
        exit();
    }

    require_once PATH_VIEW_ADMIN . 'layouts/master.php';
}

function validateProductUpdate($id, $data)
{
    $errors = [];

    if (empty($data['image'])) {
        $errors[] = 'Trường image là bắt buộc';
    }
    elseif (is_array($data['image'])) {
        $typeImage = ['image/png', 'image/jpg', 'image/jpeg'];

        if ($data['image']['size'] > 2 * 1024 * 1024) {
            $errors[] = 'Trường image có dung lượng nhỏ hơn 2M';
        } 
        else if (!in_array($data['image']['type'], $typeImage)) {
            $errors[] = 'Trường image chỉ chấp nhận định dạng file: png, jpg, jpeg';
        }
    }

   

    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        
        header('Location: ' . BASE_URL_ADMIN . '?act=post-update&id=' . $id);
        exit();
    }
}

function productDelete($id)
{
    $post = showOne('products', $id);

    if (empty($product)) {
        e404();
    }

    try {
        $GLOBALS['conn']->beginTransaction();

        deleteTagsByProductID($id);

        delete2('products', $id);

        $GLOBALS['conn']->commit();
    } catch (Exception $e) {
        $GLOBALS['conn']->rollBack();

        debug($e);
    }

    if (
        !empty($post['image'])
        && file_exists(PATH_UPLOAD . $post['image'])
    ) {
        unlink(PATH_UPLOAD . $post['image']);
    }

    
    $_SESSION['success'] = 'Thao tác thành công!';

    header('Location: ' . BASE_URL_ADMIN . '?act=products');
    exit();
}
