<?php

class Create_page
{
    public string $page_path;
    public string $title;
    public string $boilerplate_path;
    public string $header_path;
    public string $footer_path;
    public string $main_js_path;
    public string $main_css_path;
    public string $page_dir;
    public string $file_extension;
    public string $js_asset_path;
    public string $css_asset_path;
    public string $jquery_path;
    public string $nav_path;
    public array $js_filenames;
    public array $css_filenames;
    public array $options;
    public bool $use_jquery;
    public bool $use_nav;
    public array $nav_elem;


    // use named arguments 
    public function __construct(
        string $page_name,
        array $options = [],
        array $js_filenames = [],
        array $css_filenames = [],
        bool $use_jquery = true,
        bool $use_nav = false,
        array $nav_elem = [],
    ) {
        $this->file_extension = $options['file_extension'] ?? "php";
        $this->page_dir = $options['page_dir'] ?? Config::$get["page_dir"];
        $this->title = $options['title'] ?? Config::$get["default_title"];
        $this->boilerplate_path = $options["boilerplate_path"] ?? Config::$get["default_boilerplate"];
        $this->header_path = $options["header_path"] ?? Config::$get["default_header"];
        $this->footer_path = $options["footer_path"] ?? Config::$get["default_footer"];
        $this->main_js_path = $options["main_js_path"] ?? Config::$get["default_js"];
        $this->main_css_path = $options["main_css_path"] ?? Config::$get["default_css"];
        $this->jquery_path = $options["jquery_path"] ?? Config::$get["jquery_path"];
        $this->js_asset_path = $options["js_asset_path"] ?? Config::$get["js_asset_path"];
        $this->css_asset_path = $options["css_asset_path"] ?? Config::$get["css_asset_path"];
        $this->nav_path = $options["nav_path"] ?? Config::$get["nav_path"];
        $this->nav_elem = $nav_elem;
        $this->use_jquery = $use_jquery;
        $this->use_nav = $use_nav;
        $this->page_path = $this->page_dir . $page_name . "." . $this->file_extension;
        $this->js_filenames = $js_filenames;
        $this->css_filenames = $css_filenames;
        $this->options = $options ?? [];
    }

    public function showPage(array $page_variables = [])
    {
        // js and css files are included in header and footer
        // header and footer files are included in the boilerplate
        // page variables are accessed in the page and template files

        include($this->boilerplate_path);
    }
}
