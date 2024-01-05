<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel = "stylesheet" href = "../../public/css/main.css"/>
    <title>Register</title>
</head>
<body>
    <div id = "main">
        <div id="return_btn"><a href="/"><h2><</h2></a></div>
        <div id = "column">
            <div id = "header"><h1>Notility</h1></div>
            <div class = "logo_medium"><img src = "../../public/data/logo2.png"/></div>
            <div id="sub_header"><h6>Hello! Register to get started</h6></div>
            <form action = "register" method="POST">
                <div><input class = "input_big" type = "text" name = "login" placeholder = "Login"/></div>
                <div><input class = "input_big" type = "text" name = "email" placeholder = "Email"/></div>
                <div><input class = "input_big" type = "password" name = "password" placeholder = "Password"/></div>
                <div><input class = "input_big" type = "password" name = "password" placeholder = "Confirm password"/></div>
                <div><button class = "button_big button_grey" type="submit">Register</button></div>
            </form>
            <div class = "center_aligned"><p class = "text_normal">Already have an account? <a class = "hyperlink_regular" href = "login">Login Now</a></p></div>
        </div>
    </div>
</body>
</html>