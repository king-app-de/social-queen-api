<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class Channel extends Base
{
    public function list(): array
    {
        return $this->get('channel');
    }
}
