<?php

$data ="
            <?php
            \$SiteAddress = \"$SitePath\";
            \$SiteName = \"$SiteName\";
            ?>
        ";

        $fp = fopen("../lib/sitevariables.php", 'w');
        fwrite($fp, $data);
        fclose($fp);

?>