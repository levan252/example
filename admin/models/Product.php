<?php 

if (!function_exists('listAllForProduct')) {
    function listAllForProduct() {
        try {
            $sql ="
            SELECT 
              pr.id AS pr_id,
              pr.category_id AS pr_category_id,
              pr.brand_id AS pr_brand_id,
              pr.tittle AS pr_tittle,
              pr.image AS pr_image,
              pr.description AS pr_description,
              pr.quantity AS pr_quantity,
              pr.status AS pr_status,
              pr.created_at AS pr_created_at,
              pr.updated_at AS pr_updated_at,  -- Assuming this is the intended column
              c.name AS c_name,
              br.name AS br_name
            FROM products AS pr
            INNER JOIN categories AS c ON c.id = pr.category_id
            INNER JOIN brands AS br ON br.id = pr.brand_id
            ORDER BY pr_id DESC;  -- Corrected alias
          ";
            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}

if (!function_exists('showOneForProduct')) {
    function showOneForProduct($id) {
        try {
            $sql = "
            SELECT 
                pr.id            as pr_id,
                pr.category_id   as pr_category_id,
                pr.brand_id      as pr_brand_id,
                pr.title         as pr_title,
                pr.image         as pr_image,
                pr.description   as pr_desciption,
                pr.quantity      as pr_quantity,
                pr.status        as pr_status,
                pr.created_at    as pr_created_at,
                pr.updated_ed    as pr_updated_ed,
                c.name           as c_name,
                br.name          as br_name,
            FROM posts as p
            INNER JOIN categories   as c    ON c.id     = pr.category_id
            INNER JOIN brands      as br   ON br.id    = pr.brand_id
                WHERE 
                    pr.id = :id 
                LIMIT 1
            ";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->bindParam(":id", $id);

            $stmt->execute();

            return $stmt->fetch();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}

if (!function_exists('getTagsForProduct')) {
    function getTagsForProduct($productID) {
        try {
            $sql = "
                SELECT 
                    t.id    t_id,
                    t.name  t_name
                FROM tags as t
                INNER JOIN product_tag as pt   ON t.id     = pt.tag_id
                WHERE pt.product_id = :product_id;
            ";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->bindParam(':post_id', $productID);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}

if (!function_exists('deleteTagsByProductID')) {
    function deleteTagsByProductID($productID) {
        try {
            $sql = "DELETE FROM product_tag WHERE product_id = :product_id;";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->bindParam(':product_id', $productID);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}