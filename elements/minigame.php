<?php
    $username = $user->getUsername();
    $coins = $user->getCoins();

    $commitment = 50;
/* check if there is a game*/
if(isset($_POST["black"]) OR isset($_POST["red"])){
    
    $newCoins = $coins - $commitment;
/* check if the user has enough coins*/
    if($newCoins < 0){
        echo "Zu wenig Coins. Komme morgen wieder für mehr Coins.";
    }else{

      
/* generate a random number 0 or 1 */
        $randomnumber = round(rand(0,1000)/1000);
        if(isset($_POST["black"])){
            if($randomnumber == 1){
                echo "Du hast gewonnen!";
                $newCoins = $coins + $commitment;    
                $user->setCoins($newCoins); 
                $user->save();
            }else{
                echo "Du hast verloren!";
                $newCoins = $coins - $commitment; 
                $user->setCoins($newCoins);
                $user->save();
            }
        }
        if(isset($_POST["red"])){
            if($randomnumber == 0){
                echo "Du hast gewonnen!";
                $newCoins = $coins + $commitment;
                $user->setCoins($newCoins);
                $user->save();
            }else{
                echo "Du hast verloren!";
                $newCoins = $coins - $commitment; 
                $user->setCoins($newCoins);
                $user->save();
            } 
        }
    echo "<br>";
    echo $newCoins;
    echo "<br>";
    
    }   
}else{
    echo "<br>";
    echo $coins;
    echo "<br>";
}
    
?>
<p>Für 50 Coins kannst du hier am Roulettetisch auf Schwarz oder Rot setzen. Solltest du richtig setzen kommst du 100 Coins zurück. Viel Glück</p>
<div class="minigame container">
    <form id='minigame_red' class="minigame" action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' accept-charset='UTF-8'>
        <input type="hidden" value="0" name="red">
        <input type="submit" value="rot" id="red" class="minigame-button" name="red_submit">
    </form>

    <form id='minigame_black' class="minigame" action='<?php echo $_SERVER['PHP_SELF']; ?>' method='post' accept-charset='UTF-8'>
        <input type="hidden" value="1" name="black">
        <input type="submit" value="black" id="black" class="minigame-button" name="black_submit">
    </form>
</div>