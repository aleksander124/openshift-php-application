<?php
       session_start();

       if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true)){
		header('Location: home.php');
		exit();
	    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Strona logowania</title>
    <link href="main.css" rel="stylesheet" />
</head>
<body>
    <div class="header">
        <div class="login-box">
            <div class="avatar">
                <!-- <img src="https://s.gravatar.com/avatar/6826aaff0ea707061b91d4277366de9a?s=104" alt=""> -->
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M288 128H160c-35.3 0-64 28.7-64 64v16c0 61.8 50.2 112 112 112h32c61.8 0 112-50.2 112-112v-16c0-35.3-28.7-64-64-64zm32 80c0 44.1-35.9 80-80 80h-32c-44.1 0-80-35.9-80-80v-16c0-17.6 14.3-32 32-32h128c17.7 0 32 14.4 32 32v16zm-128-32l-12 36-36 12 36 12 12 36 12-36 36-12-36-12-12-36zm112 224H144c-26.5 0-48 21.5-48 48v56c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-56c0-8.8 7.2-16 16-16h160c8.8 0 16 7.2 16 16v56c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-56c0-26.5-21.5-48-48-48zm-32 48c-8.8 0-16 7.2-16 16s7.2 16 16 16 16-7.2 16-16-7.2-16-16-16zm-96 0c-8.8 0-16 7.2-16 16v40c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-40c0-8.8-7.2-16-16-16zm183.2-119.7c20.3-20.1 35.9-44.8 45.7-72.3H416c8.8 0 16-7.2 16-16v-96c0-8.8-7.2-16-16-16h-11.2C378.5 53.5 307.6 0 224 0S69.5 53.5 43.2 128H32c-8.8 0-16 7.2-16 16v96c0 8.8 7.2 16 16 16h11.2c9.7 27.5 25.4 52.2 45.7 72.3C37.1 347 0 396.2 0 454.4V504c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-49.6c0-51.6 38.5-94 88.3-101C150.2 372.7 185.8 384 224 384s73.8-11.3 103.7-30.6c49.8 6.9 88.3 49.3 88.3 101V504c0 4.4 3.6 8 8 8h16c4.4 0 8-3.6 8-8v-49.6c0-58.2-37.1-107.4-88.8-126.1zM224 352c-88.2 0-160-71.8-160-160S135.8 32 224 32s160 71.8 160 160-71.8 160-160 160z"></path></svg>
            </div>

            <h2>Witaj!</h2>
            <p>Zaloguj się na swoim koncie.</p>

            <form method="post" action="zaloguj.php" class="form">
                <div class="form--input-box">
                    <label for="username">Login</label>
                    <input type="text" name="login" id="login">
                </div>
                <div class="form--input-box">
                    <label for="password">Hasło</label>
                    <input type="password" name="haslo" id="password">
                </div>
                <div class="form--options">
                    <div>
                        <a href="/">Forgot password?</a>
                    </div>
                    <div>
                        Do not have account? <a href="rejestracja.php">SIGN UP</a>
                    </div>
                </div>

                <input type="submit" value="Zaloguj" class="button button--block" name="loguj">
            </form>
        </div>
        <canvas id="bg-anim"></canvas>
    </div>

    <script src="./assets/js/index.js" type="module"></script>
</body>
</html>