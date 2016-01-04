{% if url == '/login' %}

<h1> begin login</h1>
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
<h1> end login</h1>
{% elseif url == '/register' %}
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


{% elseif url == '/logout' %}
{% elseif url == '/updateprofile' %}
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
{% elseif url == '/profile' %}

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
{% endif %}

<?php
function searchFileSystem ($path) {
				// echo $path;
				global $ignore;
				$data = [];

			if ( is_dir(CONTENTDIR.$path) ) {
					
				
				// Create recursive dir iterator which skips dot folders
				$dir = new RecursiveDirectoryIterator(CONTENTDIR.$path,
				    RecursiveDirectoryIterator::SKIP_DOTS);

				$glb = new GlobIterator(CONTENTDIR.$path.'/*', FilesystemIterator::KEY_AS_FILENAME );
				// Flatten the recursive iterator, folders come before their files
				// SELF_FIRST gets all sub folders
				// CATCH_GET_CHILD gets 1 level folders
				$it  = new RecursiveIteratorIterator($dir,
				    RecursiveIteratorIterator::SELF_FIRST
				    );
				// Maximum depth is 1 level deeper than the base folder
				// $it->setMaxDepth(5);
				$articles = [];
				$categories = [];


				foreach ($glb as $key => $value) {
				if ( is_dir($value)) {
					// Send all folder arrays to categories array object
					$categories[] = [$key => $value];
				}else{
					// Send all file arrays to articles array object
					if ( substr_count($value,".DS_Store") ) continue;
				    $articles[] = [$key => ['path'=>$value->getPathname()]];					
				}					

				}
				$data['collection'] = ['articles'=> $articles,'categories'=>$categories];
				// debug($data);
				// return json_encode($data,true);
				return $data;
			}else{
				return 'its a file';

			}
}// end searchfilesystem



// the code below was used to create page navigations for prev and next articles
    // for ($i=0; $i < count($data); $i++) { 
    //  $pages[] = $data[$i]['url'];
    //  // $pages[] = ['url'=>$data[$i]['url'],'title'=>$data[$i]['title']];
    // }
 
    // $urlkey = array_search($requesturl, $pages);
    // $prev_page = ($urlkey == 0) ? false: $pages[$urlkey - 1] ;
    // $next_page = ($urlkey <= $total_pages - 1) ? $pages[$urlkey + 1] : false;

    // debug($urlkey);
    // debug($next_page);
    // $pagenav = ['next'=>$next_page,'prev'=>$prev_page];
    // return $pagenav;