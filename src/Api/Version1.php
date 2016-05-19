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
use AyeAye\Auth\Database\Entity\User;
use AyeAye\Auth\Database\Factory;

/**
 * Class Version1
 * @package AyeAye\Auth
 */
class Version1 extends Controller
{
    public function putUserEndpoint($email, $password)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPassword($password);

        try {
            $entityManager = Factory::getEntityManager();
            $entityManager->persist($user);
            $entityManager->flush();
            return true;
        }
        catch(\Exception $e) {
            return $e->getMessage();
        }
    }
}
