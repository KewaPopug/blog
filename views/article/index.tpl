{extends file='../layout.tpl'}
{block name=title}
    {$article.title} — Точка зрения
{/block}
{block name=content}
    <nav class="breadcrumbs">
        <a href="{$basePath}/">Главная</a>
        <span>/</span>
        {foreach $article.categories as $category}
            <a href="{$basePath}/category/{$category.slug}">
                {$category.name}
            </a>
            {if !$category@last}
                <span>/</span>
            {/if}
        {/foreach}
    </nav>
    <article class="article">
        <p class="eyebrow">
            {foreach $article.categories as $category}
                <a href="{$basePath}/category/{$category.slug}">
                    {$category.name}
                </a>
                {if !$category@last} · {/if}
            {/foreach}
        </p>
        <h1>{$article.title}</h1>
        <p class="article-lead">{$article.description}</p>
        <div class="article-meta">
            {$article.published_at|date_format:'%d.%m.%Y'} · {$article.views}просмотров
        </div>
        {if $article.image}
            <img class="article-cover" src="{$article.image}" alt="{$article.title}">
        {/if}
        <div class="article-body">
            {$article.body|escape:'html'|nl2br nofilter}
        </div>
    </article>
    {if $related}
        <section class="related">
        <div class="section-heading">
            <div>
                <p class="eyebrow">ЕЩЁ ПО ТЕМЕ</p>
                <h2>Похожие статьи</h2>
            </div>
        </div>
        <div class="article-grid">
            {foreach $related as $item}
                <a class="card" href="{$basePath}/article/{$item.slug}">
                    {if $item.image}
                        <img src="{$item.image}" alt="">
                    {else}
                        <div class="card-placeholder">ТЗ</div>
                    {/if}
                    <div class="card-content">
                        <time>{$item.published_at|date_format:'%d.%m.%Y'}</time>
                        <h3>{$item.title}</h3>
                        <p>{$item.description}</p>
                    </div>
                </a>
            {/foreach}
        </div>
        </section>
    {/if}
{/block}
