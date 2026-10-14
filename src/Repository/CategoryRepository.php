<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;

class CategoryRepository
{
    public function __construct(
        private readonly PDO $db
    ) {}

    public function findAllWithRecentArticles(): array
    {
        $categories = $this->db->query(
            'SELECT c.*, COUNT(DISTINCT ac.article_id) AS article_count
             FROM categories c
             JOIN article_categories ac ON ac.category_id = c.id
             GROUP BY c.id
             ORDER BY c.name'
        )->fetchAll();

        $recentArticles = $this->db->query(
            'SELECT ranked.* FROM (
                SELECT a.*, ac.category_id,
                       ROW_NUMBER() OVER (PARTITION BY ac.category_id ORDER BY a.published_at DESC, a.id DESC) AS row_num
                FROM articles a
                JOIN article_categories ac ON ac.article_id = a.id
             ) AS ranked
             WHERE ranked.row_num <= 3
             ORDER BY ranked.category_id, ranked.row_num'
        )->fetchAll();

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
}
