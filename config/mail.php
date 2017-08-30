<?php

return array(
    "driver" => "smtp",
    "host" => "smtp.gmail.com",
    "port" => 587,
    "from" => array(
        "address" => "onthedotpk@gmail.com",
        "name" => "OntheDot"
    ),
    "encryption"=>"tls",
    "username" => "onthedotpk@gmail.com",
    "password" => "twjeodyxixnidoim",
    "sendmail" => "/usr/sbin/sendmail -bs",
    "pretend" => false
);
