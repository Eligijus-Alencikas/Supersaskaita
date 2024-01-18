<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>
    <?php
    echo $this->title;
    ?>
</title>
<style>
    <?php
    include($this->main_css_path);
    foreach ($this->css_filenames as $file) {
        include($this->css_asset_path . $file);
    }
    ?>
</style>