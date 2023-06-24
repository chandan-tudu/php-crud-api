<?php
require_once __DIR__ . "/Database.php";
require_once __DIR__ . "/Main.php";

use Main as Response;

class Post extends Database
{
    private $DB;

    function __construct()
    {
        $this->DB = Database::__construct();
    }

    private function filter($data)
    {
        return htmlspecialchars(trim(htmlspecialchars_decode($data)), ENT_NOQUOTES);
    }

    // Create a new post
    public function create(string $title, string $content, string $author)
    {
        $title = $this->filter($title);
        $content = $this->filter($content);
        $author = $this->filter($author);

        try {
            $sql = "INSERT INTO `posts` (`title`,`content`,`author`) VALUES (:title,:content,:author)";
            $stmt = $this->DB->prepare($sql);

            $stmt->bindParam(":title", $title, PDO::PARAM_STR);
            $stmt->bindParam(":content", $content, PDO::PARAM_STR);
            $stmt->bindParam(":author", $author, PDO::PARAM_STR);

            $stmt->execute();

            $last_id = $this->DB->lastInsertId();
            Response::json(1, 201, "Post has been created successfully", "post_id", $last_id);

        } catch (PDOException $e) {
            Response::json(0, 500, $e->getMessage());
        }
    }

    // Fetch all posts or Get a single post through the post ID
    public function read($id = false, $return = false)
    {
        try {
            $sql = "SELECT * FROM `posts`";
            // If post id is provided
            if ($id !== false) {
                // Post id must be a number
                if (is_numeric($id)) {
                    $sql = "SELECT * FROM `posts` WHERE `id`='$id'";
                } else {
                    Response::_404();
                }
            }
            $query = $this->DB->query($sql);
            if ($query->rowCount() > 0) {
                $allPosts = $query->fetchAll(PDO::FETCH_ASSOC);
                // If ID is Provided, send a single post.
                if ($id !== false) {
                    // IF $return is true then return the single post
                    if ($return) return $allPosts[0];
                    Response::json(1, 200, null, "post", $allPosts[0]);
                }
                Response::json(1, 200, null, "posts", $allPosts);
            }
            // If the post id does not exist in the database
            if ($id !== false) {
                Response::_404();
            }
            // If there are no posts in the database.
            Response::json(1, 200, "Please Insert Some posts...", "posts", []);
        } catch (PDOException $e) {
            Response::json(0, 500, $e->getMessage());
        }
    }

    // Update an existing post
    public function update(int $id, Object $data)
    {
        try {
            $sql = "SELECT * FROM `posts` WHERE `id`='$id'";
            $query = $this->DB->query($sql);
            if ($query->rowCount() > 0) {
                $the_post = $query->fetch(PDO::FETCH_OBJ);

                $title = (isset($data->title) && !empty(trim($data->title))) ? $this->filter($data->title) : $the_post->title;
                $content = (isset($data->body) && !empty(trim($data->body))) ? $this->filter($data->body) : $the_post->content;
                $author = (isset($data->author) && !empty(trim($data->author))) ? $this->filter($data->author) : $the_post->author;

                $update_sql = "UPDATE `posts` SET `title`=:title,`content`=:content,`author`=:author,`updated_at`=NOW() WHERE `id`='$id'";

                $stmt = $this->DB->prepare($update_sql);
                $stmt->bindParam(":title", $title, PDO::PARAM_STR);
                $stmt->bindParam(":content", $content, PDO::PARAM_STR);
                $stmt->bindParam(":author", $author, PDO::PARAM_STR);

                $stmt->execute();

                Response::json(1, 200, "Post Updated Successfully", "post", $this->read($id, true));
            }

            Response::json(0, 404, "Invalid Post ID.");

        } catch (PDOException $e) {
            Response::json(0, 500, $e->getMessage());
        }
    }

    // Delete a Post
    public function delete(int $id)
    {
        try {
            $sql =  "DELETE FROM `posts` WHERE `id`='$id'";
            $query = $this->DB->query($sql);
            if ($query->rowCount() > 0) {
                Response::json(1, 200, "Post has been deleted successfully.");
            }
            Response::json(0, 404, "Invalid Post ID.");
        } catch (PDOException $e) {
            Response::json(0, 500, $e->getMessage());
        }
    }
}