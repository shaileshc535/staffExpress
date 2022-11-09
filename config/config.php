<?php

session_start();

date_default_timezone_set('Australia/Perth');

define('ENCRYPTIONKEY', 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789');

define('FOOTERTITLE', 'Staff Express');

define('ADMINEMAIL', 'contact@staffexpress.com.au');

define('CONTACTEMAIL', 'help@staffexpress.com.au');



//define('STRIPE_API_KEY','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');

//define('STRIPE_PUBLISHABLE_KEY','pk_live_51L5JNJDjlOsefawKSY0hBz7Rx8tOfjeS1p7a8ViV7YDkUCzzyY9YNFlEfTMbX0hkZWR5zsO82rkv5PFYDDBhTUak00sE76SHFr');



define('STRIPE_API_KEY', 'sk_test_51L5JNJDjlOsefawKLzfXGR59xLd3BApfqeFAlPzHaUpJiTbfFHSiJchUZXPJis5NRVxUoweU3k8GJkdZCP092MCv00lZRpQPxv');

define('STRIPE_PUBLISHABLE_KEY', 'pk_test_51L5JNJDjlOsefawKechdnKFA4HcP0qiHF307htzH1NvLTqsJ8yT5LSl5rb8yygI7y6axIsLblQUTPtPqYFPehxhU00wRGzLJLZ');



// if($_SERVER['HTTPS'] == 'on' && !empty($_SERVER['HTTPS']))

// define('SITEURL','https://www.staffexpress.com.au/staging/');

// else

// define('SITEURL','http://www.staffexpress.com.au/staging/');
define('SITEURL', 'http://localhost//staffexpress/');
