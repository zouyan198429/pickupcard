<?php
$handle = fopen("footer.html", "r"); // 输入html文件
$dest = fopen("footer.js", "w"); // 输出js文件

if ($handle) {
    fwrite($dest, "(function() {\n");
    while (($buffer = fgets($handle, 4096)) !== false) {
        $s = addslashes($buffer);
        $line = "\tdocument.write(\"" . rtrim($s, "\r\n") . "\");\n";
        // 处理 / -> \/
        $line = str_replace(['/'],['\/'],$line);
        fwrite($dest, $line);
    }
    fwrite($dest, "}).call();");
    if (!feof($handle)) {
        echo "Error: unexpected fgets() fail\n";
    }
    fclose($handle);
    fclose($dest);
}
?>