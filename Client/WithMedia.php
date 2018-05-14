<?php declare(strict_types=1);

namespace KingApp\SocialQueenApi\Client;

abstract class WithMedia extends Base
{
    /** @var Media[] */
    protected $media = [];

    /** @return  Media[] */
    public function getMedia(): array
    {
        return $this->media;
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

    public function uploadMediaFile(string $fileName, ?array $settings = []): Media
    {
        $media = (new Media($this->client));
        $media->setPath($fileName);
        foreach ($settings as $name => $value) {
            $method = 'set' . ucfirst($name);
            $media->$method($value);
        }
        $this->media[] = $media;
        return $media->create();
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

    protected function sendMedia(): void
    {
        if ($this->media && empty($this->data['gallery']['setting'])) throw new \Exception('There is no media settings');
        foreach ($this->media as $media) {
            $this->data['gallery'][spl_object_hash($media)] = $media->create()->toArray();
        }
    }
}
