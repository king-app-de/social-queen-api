<?php

namespace KingApp\SocialQueenApi\Client;

class Post extends Base
{
    /** @var Media[] */
    protected $media = [];

    public function setContent($content)
    {
        $this->data['content'] = $content;
        return $this;
    }

    public function setTwitterContent($content)
    {
        $this->data['twitter']['content'] = $content;
        return $this;
    }

    public function setTitle($title)
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function setExceedTwitterContent($flag)
    {
        $this->data['twitter']['exceed'] = $flag;
        return $this;
    }

    public function setInstagramCredentials($credentials)
    {
        foreach ($credentials as $uuid => $loginAndPassword) {
            $this->data['channel']['login'][$uuid] = [$loginAndPassword['login'], $loginAndPassword['password']];
        }
        return $this;
    }

    public function setChannels($channels)
    {
        $this->data['channel']['my'] = [];
        foreach ($channels as $channel) {
            $this->data['channel']['my'][] = (string)$channel;
        }
        return $this;
    }

    public function setSharedChannels($channels)
    {
        $this->data['channel']['shared'] = [];
        foreach ($channels as $channel) {
            $this->data['channel']['shared'][] = (string)$channel;
        }
        return $this;
    }

    public function setHashTags($hashTags)
    {
        $this->data['hashTags'] = [];
        foreach ($hashTags as $hashTag) {
            $this->data['hashTags'][] = preg_replace('/[#\s\,]+/', '', $hashTag);
        }
        return $this;
    }

    public function setFacebookLocationId($id)
    {
        $this->data['location']['facebookId'] = $id;
        return $this;
    }

    public function setPublishedAt(\DateTime $dateTime)
    {
        $this->data['publishedAt'] = $dateTime->format(DATE_ATOM);
        return $this;
    }

    public function addMediaFile($fileName, $settings = [])
    {
        $media = (new Media($this->client));
        $media->setPath($fileName);
        foreach ($settings as $name => $value) {
            $method = 'set' . ucfirst($name);
            $media->$method($value);
        }
        $this->media[] = $media;
        return $this;
    }

    public function setMediaSetting($transformation, $ratio = 0)
    {
        if (!in_array($transformation, ['c', 'o', 'f'])) throw new \Exception('No such transformation possible use c -auto cropp, f - fill, o -original');
        $this->data['gallery']['setting']['type'] = $transformation;
        if ($transformation === 'o' && $ratio === 0) throw new \Exception('For originals the ratio should be 0');
        if ($transformation !== 'o' && !in_array($ratio, [1, 1.33, 1.77])) throw new \Exception('Possible ratios are 1, 1.33, 1.77');
        $this->data['gallery']['setting']['ratio'] = $ratio;
        return $this;
    }

    public function send()
    {
        if ($this->media && empty($this->data['gallery']['setting'])) throw new \Exception('There is no media settings');
        foreach ($this->media as $media) {
            $this->data['gallery'][spl_object_hash($media)] = $media->toArray();
        }
        return $this->post('posts', ['form_params' => $this->data])->message;
    }

    public function create()
    {
        return new self($this->client);
    }

    public function series()
    {
        return $this->get('posts');
    }
}
