<?php
/**
 * User.php
 * @author    Daniel Mason <daniel@ayeayeapi.com>
 * @copyright (c) 2016 Daniel Mason <daniel@ayeayeapi.com>
 * @license   MIT
 * @see       https://github.com/AyeAyeApi/tutorial-auth
 */

namespace AyeAye\Auth\Database\Entity;

use AyeAye\Formatter\Serializable;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package AyeAye\Auth\Database\Entity
 * @ORM\Entity
 * @ORM\Table(name="users")
 */
class User implements Serializable
{

    /**
     * @var integer
     * @ORM\Id
     * @ORM\Column(type="guid")
     * @ORM\GeneratedValue(strategy="UUID")
     */
    protected $id;

    /**
     * @var string
     * @ORM\Column(type="string",length=255)
     */
    protected $email;

    /**
     * @var string
     * @ORM\Column(type="string",length=255)
     */
    protected $passwordHash;

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param string $email
     */
    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param $password
     * @return bool
     */
    public function checkPassword($password)
    {
        return password_verify($password, $this->passwordHash);
    }

    /**
     * @param string $passwordHash
     */
    public function setPassword($passwordHash)
    {
        $this->passwordHash = password_hash($passwordHash, PASSWORD_DEFAULT);
    }

    /**
     * Serialise for Aye Aye Api response
     * @return array
     */
    public function ayeAyeSerialize()
    {
        return [
            'id' => $this->getId(),
            'email' => $this->getEmail()
        ];
    }
}
