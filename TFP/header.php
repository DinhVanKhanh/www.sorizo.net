<?php
    header('Content-Type: text/plain; charset=utf-8');

    foreach (getallheaders() as $name => $value) {
        echo "$name: $value\n";
    }
    
    $stream = fopen('php://input');
    $data = stream_get_contents($stream);
    fclose($stream);
    echo $data;
    echo "strlen  = ".strlen($data)
    
?>