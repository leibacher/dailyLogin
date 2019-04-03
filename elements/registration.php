<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['register-submit'])){
    
    $error = "";
    if(!isset($_POST['username'])){
       $error = "Kein Benutzername gesetzt. ";
    }
    else{
/* Check the lenght from the username */
        $letters = strlen($_POST['username']);
        if($letters > 45 OR $letters < 3){
            $error = $error."Username zu lang oder zu kurz. Maximal 45 Zeichen, minimal 3 Zeichen.";
        }
    }
   
    if(!isset($_POST['password'])){
       $error = $error."Kein Passwort gesetzt. ";
    }
    else{
        $letters = strlen($_POST['password']);
/* Check the lenght from the password */
        if($letters > 45 OR $letters < 6){
            $error = $error."Passwort zu lang oder zu kurz. Maximal 45 Zeichen, minimal 6 Zeichen.";
        }
/* Check if a letter a-z is included in the password*/
        if(preg_match('/[a-z]/', $_POST['password']))
        {}
        else{
            $error = $error."Das Passwort benötigt einen Kleinbuchstaben. ";
        }
/* Check if a letter A-Z is included in the password */
        if(preg_match('/[A-Z]/', $_POST['password']))
        {}
        else{
            $error = $error."Das Passwort benötigt einen Grossbuchstaben. ";
        }
/* Check if a number is included in the password */
        if(preg_match('/[0-9]/', $_POST['password']))
        {}
        else{
            $error = $error."Das Passwort benötigt eine Zahl. ";
        }   
    }
    if(!isset($_POST['password_confirmed'])){
       $error = $error."Keine Passwort Bestätigung gesetzt. ";
    }
    $name = $_POST['username'];
    $password = sha1($_POST['password']);
    $password_confirmed = sha1($_POST['password_confirmed']);
    $lastonline = new DateTime();
     
/* Check if the username is already taken */
    $sql = "SELECT name FROM user where name='$name';";
    $result = Database::getData($sql);
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            if($row["name"]==$name){
                $error = $error."Benutzername bereits vergeben";
            }
        }
    }
    
/* checks if both password entries are identical */
    if($password!=$password_confirmed){
       $error = $error."Das Passwort ist nicht gleich. ";
    }
    
/* if no errors add a new user */    
    if($error==""){
        $serie = 0;
        $user = new User();
        $user->setUsername($name)
             ->setPassword($password)
             ->setLastonline($lastonline)
             ->setSerie($serie);
        $user->save(); 
                           }
    
}?>
    <h1>Registrieren</h1>
    <?php
if(isset($_POST['username']) AND $error!=""){ ?>

        <p style="color:red;">
            <?php echo $error; ?>
        </p>

        <form id='register' action='?p=register' method='post' accept-charset='UTF-8' enctype="multipart/form-data" autocomplete="on">
            <div class="register-form">
                <label for='username'>Benutzername*:</label>
                <input type='text' name='username' id='username' value="<?php echo $_POST['username']; ?>" maxlength="45" autocomplete="username" required/>
                <p id="username-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label for='password'>Passwort*:</label>
                <input type='password' name='password' id='password' maxlength="45" autocomplete="new-password" required/>
                <p id="password-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label for='password'>Passwort bestätigen*:</label>
                <input type='password' name='password_confirmed' id='password_confirmed' maxlength="45" required/>
                <p id="password-confirm-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label></label>
                <button class="button-green" type='submit' name='register-submit'>Registrieren</button>
            </div>
        </form>
        <?php } 
elseif(isset($_POST['register-submit']) AND $error==""){
    echo "Erfolgreich registriert!";
}
else{ ?>
        <form id='register' action='?p=register' method='post' accept-charset='UTF-8' enctype="multipart/form-data" autocomplete="on">
            <div class="register-form">
                <label for='username'>Benutzername*:</label>
                <input type='text' value="" name='username' id='username' maxlength="45" autocomplete="username" required/>
                <p id="username-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label for='password'>Passwort*:</label>
                <input type='password' value="" name='password' id='password' class="firstpw" maxlength="45" autocomplete="new-password" required/>
                <p id="password-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label for='password'>Passwort bestätigen*:</label>
                <input type='password' value="" name='password_confirmed' id='password_confirmed' maxlength="45" required/>
                <p id="password-confirm-alert" class="register-alert"></p>
            </div>
            <div class="register-form">
                <label></label>
                <button class="button-green" id="button-green" type='submit' name='register-submit'>Registrieren</button>
            </div>
        </form>
        <?php } ?>

<script>
/* if the cursor lose focus to the input field, it check if the username is already taken */
jQuery("#register").on("blur", "input[name=username]", function() { 
    var username = jQuery(this).val();
/* with a script it compare the username, with the usernames in the database */
    var url = "scripts/valid_username.php";
    jQuery.ajax({
        type: "POST",
        url: url,
        dataType: "JSON",
        data: {
            username: username
        },
        success: function(data) {
            setTimeout(function() { 
                $("#username-alert").text("");
                if (data != "") {

                    $("#username-alert").append(data);
                    $("#username-alert").css("color", "red");
                    checkusername = false;
                } else {
                    $("#username-alert").append("Benutzername noch verfügbar");
                    $("#username-alert").css("color", "green");
                    checkusername = true;
                }
            }, 2000); 
        },        
        error: function(xhr, ajaxOptions, thrownError) {
            $('.error').toggleClass("hidden");
            $('.error').text(xhr.responseText);
            alert(xhr.responseText);
            alert(thrownError);
        }
    });
     
});
/* checks if both password entries are identical while typing the first password*/   
jQuery("#register").on("keyup", "input[name=password]", function() {
    var pw = jQuery(this).val();
    var pw_confirmed = jQuery("input[name=password_confirmed]").val(); 
    if(pw==pw_confirmed){
        $("#password-confirm-alert").text("");
        $("#password-confirm-alert").append("Passwörter sind gleich.");
        $("#password-confirm-alert").css("color", "green");   
    }else{
        $("#password-confirm-alert").text("");
        $("#password-confirm-alert").append("Passwörter sind nicht gleich.");
        $("#password-confirm-alert").css("color", "red");   
    }
});
/* checks if both password entries are identical while typing the second password*/  
jQuery("#register").on("keyup", "input[name=password_confirmed]", function() {
    var pw = jQuery(this).val();
    var pw_confirmed = jQuery("input[name=password]").val(); 
    if(pw==pw_confirmed){
        $("#password-confirm-alert").text("");
        $("#password-confirm-alert").append("Passwörter sind gleich.");
        $("#password-confirm-alert").css("color", "green");   
    }else{
        $("#password-confirm-alert").text("");
        $("#password-confirm-alert").append("Passwörter sind nicht gleich.");
        $("#password-confirm-alert").css("color", "red");   
    }
});   
</script>
