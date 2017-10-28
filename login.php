<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>login</title>
</head>
<body>
<style>
    form{
        width: 350px;
    }
    .form-group{
        width: 100%;
        height: 30px;
    }
    label{
        float: left;
    }
    input{
        float: right;
    }
    .error{
        color: red;
        float: right;
    }
    #button{
        margin-top:  20px;
      width: 100%;
    }
</style>

<form action="" method="post">
    <div class="form-group">
        <label for="log">login or email:</label>
        <input type="text" id="log" name="log" value="<?php if(!empty($_SESSION['log'])){ echo $_SESSION['log']; } ?>" placeholder="login or email">
    </div>
    <div class="form-group">
        <label for="pass">password:</label>
        <input type="password" id="pass" name="pass" placeholder="password" >
        <?php if (!empty($_SESSION['login_err'])){?>
            <span class="error"><?php echo $_SESSION['login_err']; ?></span>
        <?php } ?>
    </div>
    <a href="User.php">registration</a>

    <!--  full path to the file  <a href="--><?php //echo ROOT.'\User.php'; ?><!--">registration</a>-->

    <div class="form-group">
        <input type="submit" id="button" value="send">
    </div>
</form>

</body>
</html>