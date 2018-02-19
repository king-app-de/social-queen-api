<?php

namespace KingApp\SocialQueenApi\Client;

class Channel extends Base
{
    public function series()
    {
        return $this->get('channel');
    }
}
