<?php

namespace AppBundle\Twig;

use AppBundle\Entity\User;

class AppExtension extends \Twig_Extension
{
    public function getFunctions()
    {
        return array(
            new \Twig_SimpleFunction('avatar', array($this, 'userAvatar')),
        );
    }

    public function userAvatar(User $user, int $size)
    {
        return "https://api.adorable.io/avatars/" . $size . "/" . $user->getEmail() . ".png";
    }
}
