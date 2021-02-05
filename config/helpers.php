<?php

if (! function_exists('image_asset_url')) {
    function image_asset_url($url)
    {
        return '/public/uploads/' . $url;
    }
}

if (! function_exists('old')) {
    function old(string $value, string $default = '')
    {
        return $_SESSION['oldInputValues'][$value] ?? $default;
    }
}

if (! function_exists('error')) {
    function error(string $value)
    {
        return isset( $_SESSION['errorMessageBag'][$value] ) 
                ? $_SESSION['errorMessageBag'][$value] 
                : false;
    }
}

if (! function_exists('is_current_uri')) {
    function is_current_uri(string $uri) 
    {
        $path = $_SERVER['REQUEST_URI'];
        $position = strpos($path, '?');
        $trim_path = str_replace('.php', '', $path);

        if ( 
            $trim_path == $uri || ( $position && ( str_replace('.php', '', substr($path, 0, $position )) == $uri ) ) 
        ) {
            return true;
        }
    }
}

if (! function_exists('e')) {
    function e(string $value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }
}

if (! function_exists('method')) {
    function method(string $method)
    {
        echo "<input type='hidden' name='_method' value='{$method}'>";
    }
}

if (! function_exists('csrf')) {
    function csrf()
    {
        echo "<input type='hidden' name='_token' value='{$_SESSION['_token']}'>";
    }
}

if (! function_exists("get_header")) {
    function get_header(string $name = null, array $args = array())
    {
        if (empty($name)) {
            include "partials/header.php";
        } else {
            include "partials/header-{$name}.php";
        }
    }
}

if (! function_exists("get_footer")) {
    function get_footer(string $name = null, array $args = array())
    {
        if (empty($name)) {
            include "partials/footer.php";
        } else {
            include "partials/footer-{$name}.php";
        }
    }
}

if (! function_exists("get_template_part")) {
    function get_template_part(string $slug, string $name = null, array $args = array())
    {
        if (empty($name)) {
            include "{$slug}.php";
        } else {
            include "{$slug}-{$name}.php";
        }
    }
}