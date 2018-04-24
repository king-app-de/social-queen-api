<?php

namespace KingApp\SocialQueenApi\Client;

class News extends Base
{
    /** @var Media[] */
    protected $media = [];

    public function setContent(string $content): self
    {
        $this->data['content'] = $content;
        return $this;
    }

    public function setTitle(string $title): self
    {
        $this->data['title'] = $title;
        return $this;
    }

    public function setKeyWords(array $hashTags): self
    {
        $this->data['keywords'] = [];
        foreach ($hashTags as $hashTag) {
            $this->data['keywords'][] = preg_replace('/[#\s\,]+/', '', $hashTag);
        }
        $this->data['keywords'] = implode(",", $this->data['keywords']);
        return $this;
    }

    public function addMediaFile(string $fileName, ?array $settings = []): self
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

    public function setMediaSetting(string $transformation, ?float $ratio = 0): self
    {
        if (!in_array($transformation, ['c', 'o', 'f'])) throw new \Exception('No such transformation possible use c -auto cropp, f - fill, o -original');
        $this->data['gallery']['setting']['type'] = $transformation;
        if ($transformation === 'o' && $ratio === 0) throw new \Exception('For originals the ratio should be 0');
        if ($transformation !== 'o' && !in_array($ratio, [1, 1.33, 1.77])) throw new \Exception('Possible ratios are 1, 1.33, 1.77');
        $this->data['gallery']['setting']['ratio'] = $ratio;
        return $this;
    }

    public function send(): string
    {
        if ($this->media && empty($this->data['gallery']['setting'])) throw new \Exception('There is no media settings');
        foreach ($this->media as $media) {
            $this->data['gallery'][spl_object_hash($media)] = $media->toArray();
        }
        return $this->post('/section/1/new', ['form_params' => $this->data])->hardLink;
    }

    public function create(): self
    {
        return new self($this->client);
    }

    public function remove($id): array
    {
        return $this->delete("/news/$id");
    }
}
