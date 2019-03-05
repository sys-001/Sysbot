<?php

require_once 'bootstrap.php';

return Doctrine\ORM\Tools\Console\ConsoleRunner::createHelperSet($entity_manager);