<?php
session_start();
if (isset($_POST['email'])){
    // Udana walidacja? Załóżmy że tak
    $wszystko_ok=true;

    //sprawdzanie nazyw użytkownika
    $nick = $_POST['nick'];

    //sprawdzenie długości nicku
    if((strlen($nick)<3) || (strlen($nick)>20)){
        $wszystko_ok = false;
        $_SESSION['e_nick'] = "Nazwa użytkownika musi posiadać od 3 do 20 znaków!";
    }

    if(ctype_alnum($nick)==false){
        $wszystko_ok=false;
        $_SESSION['e_nick']="Nazwa użytkownika może składać się tylko z liter i cyfr (bez polskich znaków)";
    }

    // Sprawdz poprawność adresu email
    $email = $_POST['email'];
    $emailB = filter_var($email, FILTER_SANITIZE_EMAIL);

    if((filter_var($emailB, FILTER_VALIDATE_EMAIL)==false) || ($emailB!=$email)){
        $wszystko_ok=false;
        $_SESSION['e_email'] = "Podaj poprawny adres e-mail";
    }

    // Sprawdz poprawność hasła
    $haslo1 = $_POST['haslo1'];
    $haslo2 = $_POST['haslo2'];

    if((strlen($haslo1)<8)|| (strlen($haslo1)>20)){
        $wszystko_ok=false;
        $_SESSION['e_haslo'] = "Hasło musi posiadać od 8 do 20 znaków!";
    }

    if($haslo1!=$haslo2){
        $wszystko_ok=false;
        $_SESSION['e_haslo'] = "Podane hasła nie są takie same";
    }

    $haslo_hash = password_hash($haslo1, PASSWORD_DEFAULT);


    // Czy zaakceptowano regulamin
    if(!isset($_POST['regulamin'])){
        $wszystko_ok=false;
        $_SESSION['e_regulamin'] = "Potwierdz akceptację regulaminu!";
    }

    require_once "connect.php";
    mysqli_report(MYSQLI_REPORT_STRICT);

    try{
        $polaczenie = new mysqli($host, $db_user, $db_password, $db_name);
        if ($polaczenie->connect_errno!=0){
            throw new Exception(mysqli_connect_errno());
        }else{
            // Czy email już istnieje?
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE email='$email'");
            if(!$rezultat)throw new Exception($polaczenie->error);
            $ile_takich_maili = $rezultat->num_rows;

            if($ile_takich_maili>0){
                $wszystko_ok=false;
                $_SESSION['e_email'] = "Istnieje już konto przypisane do tego adresu email!";
            }

            // Czy nazwa użytkownika jest już zarezerwowany?
            $rezultat = $polaczenie->query("SELECT id FROM uzytkownicy WHERE  user='$nick'");
            if(!$rezultat)throw new Exception($polaczenie->error);
            $ile_takich_nickow = $rezultat->num_rows;

            if($ile_takich_nickow>0){
                $wszystko_ok=false;
                $_SESSION['e_nick'] = "Istnieje już konto o takiej nazwie!";
            }

            if($wszystko_ok==true){
                //wszystkie testy zaliczone
                if($polaczenie->query("INSERT INTO uzytkownicy VALUES (NULL, '$nick', '$haslo_hash', '$email')")){
                    $_SESSION['udanarejestracja']=true;
                    header('Location: witamy.php');
                }else{
                    throw new Exception($polaczenie->error);
                }
            }

            $polaczenie->close();
        }

    }
    catch(Exception $e){
        echo 'Błąd server! przepraszamy za niedogodności i prosimy o rejestrację w innym terminie';
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <link rel="stylesheet" href="style.css">
    <title>Rejestracja</title>

    <style>
        .error{
            color: red;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>
<h1>Formularz rejestracji</h1>

<div>
    <form method="post">
        <label for="username">Nazwa użytkownika: </label>
        <input type="text" name="nick"><br><br>

        <?php
        if(isset($_SESSION['e_nick'])){
            echo '<div class="error">'.$_SESSION['e_nick'].'</div>';
            unset($_SESSION['e_nick']);
        }
        ?>

        <label for="email">Email: </label>
        <input type="text" name="email"><br><br>

        <?php
        if(isset($_SESSION['e_email'])){
            echo '<div class="error">'.$_SESSION['e_email'].'</div>';
            unset($_SESSION['e_email']);
        }
        ?>

        <label for="password">Twoje hasło: </label>
        <input type="password" name="haslo1"><br><br>

        <?php
        if(isset($_SESSION['e_haslo'])){
            echo '<div class="error">'.$_SESSION['e_haslo'].'</div>';
            unset($_SESSION['e_haslo']);
        }
        ?>

        <label for="password">Powtórz hasło: </label>
        <input type="password" name="haslo2"></br><br>

        <label>
            <input type="checkbox" name="regulamin" />Akceptuje regulamin
        </label></br><br>

        <?php
        if(isset($_SESSION['e_regulamin'])){
            echo '<div class="error">'.$_SESSION['e_regulamin'].'</div>';
            unset($_SESSION['e_regulamin']);
        }
        ?>

        <input type="submit" value="Zarejestruj się" name="Zarejestruj">

    </form>
</div>


</body>
</html>