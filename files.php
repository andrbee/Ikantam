<?php
require "libs/db.php";
$user = R::findOne('users', "email = ?", array($_SESSION['cur_user']->email));
if ($_FILES['user__file']['error'] == 0) {
    if (!file_exists("uploads/" . $user->email)) {
        mkdir("uploads/" . $user->email);
    }
    $uploadFile = $_SERVER['DOCUMENT_ROOT'] . "/uploads/" . $user->email . "/" . $_FILES['user__file']['name'];
    $urlFile = "http://" . $_SERVER['HTTP_HOST'] . "/uploads/" . $user->email . "/" . $_FILES['user__file']['name'];

    if (is_uploaded_file($_FILES['user__file']['tmp_name'])) {
        $fileUpload = $_FILES['user__file'];
        if (move_uploaded_file($_FILES['user__file']['tmp_name'], $uploadFile)) {
            $file = R::dispense('usersfiles');
            $file->user_id = $user->id;
            $file->url = $urlFile;
            $file->type = $fileUpload['type'];
            $file->size = $fileUpload['size'];
            R::store($file);
            header('Location: /files.php');
        } else {
            $error = "Ошибка сохранения файла на сервер";
        }
    }
} else {
    $error = "Ошибка загрузки файла";
}


$countFiles = R::count('usersfiles', 'user_id = ?', array($user->id));
$files = R::find('usersfiles', "user_id = ?  ORDER BY id DESC", array($user->id));?>

<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ikantam upload files App</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<div class="container">
    <h3 class="title__my_files">My Files</h3>
</div>
<? if ($countFiles > 0) { ?>
    <div class="container">
        <table class="tbl__files">
            <tr>
                <th>Id</th>
                <th>Url</th>
                <th>Size</th>
                <th>Type</th>
            </tr>
            <? foreach ($files as $file) { ?>
                <tr>
                    <td><?= $file->id ?></td>
                    <td><a href="<?= $file->url ?>"><?= basename($file->url) ?></a></td>
                    <td><?= $file->size ?></td>
                    <td><?= $file->type ?></td>
                </tr>
            <? } ?>
        </table>
    </div>
<? } ?>

<div class="container">
    <? if (!empty($error)) { ?><p class="notification"><?= $error; ?></p><? } ?>
    <form enctype="multipart/form-data" action="files.php" method="POST">
        <input class="user__file" name="user__file" type="file"/>
        <button class="file_upload" type="submit" name="file_upload">Загрузить</button>
    </form>
</div>
<div class="container">
    <a href="/log_out.php" class="link__log_out">Выйти</a>
</div>
</body>
</html>

