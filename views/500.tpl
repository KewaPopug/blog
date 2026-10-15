{extends file='layout.tpl'}
{block name=title}Ошибка сервера — Точка зрения{/block}
{block name=content}
    <section class="page-intro">
        <p class="eyebrow">500</p>
        <h1>Не удалось загрузить страницу</h1>
        <p>Попробуйте обновить страницу позже.</p>
        <a class="text-link" href="{$basePath}/">На главную →</a>
    </section>
{/block}
