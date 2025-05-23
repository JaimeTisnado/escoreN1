<?php
session_start();

class Post_Block {


	function startPost() {
        echo "<input type='hidden' name='postID' ";
        echo "value='".md5(uniqid(rand(), true))."'>";
    }
 
   function postBlock($postID) {
        
        if(isset($_SESSION['postID'])) {
			#echo "<br>entro al isset 1";
            if ($postID == $_SESSION['postID']) {
				#echo "<br>if comparacion true";
                return false;
           } else {
			    #echo "<br>if comparacion false";
                $_SESSION['postID'] = $postID;
                return true;
            }
        } else {
			#echo "<br>salio del isset 1";
            $_SESSION['postID'] = $postID;
            return true;
        }
		
    }

} // end Class
?>