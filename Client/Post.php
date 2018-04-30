<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class Post extends WithMedia
{
    public function setContent(string $content): self
    {
        $this->data['content'] = $content;
        return $this;
    }

    public function setTwitterContent(string $content): self
    {
        $this->data['twitter']['content'] = $content;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function setExceedTwitterContent(bool $flag): self
    {
        $this->data['twitter']['exceed'] = $flag;
        return $this;
    }

    public function setInstagramCredentials(array $credentials): self
    {
        foreach ($credentials as $uuid => $loginAndPassword) {
            $this->data['channel']['login'][$uuid] = [$loginAndPassword['login'], $loginAndPassword['password']];
        }
        return $this;
    }

    public function setChannels(array $channels): self
    {
        $this->data['channel']['my'] = [];
        foreach ($channels as $channel) {
            $this->data['channel']['my'][] = (string)$channel;
        }
        return $this;
    }

    public function setSharedChannels(array $channels): self
    {
        $this->data['channel']['shared'] = [];
        foreach ($channels as $channel) {
            $this->data['channel']['shared'][] = (string)$channel;
        }
        return $this;
    }

    public function setHashTags(array $hashTags): self
    {
        $this->data['hashTags'] = [];
        foreach ($hashTags as $hashTag) {
            $this->data['hashTags'][] = preg_replace('/[#\s\,]+/', '', $hashTag);
        }
        return $this;
    }

    public function setFacebookLocationId(string $id): self
    {
        $this->data['location']['facebookId'] = $id;
        return $this;
    }

    public function setPublishedAt(\DateTime $dateTime): self
    {
        $this->data['publishedAt'] = $dateTime->format(DATE_ATOM);
        return $this;
    }

    public function send(): string
    {
        $this->sendMedia();
        return $this->post('posts', ['form_params' => $this->data])->message;
    }

    public function create(): self
    {
        return new self($this->client);
    }

    public function list(): array
    {
        return $this->get('posts');
    }
}
