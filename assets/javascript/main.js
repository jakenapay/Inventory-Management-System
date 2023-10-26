

// Show or hide password 
function showHidePassword() {
    var x = document.getElementById("password");
    var y = document.getElementById("label-toggle");
    if (x.type === "password") {
        x.type = "text";
        y.innerHTML = "Hide Password";
    } else {
        x.type = "password";
        y.innerHTML = "Show Password";
    }
}