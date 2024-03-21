<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chi tiết người dùng: <?= $user['name'] ?></title>
</head>
<body>
    <h1>Chi tiết người dùng: <?= $user['name'] ?></h1>
    <table>
        <tr>
            <th>Tên trường</th>
            <th>Giá trị</th>
        </tr>
        <tr>
            <td>Name</td>
            <td><?= $user['name'] ?></td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?= $user['email'] ?></td>
        </tr>

    </table>
</body>
</html>