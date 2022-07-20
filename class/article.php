<?php

/**
 * Article
 *
 * A piece of writing for publication
 */
class Article
{
    /**
     * Unique identifier
     * @var integer
     */
    public $id;

    public $image_file;
    /**
     * The article title
     * @var string
     */
    public $title;

    /**
     * The article content
     * @var string
     */
    public $content;

    /**
     * The publication date and time
     * @var datetime
     */
    public $published_at;

    /**
     * Validation errors
     * @var array
     */
    public $errors = [];

    /**
     * Get all the articles
     *
     * @param object $conn Connection to the database
     *
     * @return array An associative array of all the article records
     */
    public static function getAll($conn)
    {
        $sql = "SELECT *
                FROM artice
                ORDER BY published_at;";

        $results = $conn->query($sql);

        return $results->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Get the article record based on the ID
     *
     * @param object $conn Connection to the database
     * @param integer $id the article ID
     * @param string $columns Optional list of columns for the select, defaults to *
     *
     * @return mixed An object of this class, or null if not found
     */
    public static function getByID($conn, $id, $columns = '*')
    {
        $sql = "SELECT $columns
                FROM artice
                WHERE id = :id";

        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        $stmt->setFetchMode(PDO::FETCH_CLASS, 'Article');

        if ($stmt->execute()) {

            return $stmt->fetch();
        }
    }

    /**
     * Update the article with its current property values
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the update was successful, false otherwise
     */
    public function update($conn)
    {
        if ($this->validate()) {

            $sql = "UPDATE artice
                    SET title = :title,
                        content = :content,
                        published_at = :published_at
                    WHERE id = :id";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

            if ($this->published_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);
            }

            return $stmt->execute();
        } else {
            return false;
        }
    }
    public function setCategories($conn, $ids)
    {
        if ($ids) {
            $sql = "INSERT IGNORE INTO article_category (article_id,category_id) VALUES ";
            $value = [];
            foreach ($ids as $id) {
                $value[] = "({$this->id},?)";
            }
            $sql .= implode(",", $value);

            $stmt = $conn->prepare($sql);

            foreach ($ids as $i => $id) {
                $stmt->bindValue($i + 1, $id, PDO::PARAM_INT);
            }

            $stmt->execute();
        }
    }
    /**
     * Validate the properties, putting any validation error messages in the $errors property
     *
     * @return boolean True if the current properties are valid, false otherwise
     */
    protected function validate()
    {
        if ($this->title == '') {
            $this->errors[] = 'Title is required';
        }
        if ($this->content == '') {
            $this->errors[] = 'Content is required';
        }

        if ($this->published_at != '') {
            $date_time = date_create_from_format('Y-m-d H:i:s', $this->published_at);

            if ($date_time === false) {

                $this->errors[] = 'Invalid date and time';
            } else {

                $date_errors = date_get_last_errors();

                if ($date_errors['warning_count'] > 0) {
                    $this->errors[] = 'Invalid date and time';
                }
            }
        }

        return empty($this->errors);
    }

    /**
     * Delete the current article
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the delete was successful, false otherwise
     */
    public function delete($conn)
    {
        $sql = "DELETE FROM artice
                WHERE id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Insert a new article with its current property values
     *
     * @param object $conn Connection to the database
     *
     * @return boolean True if the insert was successful, false otherwise
     */
    public function create($conn)
    {
        if ($this->validate()) {

            $sql = "INSERT INTO artice (title, content, published_at)
                    VALUES (:title, :content, :published_at)";

            $stmt = $conn->prepare($sql);

            $stmt->bindValue(':title', $this->title, PDO::PARAM_STR);
            $stmt->bindValue(':content', $this->content, PDO::PARAM_STR);

            if ($this->published_at == '') {
                $stmt->bindValue(':published_at', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindValue(':published_at', $this->published_at, PDO::PARAM_STR);
            }

            if ($stmt->execute()) {
                $this->id = $conn->lastInsertId();
                return true;
            }
        } else {
            return false;
        }
    }
    public static function getPage($conn, $limit, $offset)
    {
        $sql = "SELECT a.*, category.name AS category_name
                FROM (SELECT *
                FROM artice
                ORDER BY published_at
                LIMIT :limit
                OFFSET :offset) AS a
                LEFT JOIN article_category
                ON a.id = article_category.article_id
                LEFT JOIN category
                ON article_category.category_id = category.id";



        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':limit', $limit, PDO::PARAM_INT);
        $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);

        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public static function getTotal($conn)
    {
        return $conn->query('SELECT COUNT(*) FROM artice')->fetchColumn();
    }

    public function setImageFile($conn, $filename)
    {
        $sql = "UPDATE  artice
                SET image_file = :image_file
                WHERE id=:id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->bindValue(':image_file', $filename, $filename == null ? PDO::PARAM_NULL : PDO::PARAM_STR);

        return $stmt->execute();
    }
    public static function getWithCategories($conn, $id)
    {
        $sql = "SELECT artice.*,category.name AS category_name FROM artice JOIN article_category 
        ON artice.id = article_category.article_id JOIN category 
        ON article_category.category_id = category.id
        WHERE artice.id = :id";

        $stmt = $conn->prepare($sql);

        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function getCategories($conn)
    {
        $sql = "SELECT category.*
         FROM category
         JOIN article_category
         ON category.id = article_category.category_id
         WHERE article_id=:id";
        $stmt = $conn->prepare($sql);
        $stmt->bindValue(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}