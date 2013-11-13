<?php
/**
 * Created by PhpStorm.
 * User: danny
 * Date: 13/11/2013
 * Time: 12:07
 */

namespace application\entity
{
    use application\environment\Environment;
    use application\locale\Locale;
    use core\entity\HTMLEntity;

    abstract class ShareablePageEntity extends BasePageEntity
    {

        protected final function escapeShareUrl($url)
        {
            $fullyEscapedUrl = urlencode($url);
            $replacements = array(
                '%3A' => ':',
                '%2F' => '/',
            );
            return str_replace(array_keys($replacements), array_values($replacements), $fullyEscapedUrl);
        }

        protected function getShareData()
        {

            $shareUrl = $this->getPageShareUrl();
            return array(
                'url' => $shareUrl,
                'escapedUrl' => $this->escapeShareUrl($shareUrl),
                'title' => $this->getPageTitle(),
                'image' => $this->getPageShareImage(),
                'copy' => $this->getPageDescription(),
                'hashtag' => $this->getPageHashTag(),
                'withPattern' => true,
            );
        }


        protected function getPageShareUrl()
        {
            $domain = $this->environment->getCanonicalDomain();
            $path = $this->getPagePath();
            return $domain . $path;
        }

        protected function getPageShareImage()
        {
            return $this->environment->getCanonicalDomain() . '/images/content/' . $this->translate('token-share-image');
        }


        protected function getPagePath()
        {
            //return $this->router->getHomepagePath();
        }


        protected function getPageDescription()
        {
            return $this->translate('token-meta-description');
        }

        protected function getPageHashTag()
        {
            return $this->translate('token-twitter-hashtag');
        }

    }
}