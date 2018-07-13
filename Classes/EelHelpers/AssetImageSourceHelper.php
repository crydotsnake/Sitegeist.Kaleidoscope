<?php
namespace Sitegeist\Kaleidoscope\EelHelpers;

use Neos\Flow\Annotations as Flow;
use Neos\Media\Domain\Model\Image;
use Neos\Media\Domain\Service\AssetService;
use Neos\Media\Domain\Service\ThumbnailService;
use Neos\Media\Domain\Model\ThumbnailConfiguration;
use Neos\Flow\Mvc\ActionRequest;

class AssetImageSourceHelper extends AbstractImageSourceHelper
{

    /**
     * @Flow\Inject
     * @var ThumbnailService
     */
    protected $thumbnailService;

    /**
     * @Flow\Inject
     * @var AssetService
     */
    protected $assetService;

    /**
     * @var Image
     */
    protected $asset;

    /**
     * @var bool
     */
    protected $async = false;

    /**
     * @var ActionRequest
     */
    protected $request;

    /**
     * AssetImageSourceHelper constructor.
     * @param Image $asset
     */
    public function __construct(Image $asset)
    {
        $this->asset = $asset;
    }

    /**
     * @param Image $asset
     */
    public function setAsset(Image $asset): void
    {
        $this->asset = $asset;
    }

    /**
     * @param bool $async
     */
    public function setAsync(bool $async): void
    {
        $this->async = $async;
    }

    /**
     * @param ActionRequest $request
     */
    public function setRequest(ActionRequest $request): void
    {
        $this->request = $request;
    }

    public function scale(float $factor): ImageSourceHelperInterface
    {
        $scaledHelper = clone($this);

        if ($this->targetWidth) {
            $scaledHelper->setWidth(round($factor * $this->targetWidth));
        }
        if ($this->targetHeight) {
            $scaledHelper->setHeight(round($factor * $this->targetHeight));
        }

        return $scaledHelper;
    }

    public function src(): string
    {
        $async = $this->request ? $this->async : false;
        $allowCropping = ($this->targetWidth && $this->targetHeight);
        $allowUpScaling = true;

        $thumbnailConfiguration = new ThumbnailConfiguration(
            $this->targetWidth,
            $this->targetWidth,
            $this->targetHeight,
            $this->targetHeight,
            $allowCropping,
            $allowUpScaling,
            $async
        );

        $thumbnailData = $this->assetService->getThumbnailUriAndSizeForAsset(
            $this->asset,
            $thumbnailConfiguration,
            $this->request
        );

        if ($thumbnailData === null) {
            return '';
        }

        return $thumbnailData['src'];
    }

    public function getWidth() : int
    {
        return $this->asset->getWidth();
    }

    public function getHeight() : int
    {
        return $this->asset->getHeight();
    }
}