    <?php
    foreach ($this->nav_elem as $name => $url) {
        echo ("<a href='" . $url . "'>" . $name . "</a>\n");
    }
    ?>