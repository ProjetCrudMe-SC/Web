<?php
$datasToSave = ["126","15","18"];
setcookie(
  'dernierArticleLu',
    json_encode($datasToSave),
    time() + (86400 *30)
);