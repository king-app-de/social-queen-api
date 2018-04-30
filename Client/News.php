<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

class News extends WithMedia
{
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

    public function addMediaFile(string $fileName, ?array $settings = []): array
    {
        parent::addMediaFile($fileName, $settings);
        if ($this->media && empty($this->data['gallery']['setting'])) throw new \Exception('There is no media settings');
        foreach ($this->media as $media) {
            $params = $media->toArray();
            $this->data['gallery'][spl_object_hash($media)] = $params;
            $response = $params;
        }
        return $response;
    }

    public function send(): \stdClass
    {
        $this->sendMedia();
        return $this->post('news', ['form_params' => $this->data]);
    }

    public function create(): self
    {
        return new self($this->client);
    }

    public function remove($id): string
    {
        return $this->delete("news/{$id}");
    }
}
