<?php
echo "SAPI Name: " . php_sapi_name() . "\n";
echo "Is CLI Server: " . (php_sapi_name() === 'cli-server' ? 'Yes' : 'No') . "\n";
?>