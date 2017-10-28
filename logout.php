<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>logout</title>
</head>
<body>
<div class="form-group">

   <ul>
       <?php foreach($user as $value) { ?>
       <li>Your email: <?php echo $value['email'] ?></li>
       <li>Your name: <?php echo $value['user_name'] ?></li>
       <?php } ?>
       <li><a href="exit.php">sign out</a></li>

       <!--  full path to the file   <li><a href="--><?php //echo ROOT.'\exit.php'; ?><!--">logout</a></li>-->

   </ul>
    
    
    

   


</body>
</html>