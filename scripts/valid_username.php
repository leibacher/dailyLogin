<?php
require_once '../require.php';
if(isset($_POST['username'])){
    $data = "";
/* use a function to compare the names */
    foreach(UsserSelect::selectUsers() as $user){
        if($user->getUsername() == $_POST['username'] ){
            $data = "Benutzername bereits vergeben";
        }
    }
    echo json_encode($data);
}