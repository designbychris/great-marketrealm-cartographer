<?php
declare(strict_types=1);

// PHPUnit runs outside WordPress; permit loading guarded plugin classes.
if (!defined('ABSPATH')) {
    define('ABSPATH', dirname(__DIR__) . '/');
}

require_once dirname(__DIR__) . '/src/Workshop.php';

require_once dirname(__DIR__) . '/src/Drafts.php';

require_once dirname(__DIR__) . '/src/CompanionBridge.php';
