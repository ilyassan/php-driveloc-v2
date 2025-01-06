<?php
class User extends BaseClass
{
    private $id;
    private $first_name;
    private $last_name;
    private $email;
    private $password;
    private $role_id;

    static public $adminRoleId = 2;
    static public $clientRoleId = 1;

    public function __construct($id, $first_name, $last_name, $email, $password, $role_id)
    {
        $this->id = $id;
        $this->first_name = $first_name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->password = $password;
        $this->role_id = $role_id;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getFirstName()
    {
        return $this->first_name;
    }

    public function getLastName()
    {
        return $this->last_name;
    }

    public function getName()
    {
        return $this->first_name . " " . $this->last_name;
    }

    public function getEmail()
    {
        return $this->email;
    }
    
    public function getPassword()
    {
        return $this->password;
    }

    public function isAdmin()
    {
        return $this->role_id == self::$adminRoleId;
    }

    public function isClient()
    {
        return $this->role_id == self::$clientRoleId;
    }

    public function getRoleName()
    {
        return $this->isAdmin() ? "admin" : "client";
    }

    public function save()
    {
        $sql = "INSERT INTO users (first_name, last_name, email, password_hash, role_id) VALUES (:first_name, :last_name, :email, :password_hash, :role_id)";
        self::$db->query($sql);
        self::$db->bind(':first_name', $this->first_name);
        self::$db->bind(':last_name', $this->last_name);
        self::$db->bind(':email', $this->email);
        self::$db->bind(':password_hash', $this->password);
        self::$db->bind(':role_id', self::$clientRoleId);

        if (self::$db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public static function find($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        self::$db->query($sql);
        self::$db->bind(':id', $id);
        self::$db->execute();
        $result = self::$db->single();

        if (self::$db->rowCount() > 0) {
            return new self($result->id, $result->first_name, $result->last_name, $result->email, $result->password_hash, $result->role_id);
        } else {
            return false;
        }
    }

    public static function findUserByEmail($email)
    {
        $sql = "SELECT * FROM users WHERE email = :email";
        self::$db->query($sql);
        self::$db->bind(':email', $email);
        self::$db->execute();
        $result = self::$db->single();

        if (self::$db->rowCount() > 0) {
            return new self($result->id, $result->first_name, $result->last_name, $result->email, $result->password_hash, $result->role_id);
        } else {
            return false;
        }
    }

    public static function count()
    {
        $sql = "SELECT COUNT(*) as count FROM users WHERE role_id = :role_id";
        self::$db->query($sql);
        self::$db->bind(':role_id', self::$clientRoleId);
        self::$db->execute();
        $result = self::$db->single();

        return $result->count;
    }

    public static function getRecentRegistrations($limit)
    {
        $sql = "SELECT *
                FROM users
                WHERE role_id = :role_id
                ORDER BY created_at DESC
                LIMIT :limit";

        self::$db->query($sql);
        self::$db->bind(':role_id', self::$clientRoleId);
        self::$db->bind(':limit', $limit);

        $results = self::$db->results();

        return $results;
    }

    public static function paginate($page, $usersPerPage = 10)
    {
        $offset = ($page - 1) * $usersPerPage;
        $sql = "SELECT u.*, COUNT(r.id) as reservations_count
                FROM users u
                LEFT JOIN reservations r ON r.client_id = u.id
                WHERE u.role_id = :role_id
                GROUP BY u.id
                ORDER BY u.created_at DESC
                LIMIT :offset, :users_per_page";
        
        self::$db->query($sql);
        self::$db->bind(':role_id', self::$clientRoleId);
        self::$db->bind(':offset', $offset);
        self::$db->bind(':users_per_page', $usersPerPage);

        $results = self::$db->results();

        return $results;
    }

    public function createSession()
    {
        $_SESSION['user_id'] = $this->id;
    }

    public function destroySession()
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            unset($_SESSION['user_id']);
            session_destroy();
        }
    }
}