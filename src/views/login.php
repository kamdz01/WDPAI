<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../../public/css/main.css"/>
    <title>Login</title>
</head>
<body>
    <div id = "main">
        <div id="return_btn"><a href="/"><h2><</h2></a></div>
        <div id = "column">
            <div id = "header"><h1>Notility</h1></div>
            <div class = "logo_medium"><img src = "../../public/data/logo2.png"/></div>
            <div id="sub_header"><h6>Welcome back!</h6></div>
            <div class="messages">
                <?php
                    if (isset($messages)) {
                    foreach ($messages as $message) {
                        echo $message;
                    }
                    }
                ?>
            </div>
            <form action = "login" method="POST">
                <div><input class = "input_big" id='login' name = "login" placeholder = "Login"/></div>
                <div><input class = "input_big" id='password' type = "password" name = "password" placeholder = "Password"/></div>
                <div class = "text_normal right_aligned"><a href = "forgotpass.php">Forgot password?</a></div>
                <div><button class = "button_big button_grey" type="submit">Login</button></div>
            </form>
            <div class = "center_aligned"><p class = "text_normal">Don't have an account? <a class = "hyperlink_regular" href = "register">Register Now</a></p></div>
        </div>
    </div>
</body>
</html>