<!doctype html>
<html lang="ru">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>
        {block name=title}Точка зрения — блог{/block}
    </title>
    <link rel="stylesheet" href="{$basePath}/assets/style.css">
</head>
<body>
<header class="site-header">
    <a class="brand" href="{$basePath}/">Точка зрения<span>.</span></a>
    <span class="header-note">Идеи, которые хочется сохранить</span>
</header>
<main class="container">
    {block name=content}{/block}
</main>
<footer class="footer">Небольшой блог о больших идеях
</footer>
</body>
</html>
