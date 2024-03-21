<div class="container-fluid">
    <!-- Page Heading -->
    <h1 class="h3 mb-2 text-gray-800">
        <?= $title ?>

        <a href="<?= BASE_URL_ADMIN ?>?act=product-create" class="btn btn-primary">Create</a>
    </h1>

    <!-- DataTales Example -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">
                DataTables
            </h6>
        </div>
        <div class="card-body">

            <?php if (isset($_SESSION['success'])) : ?>
                <div class="alert alert-success">
                    <?= $_SESSION['success'] ?>
                </div>
                <?php unset($_SESSION['success']); ?>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Tittle</th>
                            <th>Image</th>
                            <th>Mô tả</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Ngày nhập</th>
                            <th>Ngày sửa</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tfoot>
                        <tr>
                            <th>ID</th>
                            <th>Category</th>
                            <th>Brand</th>
                            <th>Tittle</th>
                            <th>Image</th>
                            <th>Mô tả</th>
                            <th>Quantity</th>
                            <th>Status</th>
                            <th>Ngày nhập</th>
                            <th>Ngày sửa</th>
                            <th>Action</th>
                        </tr>
                    </tfoot>
                    <tbody>
                        <?php foreach ($products as $product) : ?>
                            <tr>
                                <td><?= $product['pr_id'] ?></td>
                                <td><?= $product['c_name'] ?></td>
                                <td><?= $product['br_name'] ?></td>
                                <td><?= $product['pr_tittle'] ?></td>
                                
                                
                                <td>
                                    <img src="<?= BASE_URL . $product['pr_image'] ?>" alt="" width="100px">
                                </td>
                                <td><?= $product['pr_description'] ?></td>
                                <td><?= $product['pr_quantity'] ?></td>
                                
                                <td><?= $product['pr_status'] ?></td>
                                <td><?= $product['pr_created_at'] ?></td>
                                <td><?= $product['pr_updated_at'] ?></td>
                                <td>
                                    <a href="<?= BASE_URL_ADMIN ?>?act=product-detail&id=<?= $product['pr_id'] ?>" class="btn btn-info">Show</a>
                                    <a href="<?= BASE_URL_ADMIN ?>?act=product-update&id=<?= $product['pr_id'] ?>" class="btn btn-warning">Update</a>
                                    <a href="<?= BASE_URL_ADMIN ?>?act=product-delete&id=<?= $product['pr_id'] ?>" 
                                        onclick="return confirm('Bạn có chắc chắn xóa không?')"
                                        class="btn btn-danger">Delete</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>