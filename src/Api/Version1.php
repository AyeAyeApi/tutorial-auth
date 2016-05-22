<?php
/**
 * Version1.php
 * @author    Daniel Mason <daniel@ayeayeapi.com>
 * @copyright (c) 2016 Daniel Mason <daniel@ayeayeapi.com>
 * @license   MIT
 * @see       https://github.com/AyeAyeApi/tutorial-auth
 */

namespace AyeAye\Auth\Api;

use AyeAye\Api\Controller;
use AyeAye\Api\Exception as AyeAyeException;
use AyeAye\Auth\Database\Entity\User;
use AyeAye\Auth\Database\Factory;

/**
 * Class Version1
 * @package AyeAye\Auth
 */
class Version1 extends Controller
{
    /**
     * Insert a new user
     * @param $email
     * @param $password
     * @return string
     */
    public function postUserEndpoint($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        $entityManager = Factory::getEntityManager();
        $entityManager->persist($user);
        $entityManager->flush();
        return $user->getId();
    }

    /**
     * Finds a user by their ID
     * @param string $user The users ID
     * @throws AyeAyeException
     * @return User Returns a user object
     */
    public function getUserEndpoint($user)
    {
        if (!$user) {
            throw new AyeAyeException("A 'user' parameter must be provided", 400);
        }

        $entityManager = Factory::getEntityManager();
        $userObject = $entityManager
            ->getRepository(User::class)
            ->find($user);
        yield 'user' => $userObject;
    }
}
