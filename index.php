<?php require "libs/db.php";?>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Ikantam upload files App</title>
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
<?
$data = $_POST;
$error = '';
if (isset($data['auth_submit'])) {
    $user = R::findOne('users', 'email = ?', array($data['user_login']));
    if ($user) {
//        if((password_verify($user->pass,$data['user_pass']))) {
        if (($user->pass == $data['user_pass'])) {
            $_SESSION['cur_user'] = $user;
        } else {
            $error = "Пароль введен не верно";
        }
    } else {
        $error = "Пользователя с таким именем не существует";
    }
}

if (isset($_SESSION['cur_user'])) {?>
    <div class="container">
        <h3 class="title__cur__auth">Привет, <?=$_SESSION['cur_user']->email?></h3>
        <a href="/files.php" class="link__files">My files</a>
        <a href="/log_out.php" class="link__log_out">Выйти</a>
    </div>
<?} else {?>
    <div class="container" >
        <?if(!empty($error)){?><p class="notification" ><?=$error;?></p><?}?>
        <form action="/" method="post" class="form__auth">
            <p class="title__form_auth">Авторизация</p>
            <label for="">Login(andrbee@gmail.com)</label>
            <input type="email" class="auth__user_login" name="user_login"
                   pattern="^[a-z0-9._%+-]+@[a-z0-9.-]+\.[a-z]{2,4}$" value="<?= @$data['user_login'] ?>" required>
            <label for="">Password(1234)</label>
            <input type="password" class="auth__user_pass" name="user_pass" required>
            <button class="auth__submit" name="auth_submit">Login</button>
        </form>
    </div>
<? } ?>
</body>
</html>
