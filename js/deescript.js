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
        document.modalLogForm.submit();
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


        document.modalSignupForm.submit();
    }

}