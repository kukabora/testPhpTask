<?php
$files = glob('datafiles/[A-Za-z0-9]*.ixt', GLOB_BRACE);
foreach ($files as $file) {
    print($file . "\n");
}
