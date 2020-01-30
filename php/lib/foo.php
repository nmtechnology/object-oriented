<?php

namespace pleyba4\ObjectOriented;

require_once (dirname(__DIR__) . "/Classes/autoload.php");

use pleyba4\ObjectOriented\Author;

$hash = password_hash("password", PASSWORD_ARGON2I, ["time_cost => 7"]);

$newAuthor = new Author("8fd6a6e3-8d3e-463c-a4b8-2b2e573ef5ac", "12345678901234567890123456789012", "myprofilepic.com", "author1234@gmail.com", $hash, "Smashed321");

echo ($newAuthor-> getAuthorId()),($newAuthor-> getAuthorActivationToken()),($newAuthor-> getAuthorAvatarUrl()),($newAuthor-> getAuthorEmail()),($newAuthor-> getAuthorHash()),($newAuthor-> getAuthorUsername());

var_dump($newAuthor);