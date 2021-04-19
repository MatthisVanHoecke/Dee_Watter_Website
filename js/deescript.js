function toggle() {
    if (window.innerWidth > 1020) {
        document.getElementById('navigationbar').style.display = 'none';  
        document.getElementById('menu').style.display = 'block'; 
    }
    else {
        document.getElementById('navigationbar').style.display = 'block';
        document.getElementById('menu').style.display = 'none'; 
    }    
}
toggle();
window.onresize = function() {
    toggle();
}

var n = ".*[0-9].*";
var l = ".*[A-Z].*";

function submitModalLoginForm() {
    var ok = true;
    var usr = document.getElementById('user').value;

    if(document.getElementById('user').value == "") {
        document.getElementById('userError').innerHTML = "*Please fill in your username<br>";
        ok = false;
    }
    else {
        document.getElementById('userError').innerHTML = "";
        
    }

    if(document.getElementById('password').value == "") {
        document.getElementById('passwordError').innerHTML = "*Please fill in your password";
        ok = false;
    }
    else {
        if(document.getElementById('password').value.length < 8) {
            document.getElementById('passwordError').innerHTML = "*Your password has to be at least 8 characters long";
            ok = false;
        }
        else {
            if(!document.getElementById('password').value.match(n) && !document.getElementById('pass').value.match(l)) {
                document.getElementById('passwordError').innerHTML = "*Password needs to contain letters and numbers";
                ok = false;
            }
            else {
                document.getElementById('passwordError').innerHTML = "";  
            }
        }
    }

    if(ok == true) {
        document.getElementById('signin_button').style.display = "none";
        document.getElementById('btnloadSignin').style.display = "block";
        document.getElementById('loadSignin').style.display = "block";
        $.get("name.php?username=" + document.getElementById('user').value + "&&pass=" + document.getElementById('password').value, function(data) {
            var arr = data.split(",");
            var ok2 = true;
            
            if(arr[0] != document.getElementById('user').value && arr[1] != document.getElementById('user').value) {
                document.getElementById('userError').innerHTML = "*This username or email doesn't exist";
                document.getElementById('signin_button').style.display = "block";
                document.getElementById('btnloadSignin').style.display = "none";
                document.getElementById('loadSignin').style.display = "none";
                ok2 = false;
            }
            if(arr[2] == "no") {
                document.getElementById('passwordError').innerHTML = "*Wrong password";
                document.getElementById('signin_button').style.display = "block";
                document.getElementById('btnloadSignin').style.display = "none";
                document.getElementById('loadSignin').style.display = "none";
                ok2 = false;
            }
            
            if(ok2 == true) {
                document.modalLogForm.submit();
            }
            
        });   
    }
}

function submitModalSignupForm() {

    var ok = true;

    if(document.getElementById('username').value == "") {
        document.getElementById('usernameError').innerHTML = "*Please fill in your username<br>";
        ok = false;
    }
    else {
        document.getElementById('usernameError').innerHTML = "";
    }

    if(document.getElementById('email').value == "") {
        document.getElementById('emailError').innerHTML = "*Please fill in your email<br>";
        ok = false;
    }
    else {
        document.getElementById('emailError').innerHTML = "";
    }

    if(document.getElementById('pass').value == "") {
        document.getElementById('passError').innerHTML = "*Please fill in your password";
        ok = false;
    }
    else {
        if(document.getElementById('pass').value.length < 8) {
            document.getElementById('passError').innerHTML = "*Your password has to be at least 8 characters long";
            ok = false;
        }
        else {
            if(!document.getElementById('pass').value.match(n) && !document.getElementById('pass').value.match(l)) {
                document.getElementById('passError').innerHTML = "*Password needs to contain letters and numbers";
                ok = false;
            }
            else {
                if(document.getElementById('passconfirm').value != document.getElementById('pass').value) {
                    document.getElementById('passError').innerHTML = "*Passwords don't match";
                    ok = false;
                }
                else {
                    document.getElementById('passError').innerHTML = "";
                }      
            }
        }
    }


    if(ok == true) {
        document.getElementById('signup_button').style.display = "none";
        document.getElementById('btnloadSignup').style.display = "block";
        document.getElementById('loadSignup').style.display = "block";
        $.get("name.php?username=" + document.getElementById('username').value + "&&email=" + document.getElementById('email').value + "&&pass=", function(data) {
            var arr = data.split(",");
            var ok2 = true;
            
            
            if(arr[0].toLowerCase() == document.getElementById('username').value.toLowerCase() || arr[1].toLowerCase() == document.getElementById('email').value.toLowerCase()) {
                
                if(arr[0].toLowerCase() == document.getElementById('username').value.toLowerCase()) {
                    document.getElementById('usernameError').innerHTML = "*This username already exists";
                    document.getElementById('signup_button').style.display = "block";
                    document.getElementById('btnloadSignup').style.display = "none";
                    document.getElementById('loadSignup').style.display = "none";
                    ok2 = false;
                }
                if(arr[1].toLowerCase() == document.getElementById('email').value.toLowerCase()) {
                    document.getElementById('emailError').innerHTML = "*This email already exists";
                    document.getElementById('signup_button').style.display = "block";
                    document.getElementById('btnloadSignup').style.display = "none";
                    document.getElementById('loadSignup').style.display = "none";
                    ok2 = false;
                } 
            }
            
            if(ok2 == true) {
                document.modalSignupForm.submit();
            }
        });
    }

}