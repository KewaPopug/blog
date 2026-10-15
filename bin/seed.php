<?php
declare(strict_types=1);

require dirname(__DIR__) . '/vendor/autoload.php';

use App\Database\ConnectionFactory;

$db = ConnectionFactory::createFromEnvironment();
$schemaPath = dirname(__DIR__) . '/database/schema.sql';
$schema = file_get_contents($schemaPath);

if ($schema === false) {
    throw new RuntimeException('Не удалось прочитать database/schema.sql');
}

foreach (array_filter(array_map('trim', explode(';', $schema))) as $statement) {
    $db->exec($statement);
}

$categoryRows = [
    ['Технологии', 'technologies', 'Как технологии меняют привычный мир и открывают новые возможности.'],
    ['Культура', 'culture', 'Книги, кино, искусство и явления, которые формируют наше время.'],
    ['Повседневность', 'everyday', 'Практичные идеи и наблюдения о жизни каждый день.'],
];

$articleRows = [
    [
        'Цифровая тишина: зачем нам время без уведомлений', 'digital-quiet',
        'Несколько простых способов вернуть внимание себе и своим делам.',
        "Постоянный поток уведомлений незаметно дробит день на короткие отрезки. Попробуйте выделить два спокойных окна для сообщений и отключить оповещения, которые не требуют немедленного ответа.\n\nНачните с малого: прогулка без телефона или час чтения перед сном. Цифровая тишина — не отказ от технологий, а возможность выбирать, когда они действительно нужны.",
        '/assets/images/technology.svg', 'technologies,everyday', 20,
    ],
    [
        'Небольшие привычки для больших проектов', 'small-habits',
        'Как регулярные короткие шаги помогают доводить идеи до результата.',
        "Большие проекты часто кажутся неподъёмными, пока мы смотрим на них целиком. Разбейте работу на следующий конкретный шаг, который можно выполнить за двадцать минут.\n\nЗаписывайте прогресс и заранее определяйте, с чего начнёте завтра. Устойчивый ритм обычно полезнее редких рывков.",
        '/assets/images/everyday.svg', 'everyday', 11,
    ],
    [
        'Город как открытая книга', 'city-as-book',
        'Архитектура и детали улиц рассказывают истории, которые легко пропустить.',
        "Попробуйте пройти привычный маршрут медленнее и посмотреть вверх. Вы заметите старые вывески, разные эпохи в соседних фасадах и маленькие детали, которые обычно скрывает спешка.\n\nГород хранит память в своей повседневной архитектуре. Для этого не нужен путеводитель — достаточно любопытства.",
        '/assets/images/culture.svg', 'culture,everyday', 8,
    ],
    [
        'Что делает хорошую книгу спутником', 'books-companions',
        'О книгах, к которым возвращаются в разные периоды жизни.',
        "Некоторые книги меняются вместе с читателем. При повторном чтении мы замечаем новые смыслы, потому что задаём тексту другие вопросы и смотрим на него с новым опытом.\n\nСоберите свою небольшую полку таких книг и не торопитесь дочитывать то, что сейчас не откликается.",
        '/assets/images/culture.svg', 'culture', 15,
    ],
    [
        'Технологии, которые становятся незаметными', 'invisible-tech',
        'Лучшие цифровые инструменты часто уходят на второй план.',
        "Удобная технология не требует постоянного внимания. Она снимает лишние шаги, помогает сохранить контекст и оставляет человеку больше пространства для решения самой задачи.\n\nПри выборе инструментов полезно спросить себя не только о возможностях, но и о том, сколько внимания они потребуют.",
        '/assets/images/technology.svg', 'technologies', 5,
    ],
    [
        'Медленный выходной', 'slow-weekend',
        'Идеи для свободного дня без плотного расписания.',
        "Оставьте в выходном дне свободное место. Выберите одну приятную опору — завтрак вне дома, прогулку или встречу — а остальное решите по настроению.\n\nПауза помогает заметить собственные желания, а не только закрывать список задач.",
        '/assets/images/everyday.svg', 'everyday,culture', 2,
    ],
    [
        'Рабочее место без лишнего шума', 'calm-workspace',
        'Как небольшие изменения помогают сосредоточиться на важном.',
        "Уберите со стола всё, что не нужно для текущей задачи. Подготовьте воду, блокнот и нужные материалы заранее.\n\nПорядок не решит задачу за вас, но поможет быстрее вернуться к ней после отвлечения.",
        '/assets/images/everyday.svg', 'everyday', 7,
    ],
    [
        'Зачем музеям цифровые архивы', 'digital-archives',
        'Цифровые коллекции открывают культурное наследие новой аудитории.',
        "Онлайн-архивы помогают находить материалы, которые раньше были доступны только в читальном зале. Хороший каталог показывает не только объект, но и контекст его появления.\n\nТак цифровые инструменты помогают бережно сохранять и заново открывать культурную память.",
        '/assets/images/culture.svg', 'culture,technologies', 4,
    ],
    [
        'Прогулка как способ заметить детали', 'walking-details',
        'Неспешный маршрут помогает увидеть знакомое место по-новому.',
        "Выберите улицу, по которой давно не ходили пешком. Обратите внимание на свет в окнах, деревья во дворах и небольшие места, мимо которых обычно проезжаете.\n\nИногда новое впечатление начинается с привычного маршрута и свободного часа.",
        '/assets/images/everyday.svg', 'everyday,culture', 3,
    ],
    [
        'Маленький ритуал для начала дня', 'morning-ritual',
        'Несложный утренний ритуал помогает начать день в собственном темпе.',
        "Не обязательно менять всё расписание, чтобы утро стало спокойнее. Начните с одного повторяемого действия: выпейте воды, откройте окно или запишите главную задачу дня.\n\nПусть этот ритуал будет коротким и приятным — так ему проще стать частью повседневности.",
        '/assets/images/everyday.svg', 'everyday', 6,
    ],
];

