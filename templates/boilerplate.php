<!DOCTYPE html>
<html lang="en">

<head>
    <?php
    include($this->header_path);
    ?>
</head>

<body>
    <span class="wrapper">
        <?php
        if ($this->use_nav) {
            echo "<nav>";
            include($this->nav_path);
            echo "</nav>";
        }
        include($this->page_path);
        include($this->footer_path);
        ?>
    </span>
</body>

</html>