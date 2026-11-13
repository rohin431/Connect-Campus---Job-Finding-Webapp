<?php
require_once __DIR__ . '/includes/functions.php';
try { db()->query('SELECT 1'); echo 'Database connection successful.'; }
catch(Throwable $e){ http_response_code(500); echo 'Database connection failed. Check config.php and import database/campusconnect.sql.'; }
?>