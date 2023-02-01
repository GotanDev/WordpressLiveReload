<?php
/** Print MD5 footprint of active theme directory */
require __DIR__ . '/../../../wp-blog-header.php';

if (!WP_DEBUG) {
    die();
}
function md5_directory($directoryFullpath): string
{
    $files = scandir($directoryFullpath);
    $md5 = '';
    foreach ($files as $file) {
        if ($file == '.' || $file == '..') {
            continue;
        }
        if (is_dir($directoryFullpath . DIRECTORY_SEPARATOR . $file)) {
            $md5 .= md5_directory($directoryFullpath . DIRECTORY_SEPARATOR . $file);
        } else {
            $md5 .= md5_file($directoryFullpath . DIRECTORY_SEPARATOR . $file);
        }
    }
    return md5($md5);
}

$md5sum =  md5_directory(get_template_directory());
http_response_code(200);

die($md5sum);
