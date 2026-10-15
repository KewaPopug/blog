{extends file='layout.tpl'}
{block name=title}Точка зрения — блог{/block}
{block name=content}
    <section class="hero"><p class="eyebrow">ЖУРНАЛ ИДЕЙ</p>
        <h1>Читайте. Думайте.<br><em>Открывайте новое.</em></h1>
        <p>Истории и заметки о технологиях, культуре и повседневной жизни.</p>
    </section>
    {foreach $categories as $category}
        <section class="category-section">
            <div class="section-heading">
                <div>
                    <p class="eyebrow">КАТЕГОРИЯ</p>
                    <h2>{$category.name}</h2>
                    <p class="muted">{$category.description}</p>
                </div>
                <a class="text-link" href="{$basePath}/category/{$category.slug}">Все статьи <span>→</span></a>
            </div>
            <div class="article-grid">
                {foreach $category.articles as $article}
                    <a class="card" href="{$basePath}/article/{$article.slug}">
                        {if $article.image}
                            <img src="{$article.image}" alt="">
                        {else}
                            <div class="card-placeholder">ТЗ</div>
                        {/if}
                        <div class="card-content">
                            <time>
                                {$article.published_at|date_format:'%d.%m.%Y'}
                            </time>
                            <h3>
                                {$article.title}
                            </h3>
                            <p>
                                {$article.description}
                            </p><span class="read-more">Читать →</span>
                        </div>
                    </a>
                {/foreach}
            </div>
        </section>
    {/foreach}
    {if !$categories}
        <div class="empty-state">
            Пока нет опубликованных статей.
        </div>
    {/if}
{/block}