$db->beginTransaction();

try {
    $insertCategory = $db->prepare(
        'INSERT INTO categories (name, slug, description) VALUES (?, ?, ?) '
        . 'ON DUPLICATE KEY UPDATE name = VALUES(name), description = VALUES(description)'
    );
    foreach ($categoryRows as $row) {
        $insertCategory->execute($row);
    }

    $findCategory = $db->prepare('SELECT id FROM categories WHERE slug = ?');
    $insertArticle = $db->prepare(
        'INSERT INTO articles (title, slug, description, body, image, published_at, views) '
        . 'VALUES (?, ?, ?, ?, ?, DATE_SUB(NOW(), INTERVAL ? DAY), ?) '
        . 'ON DUPLICATE KEY UPDATE title = VALUES(title), description = VALUES(description), '
        . 'body = VALUES(body), image = VALUES(image), published_at = VALUES(published_at)'
    );
    $findArticle = $db->prepare('SELECT id FROM articles WHERE slug = ?');
    $deleteLinks = $db->prepare('DELETE FROM article_categories WHERE article_id = ?');
    $insertLink = $db->prepare('INSERT INTO article_categories (article_id, category_id) VALUES (?, ?)');

    foreach ($articleRows as $index => $row) {
        [$title, $slug, $description, $body, $image, $categorySlugs, $views] = $row;
        $insertArticle->execute([$title, $slug, $description, $body, $image, $index * 4, $views]);

        $findArticle->execute([$slug]);
        $articleId = (int) $findArticle->fetchColumn();
        $deleteLinks->execute([$articleId]);

        foreach (explode(',', $categorySlugs) as $categorySlug) {
            $findCategory->execute([$categorySlug]);
            $categoryId = $findCategory->fetchColumn();

            if ($categoryId === false) {
                throw new RuntimeException("Не найдена категория с slug {$categorySlug}");
            }

            $insertLink->execute([$articleId, (int) $categoryId]);
        }
    }

    $db->commit();
    echo "Сидинг завершён: категории и статьи добавлены.\n";
} catch (Throwable $error) {
    $db->rollBack();
    throw $error;
}
