<?php
declare(strict_types=1);

namespace App\Repository;

use PDO;

final class ArticleRepository
{
    public function __construct(
        private readonly PDO $db
    ) {}

    public function findByCategory(int $categoryId, string $sort, int $limit, int $offset): array
    {
        $orderBy = $sort === 'views'
            ? 'a.views DESC, a.published_at DESC'
            : 'a.published_at DESC';
        $statement = $this->db->prepare(<<<SQL
             SELECT a.* FROM articles a
             JOIN article_categories ac ON ac.article_id = a.id
             WHERE ac.category_id = ?
             ORDER BY $orderBy, a.id DESC LIMIT ? OFFSET ?
        SQL);
        $statement->bindValue(1, $categoryId, PDO::PARAM_INT);
        $statement->bindValue(2, $limit, PDO::PARAM_INT);
        $statement->bindValue(3, $offset, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }

    public function countByCategory(int $categoryId): int
    {
        $statement = $this->db->prepare(<<<SQL
            SELECT COUNT(*) FROM article_categories WHERE category_id = ?
        SQL);
        $statement->execute([$categoryId]);

        return (int) $statement->fetchColumn();
    }

    public function findBySlug(string $slug): ?array
    {
        $statement = $this->db->prepare(<<<SQL
            SELECT * FROM articles WHERE slug = ? LIMIT 1
        SQL);
        $statement->execute([$slug]);
        $article = $statement->fetch();
        if (!$article) {
            return null;
        }

        $categories = $this->db->prepare(<<<SQL
            SELECT c.* FROM categories c
             JOIN article_categories ac ON ac.category_id = c.id
             WHERE ac.article_id = ? ORDER BY c.name
        SQL);
        $categories->execute([$article['id']]);
        $article['categories'] = $categories->fetchAll();

        return $article;
    }

    public function incrementViews(int $articleId): void
    {
        $statement = $this->db->prepare(<<<SQL
            UPDATE articles SET views = views + 1 WHERE id = ?
        SQL);
        $statement->execute([$articleId]);
    }

    public function findRelated(int $articleId, int $limit = 3): array
    {
        $statement = $this->db->prepare(<<<SQL
            SELECT a.*, COUNT(*) AS shared_categories
             FROM articles a
             JOIN article_categories related ON related.article_id = a.id
             JOIN article_categories current ON current.category_id = related.category_id AND current.article_id = ?
             WHERE a.id <> ?
             GROUP BY a.id
             ORDER BY shared_categories DESC, a.published_at DESC
             LIMIT ?
        SQL);
        $statement->bindValue(1, $articleId, PDO::PARAM_INT);
        $statement->bindValue(2, $articleId, PDO::PARAM_INT);
        $statement->bindValue(3, $limit, PDO::PARAM_INT);
        $statement->execute();

        return $statement->fetchAll();
    }
}
