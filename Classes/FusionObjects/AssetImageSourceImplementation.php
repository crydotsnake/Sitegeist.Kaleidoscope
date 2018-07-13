<?php
namespace Sitegeist\Kaleidoscope\FusionObjects;

use Neos\Flow\Annotations as Flow;
use Sitegeist\Kaleidoscope\EelHelpers\ImageSourceHelperInterface;
use Sitegeist\Kaleidoscope\EelHelpers\AssetImageSourceHelper;
use Neos\Fusion\FusionObjects\AbstractFusionObject;
use Neos\Media\Domain\Model\Image;

class AssetImageSourceImplementation extends AbstractImageSourceImplementation
{
    /**
     * @return mixed
     */
    public function getAsset() : Image
    {
        return $this->fusionValue('asset');
    }

    /**
     * @return mixed
     */
    public function getAsync()
    {
        return $this->fusionValue('async');
    }

    /**
     * Create helper and initialize with the default values
     *
     * @return ImageSourceHelperInterface
     */
    public function createHelper() : ImageSourceHelperInterface
    {
        $asset = $this->getAsset();
        $helper = new AssetImageSourceHelper($asset);
        $helper->setAsync((bool)$this->getAsync());
        $helper->setRequest($this->getRuntime()->getControllerContext()->getRequest());

        return $helper;
    }
}