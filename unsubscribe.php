<!DOCTYPE html>
<html lang="en-US">
<head>
    <title>XKCD Challenge</title>
    <link rel="icon" href="https://avatars.githubusercontent.com/u/65281650?s=200&v=4" type="image/icon type">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./users/style/main.css">
</head>

<body>


    <div class="form-style">

        <div id="tick-icon-div">
            <img src="https://img.icons8.com/color/96/000000/approval--v3.gif" />
            <div>
                <span>Congratulations! your Email has been successfully unsubscribed.</span>
            </div>
            <a href="./">Click to subscribe</a>
        </div>


        <div id="form-style-div">
            <h1>Verify to unsubscribe XKCD comics every five minutes!</h1>
            <form>

                <div id="step-1">
                    <div class="section"><span>1</span>Enter your Email Address</div>
                    <div class="inner-wrap">
                        <label>Email Address <input type="email" name="user_mail" id="user_mail" placeholder="Eg- abc@xkcd.com" /></label>
                        <label id="email-warn"></label>
                    </div>
                </div>

                <div id="step-2">
                    <div class="section"><span>2</span>Enter OTP</div>
                    <div class="inner-wrap">
                        <label>Enter OTP sent to your Email <input type="number" name="otp" id="otp" /></label>
                        <label id="otp-warn"></label>
                    </div>
                </div>

                <div class="button-section">
                    <input type="button" value="Next" onclick="send_otp();" flag="step-1" id="s-otp-button" />
                </div>
                <?php  
                    $url.= $_SERVER['REQUEST_URI'];  
                    $path = end(explode("/", $url)); 
                ?>   
                <input type="hidden" name="status" id ="status" value="<?php echo $path; ?>">
            </form>            
        </div>
    </div>
    <script type="text/javascript" src="http://code.jquery.com/jquery-1.7.1.min.js"></script>
    <script src="./users/script/unsubscribe.js"></script>
    <input type="hidden" name="user_email" id="user_email" value="">
        <input type="hidden" name="user_otp" id="user_otp" value="">

</body>

</html>
