function getValidationResult(token, recaptcha_challenge, recaptcha_response)
{
    var http = new XMLHttpRequest();
    var url = 'https://larapress.dev/admin/api/captcha/validate';
    var params = ''
        + '_token=' + encodeURIComponent(token) + '&'
        + 'recaptcha_challenge_field=' + encodeURIComponent(recaptcha_challenge) + '&'
        + 'recaptcha_response_field=' + encodeURIComponent(recaptcha_response);

    http.open('POST', url, true);
    http.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');

    http.onreadystatechange = function()
    {
        if ( http.readyState == 4 && http.status == 200 )
        {
            processResult(http.response);
        }
    };

    http.send(params);
}

function processResult(response)
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
}

document.getElementById('captcha-submit').onclick = function()
{
    var token = document.getElementsByName('_token')[0].value;
    var recaptcha_challenge = Recaptcha.get_challenge();
    var recaptcha_response = Recaptcha.get_response();
    getValidationResult(token, recaptcha_challenge, recaptcha_response);
};
