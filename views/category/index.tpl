{extends file='../layout.tpl'}
{block name=title}
    {$category.name} — Точка зрения{/block}
{block name=content}
    <nav class="breadcrumbs">
        <a href="{$basePath}/">Главная</a><span>/</span>{$category.name}
    </nav>
    <section class="page-intro">
        <p class="eyebrow">КАТЕГОРИЯ</p>
        <h1>{$category.name}</h1>
        <p>{$category.description}</p>
    </section>
    <div class="listing-toolbar">
        <span>Статей: {$category.article_count}</span>
        <div>
            Сортировать: <a class="{if $sort == 'date'}selected{/if}" href="?sort=date">По дате</a>
            <a class="{if $sort == 'views'}selected{/if}" href="?sort=views">По просмотрам</a>
        </div>
    </div>
    <div class="article-grid">
        {foreach $articles as $article}
            <a class="card" href="{$basePath}/article/{$article.slug}">
                {if $article.image}
                    <img src="{$article.image}" alt="">
                {else}
                    <div class="card-placeholder">ТЗ</div>
                {/if}
                <div class="card-content">
                    <time>
                        {$article.published_at|date_format:'%d.%m.%Y'} · {$article.views} просмотров
                    </time>
                    <h3>{$article.title}</h3>
                    <p>{$article.description}</p>
                    <span class="read-more">Читать →</span>
                </div>
            </a>
            {foreachelse}
            <p>В этой категории пока нет статей.</p>
        {/foreach}
    </div>
    {if $totalPages > 1}
        <nav class="pagination">
            {if $page > 1}
                <a href="?sort={$sort}&page={$page-1}">← Назад</a>
            {/if}
            {for $i=1 to $totalPages}
                <a class="{if $i == $page}current{/if}"href="?sort={$sort}&page={$i}">{$i}</a>
            {/for}
            {if $page < $totalPages}
                <a href="?sort={$sort}&page={$page+1}">Далее →</a>
            {/if}
        </nav>
    {/if}
{/block}
