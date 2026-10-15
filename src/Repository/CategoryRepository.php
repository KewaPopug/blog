<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;

final class CategoryRepository
{
    public function __construct(
        private readonly PDO $db
    ) {}

    public function findAllWithRecentArticles(): array
    {
        $categories = $this->db->query(<<<SQL
             SELECT c.*, COUNT(DISTINCT ac.article_id) AS article_count
             FROM categories c
             JOIN article_categories ac ON ac.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name
        SQL)->fetchAll();

        $recentArticles = $this->db->query(<<<SQL
                SELECT ranked.* FROM (
                SELECT a.*, ac.category_id,
                       ROW_NUMBER() OVER (PARTITION BY ac.category_id ORDER BY a.published_at DESC, a.id DESC) AS row_num
                FROM articles a
                JOIN article_categories ac ON ac.article_id = a.id
             ) AS ranked
             WHERE ranked.row_num <= 3
             ORDER BY ranked.category_id, ranked.row_num
        SQL)->fetchAll();

        $articlesByCategory = [];
        foreach ($recentArticles as $article) {
            $categoryId = (int) $article['category_id'];
            unset($article['category_id'], $article['row_num']);
            $articlesByCategory[$categoryId][] = $article;
        }

        foreach ($categories as &$category) {
            $category['articles'] = $articlesByCategory[(int) $category['id']] ?? [];
        }

        unset($category);
        return $categories;
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(<<<SQL
             SELECT c.*, COUNT(DISTINCT ac.article_id) AS article_count
             FROM categories c
             LEFT JOIN article_categories ac ON ac.category_id = c.id
             WHERE c.slug = ?
             GROUP BY c.id
        SQL);
        $statement->execute([$slug]);

        return $statement->fetch() ?: null;
    }
}
