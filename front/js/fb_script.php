
<?php
	header('Content-Type: text/javascript');
	$appID = (!empty($_GET['appID'])) ? $_GET['appID'] : 'appID'; // default value used if none supplied in URL
?>

<script type="text/javascript">
window.fbAsyncInit = function() {
    FB.init({
        appId: '<?php echo $appID; ?>',
        status: true,
        cookie: true,
        xfbml: true,
        version: 'v2.4'
    });
};

function FBLogin(redirect_url) {
    redirect_url = redirect_url ? redirect_url : false;
    FB.login(function(fb_response) {
        if (fb_response.authResponse) {
            FB.api('/me', 'get', {
                fields: 'id,email,first_name,last_name, picture'
            }, function(profile_response) {
                var email = profile_response.email;
                var fb_user_id = profile_response.id;
                var fname = profile_response.first_name;
                var lname = profile_response.last_name;
                var fb_token = FB.getAuthResponse()['accessToken'];
                var dataObj = {
                    "fb_user_id": fb_user_id,
                    "email": email,
                    "fname": fname,
                    "lname": lname,
                    'fb_token': fb_token,
                    "_token": csrf_token
                };
                jQuery.ajax({
                    headers: {
                        'X-CSRF-Token': csrf_token
                    },
                    url: SITE_URL + '/fblogin',
                    type: 'POST',
                    data: dataObj,
                    dataType: 'json',
                    beforeSend: showProcessingOverlay(),
                    success: function(response) {
                        if (response.status == "SUCCESS_VERIFY") {
                            window.location.href = SITE_URL + '/verify_otp/' + response.enc_user_id;
                        } else if (response.status == "SUCCESS") {
                            if (redirect_url != false) {
                                window.location.href = SITE_URL + redirect_url;
                            } else {
                                window.location.href = SITE_URL;
                            }
                        } else {
                            $("#login_social_status").html("<div class='alert alert-danger'><strong>Error: </strong>" + response.msg + "<span> <a class='close' data-dismiss='alert'><i class='fa fa-times'></i></a></span></div>");
                            setTimeout(function() {
                                $("#login_social_status").empty();
                            }, 10000);
                        }
                        return false;
                    },
                    complete: hideProcessingOverlay()
                });
                return false;
            });
        }
    }, {
        scope: 'public_profile,email'
    });
}

function FBLogout() {
    FB.logout(function(response) {
        window.location.href = SITE_URL + '/logout';
    });
}(function(d, s, id) {
    var js, fjs = d.getElementsByTagName(s)[0];
    if (d.getElementById(id)) {
        return;
    }
    js = d.createElement(s);
    js.id = id;
    js.src = SITE_URL + "/front/js/sdk.js";
    fjs.parentNode.insertBefore(js, fjs);
}(document, 'script', 'facebook-jssdk'));
</script>