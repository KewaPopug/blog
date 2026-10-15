{extends file='layout.tpl'}
{block name=title}Страница не найдена{/block}
{block name=content}
    <section class="page-intro">
        <p class="eyebrow">404</p>
        <h1>Страница не найдена</h1>
        <p>Возможно, ссылка устарела.</p>
        <a class="text-link" href="{$basePath}/">На главную →</a>
    </section>
{/block}
