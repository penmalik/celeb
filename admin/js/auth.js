$(document).ready(function(){

    // toggle password show or hide
    document.getElementById('show').addEventListener('click', function () {
    
        const passwordField = document.getElementById('password');
        const button = document.getElementById('show');
    
        if (passwordField.type === 'password') {
            passwordField.type = 'text';
            button.textContent = 'Hide Password'
        } else {
            passwordField.type = 'password';
            button.textContent = 'Show Password'
        }
    
    });
    
    
    // ajax registration logic
    $('#regForm').on('submit', function(e){
        $('#regBtn').attr('disabled', 'disabled');
        e.preventDefault();
        $.ajax({
            url: 'handlers/auth.php?action=regAdmin',
            type: 'POST',
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                var response = JSON.parse(response);
                // console.log(response);
    
                if(response.statusCode == 200){
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    $("#regForm")[0].reset();
    
                    // delay the redirect by 3 sec
                    setTimeout(function(){
                        window.location.href = "log";
                    }, 3000);
                } else if(response.statusCode == 500){
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                } else{
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
    
                $("#regBtn").removeAttr('disabled');
            },
            error: function(xhr, status, error) {
                console.log("Error: " + error);
                $("#errorToast").toast("show");
                $("#errorMsg").html('There was an error with the registration. Please try again.');
                $("#regBtn").removeAttr('disabled');
            }
        });
    });
    
    
    // ajax login logic
    $("#loginForm").on("submit", function(e){
        $("#loginBtn").attr("disabled", "disabled");
        e.preventDefault();
        $.ajax({
            url: "handlers/auth.php?action=loginAdmin",
            type: "POST",
            data: new FormData(this),
            contentType: false,
            cache: false,
            processData: false,
            success: function(response){
                var response = JSON.parse(response);
                // console.log(response);
    
                if(response.statusCode == 200){
                    $("#successToast").toast("show");
                    $("#successMsg").html(response.message);
                    $("#loginForm")[0].reset();
                    // delay the redirect by 3 seconds
                    setTimeout(function(){
                        window.location.href = "index";
                    }, 3000);
                } else if(response.statusCode == 500){
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                } else{
                    $("#errorToast").toast("show");
                    $("#errorMsg").html(response.message);
                }
                $("#loginBtn").removeAttr("disabled");
            },
            error: function(xhr, status, error){
                console.log("Error: " + error);
                $("#errorToast").toast("show");
                $("#errorMsg").html("Sorry an error occured, try again.");
                $("#loginBtn").removeAttr("disabled");
            }
        });
    });
    
});
