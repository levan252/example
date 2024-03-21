<?php 

if (!function_exists('listAllForPost')) {
    function listAllForPost() {
        try {
            $sql = "
                SELECT 
                    p.id            as p_id,
                    p.category_id   as p_category_id,
                    p.author_id     as p_author_id,
                    p.title         as p_title,
                    p.excerpt       as p_excerpt,
                    p.is_trending   as p_is_trending,
                    p.status        as p_status,
                    p.img_thumnail  as p_img_thumnail,
                    p.img_cover     as p_img_cover,
                    p.created_at    as p_created_at,
                    p.updated_at    as p_updated_at,
                    c.name          as c_name,
                    au.name         as au_name,
                    au.avatar       as au_avatar
                FROM posts as p
                INNER JOIN categories   as c    ON c.id     = p.category_id
                INNER JOIN authors      as au   ON au.id    = p.author_id
                ORDER BY p_id DESC;
            ";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}

if (!function_exists('showOneForPost')) {
    function showOneForPost($id) {
        try {
            $sql = "
                SELECT 
                    p.id            as p_id,
                    p.category_id   as p_category_id,
                    p.author_id     as p_author_id,
                    p.title         as p_title,
                    p.excerpt       as p_excerpt,
                    p.content       as p_content,
                    p.is_trending   as p_is_trending,
                    p.status        as p_status,
                    p.img_thumnail  as p_img_thumnail,
                    p.img_cover     as p_img_cover,
                    p.created_at    as p_created_at,
                    p.updated_at    as p_updated_at,
                    c.name          as c_name,
                    au.name         as au_name,
                    au.avatar       as au_avatar
                FROM posts as p
                INNER JOIN categories   as c    ON c.id    = p.category_id
                INNER JOIN authors      as au   ON au.id   = p.author_id
                WHERE 
                    p.id = :id 
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

if (!function_exists('getTagsForPost')) {
    function getTagsForPost($postID) {
        try {
            $sql = "
                SELECT 
                    t.id    t_id,
                    t.name  t_name
                FROM tags as t
                INNER JOIN post_tag as pt   ON t.id     = pt.tag_id
                WHERE pt.post_id = :post_id;
            ";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->bindParam(':post_id', $postID);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}

if (!function_exists('deleteTagsByPostID')) {
    function deleteTagsByPostID($postID) {
        try {
            $sql = "DELETE FROM post_tag WHERE post_id = :post_id;";

            $stmt = $GLOBALS['conn']->prepare($sql);

            $stmt->bindParam(':post_id', $postID);

            $stmt->execute();

            return $stmt->fetchAll();
        } catch (\Exception $e) {
            debug($e);
        }
    }
}