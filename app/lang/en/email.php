<?php

$cms_name = Config::get('larapress.names.cms');

return array(

    'Password Reset!' => 'Password Reset!',

    'Hello!' => 'Hello!',

    'Have a nice day!' => 'Have a nice day!',

    // Reset Password

    'Someone, hopefully you, has requested a password reset for your ' . $cms_name . ' cms account.'
        => 'Someone, hopefully you, has requested a password reset for your ' . $cms_name . ' cms account.',

    'By clicking on the following link you\'ll recceive a second email containing a new password:'
        => 'By clicking on the following link you\'ll recceive a second email containing a new password:',

    'If you didn\'t request a password reset for your account, you can safely ignore this email.'
        => 'If you didn\'t request a password reset for your account, you can safely ignore this email.',

    // New Password

    'Below we\'ve got the new password for you:' => 'Below we\'ve got the new password for you:',

    'Please consider changing it in the control panel as sending passwords via email is a potential security flaw.'
        => 'Please consider changing it in the control panel as sending passwords via email is a potential security flaw.',

);
