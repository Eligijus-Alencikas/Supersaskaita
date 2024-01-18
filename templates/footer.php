<footer>
    <p>Author: Eligijus Alenčikas</p>
</footer>

<Script>
    <?php
    if ($this->use_jquery) {
        include($this->jquery_path);
    }
    include($this->main_js_path);
    foreach ($this->js_filenames as $file) {
        include($this->js_asset_path . $file);
    }
    ?>
</Script>