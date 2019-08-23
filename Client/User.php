<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class User extends Base
{
    public function getData(): \stdClass
    {
        return $this->get('user');
    }
}
