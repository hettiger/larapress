var larapress = larapress || {};

larapress.getValidationResult = function(captcha_validation_url, token, recaptcha_challenge, recaptcha_response)
{
    var http = new XMLHttpRequest();
    var params = ''
        + '_token=' + encodeURIComponent(token) + '&'
        + 'recaptcha_challenge_field=' + encodeURIComponent(recaptcha_challenge) + '&'
        + 'recaptcha_response_field=' + encodeURIComponent(recaptcha_response);

    http.open('POST', captcha_validation_url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function()
    {
        if ( http.readyState == 4 && http.status == 200 )
        {
            larapress.processResult(http.response);
        }
    };

    http.send(params);
};

larapress.processResult = function(response)
{
    response = JSON.parse(response);

    if ( response.result === 'success' )
    {
        Recaptcha.destroy();
        document.getElementById('captcha-submit').style.display = 'none';
        document.getElementById('captcha-failure').style.display = 'none';
        document.getElementById('captcha-success').style.display = 'block';
    }
    else
    {
        Recaptcha.reload();
        document.getElementById('captcha-failure').style.display = 'block';
    }
};

document.getElementById('captcha-submit').onclick = function()
{
    document.getElementById('captcha-failure').style.display = 'none';

    var token = document.getElementsByName('_token')[0].value;
    var recaptcha_challenge = Recaptcha.get_challenge();
    var recaptcha_response = Recaptcha.get_response();
    larapress.getValidationResult(captcha_validation_url, token, recaptcha_challenge, recaptcha_response);
};
