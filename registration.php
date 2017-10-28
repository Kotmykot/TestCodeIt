<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>registration</title>
</head>
<body>
<style>
    form{
        width: 350px;
    }
    .form-group{
        width: 100%;
        height: 50px;
    }
    label{
        float: left;
    }
    input{
        float: right;
    }
    select{
        float: right;
    }
    .error{
        color: red;
        float: right;
    }
</style>
<form action="" method="post">
    <div class="form-group">
        <label for="email">email:</label>
        <input type="email" id="email" name="email" value="<?php if(!empty($_SESSION['email'])){ echo $_SESSION['email']; }?>" placeholder="your email">
        <?php if (!empty($_SESSION['email_error'])){?>
            <span class="error"><?php echo $_SESSION['email_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="login">login:</label>
        <input type="text" id="login" name="login" value="<?php if(!empty($_SESSION['login'])){ echo $_SESSION['login']; }?>" placeholder="your login" >
        <?php if (!empty($_SESSION['login_error'])){?>
            <span class="error"><?php echo $_SESSION['login_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="name">real name:</label>
        <input type="text" id="name" name="name" value="<?php if(!empty($_SESSION['name'])){ echo $_SESSION['name']; }?>" placeholder="your real name" >
        <?php if (!empty($_SESSION['name_error'])){?>
            <span class="error"><?php echo $_SESSION['name_error']; ?></span>
        <?php  } ?>
    </div>
    <div class="form-group">
        <label for="password">password:</label>
        <input type="password" id="password" name="pass" value="" placeholder="your password" >
        <?php if (!empty($_SESSION['pass_error'])){?>
            <span class="error"><?php echo $_SESSION['pass_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="password">repeat password:</label>
        <input type="password" id="password" name="repeated_pass" value=""  placeholder="repeated password">
        <?php if (!empty($_SESSION['repeated_pass_error'])){?>
            <span class="error"><?php echo $_SESSION['repeated_pass_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="birth">birth date:</label>
        <input type="date" id="birth" name="birth" value="<?php if(!empty($_SESSION['birth'])){ echo $_SESSION['birth']; }?>" >
        <?php if (!empty($_SESSION['birth_error'])){?>
            <span class="error"><?php echo $_SESSION['birth_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="country">country:</label>
        <select name="country" id="country" >
            <?php if(!empty($_SESSION['country'])){ echo   "<option value=\"".$_SESSION['country']."\">".$_SESSION['country']."</option>"; }?>
            <option value="" disabled >Select a country</option>
            <?php foreach(User::getCountries() as $value){ ?>
            <option value="<?php echo $value['country']; ?>"><?php echo $value['country']; ?></option>
            <?php } ?>
        </select>
        <?php if (!empty($_SESSION['country_error'])){?>
            <span class="error"><?php echo $_SESSION['country_error']; ?></span>
        <?php } ?>
    </div>
    <div class="form-group">
        <label for="check">agree with terms and conditions: </label>
        <input type="checkbox" id="check" name="check"  value="true" <?php if(!empty($_SESSION['checkbox'])){ echo"checked"; }?> >
        <?php if (!empty($_SESSION['checkbox_error'])){?>
            <span class="error"><?php echo $_SESSION['checkbox_error']; ?></span>
        <?php } ?>
    </div>
    <a href="Auth.php">sing in</a>

    <!--  full path to the file    <a href="--><?php //echo ROOT.'\Auth.php'; ?><!--">sing in</a>-->

    <div class="form-group">
        <input type="submit" value="send">
    </div>

</form>
</body>
</html>