<?php if ( $currpage == 'register' && !isset($_SESSION['ActiveUser'])  ){?>
    <?php 
    if ( empty($_POST) === false ) {
      echo ' form submitted';
    }?>
    <h1>Create an Account</h1>
    <p><label for="username">User name</label>
    <input class="_formdata" type="text" name="username" value=""></p>

    <p><label for="password">Password </label>
    <input class="_formdata" type="password" name="userpass" value=""></p>

    <p><label for="firstname">First Name:</label></p>
    <input class="_formdata" type="text" for="firstname" name="firstname" value="">

    <p><label for="firstname">Last Name:</label></p>
    <input class="_formdata" type="text" for="lastname" name="lastname" value="">

    <p><input type="submit" value="register" class="button"></p>
    <br/>
    <a href="/login">Log In</a>

  <?php } elseif ( $currpage == 'login' && !isset($_SESSION['ActiveUser']) ) {?>

    
    <h1>Log Into Account</h1>

    <p><label for="username">User name</label>
    <input class="_formdata" type="text" id="username" name="username"></p>

    <p><label for="password">Password </label>
    <input class="_formdata" type="password" id="userpass" name="userpass"></p>

    <input type="submit" value="login" class="button">
    <br/>
    <a href="/logout">Log Out</a>
    <a href="/register"> | Register</a>
    <a href="/profile"> | My Profile</a>



  <?php }elseif ( isset($_SESSION['ActiveUser']) ){?>

      <h1><?php echo $_SESSION['ActiveUser'] ?></h1>
      <a href="logout"> Logout</a>
      <a href="profile"> | My Profile</a>  

  <?php }elseif ( !isset($_SESSION['ActiveUser']) ){?>

      <h1>Get Started</h1>
      <a href="register"> Get Registerd</a>
      <a href="/login"> | Login</a>  

  <?php }?>






<?php if ( $currpage == 'profile' && isset($_SESSION['ActiveUser']) ) {?>


    <h1>My Account</h1>
    <p>User name: <?php echo $_SESSION['ActiveUser'];?></p>

    <?php
        if ( file_exists(USERSDIR.$_SESSION['ActiveUser'].CONFIG_EXT) ) {
          
          $filecontent = file_get_contents(USERSDIR.$_SESSION['ActiveUser'].CONFIG_EXT);
          $data      = json_decode($filecontent, true);
          $user      = $data['username'];
          $userbio    = $data['bio'];

        } else { 
            echo 'user data does not exist.';
        };
        ?>

    <p>user bio: <?php echo $userbio;?></p>
    <br/>
    <a href="logout">Log Out</a>
    <a href="updateprofile"> | Update Profile</a>

<?php } elseif ( $currpage == 'updateprofile' && isset($_SESSION['ActiveUser']) ) {?>
    <?php 
    $data = getUserData($_SESSION['ActiveUser']); 
    ?>

<h1>Update Account Info</h1>
        <p><label for="username">User name</label>
        <input class="_formdata" type="text" name="username" value="<?php echo $data['username']; ?>"></p>

        <p><label for="password">Password </label>
        <input class="_formdata" type="password" name="userpass" value="<?php ?>"></p>

        <p><label for="bio" id="bio">Bio:</label></p>
<div contenteditable="true" style="width:860px; margin: 30px auto;">
  <textarea id="editor" class="_formdata" name="content" placeholder="Type your text here...">
<?php echo $data['bio'];?>
  </textarea>
        <!-- <textarea id="editor" class="_formdata" name="bio" id="userbio" cols="30" rows="10"></textarea>  -->

</div>

       <input name="submitSave" type="submit" value="update" class="button">
       <input name="submitDelete" type="submit" value="delete" class="button">
        <br/>
        <a href="/logout">Log Out</a>
        <a href="/profile"> | My Profile</a> 

<?php }?>