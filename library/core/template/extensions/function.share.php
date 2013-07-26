<?php
function smarty_function_share($params, Smarty_Internal_Template $template)
{
    $pattern = '';
    switch ($params['type']) {
        case 'facebook':
            $pattern = 'https://www.facebook.com/dialog/feed?app_id=' . $params['facebookAppId'] .  '&amp;link={url}&amp;picture={image}%3Ftmp=' . time() . '&amp;name={title}&amp;caption=&amp;description={copy}&amp;redirect_uri={url}';
            break;
        case 'twitter':
            $pattern = 'http://twitter.com/intent/tweet?text={title}&amp;url={escapedUrl}&amp;hashtags=' . $params['hashtag'];
            break;
        case 'pinterest':
            $pattern = 'http://pinterest.com/pin/create/button/?url={url}&amp;media={image}&amp;description={title}';
            break;
        case 'googleplus':
            $pattern = 'https://plus.google.com/share?url={escapedUrl}';
            break;
        case 'vkontakte':
            $pattern = 'http://vkontakte.ru/share.php?url={url}';
            break;
        case 'livejournal':
            $pattern = 'http://www.livejournal.com/update.bml?event=%3Ca+href%3D%22{url}%22%3E{title}%3C%2Fa%3E%3Cbr+%2F%3E%3Cimg+src%3D%22{image}%22+%2F%3E&amp;subject={title}';
            break;
        case 'sinaweibo':
            $pattern = 'http://service.weibo.com/share/share.php?url={url}';
            break;
        case 'douban':
            $pattern = 'http://www.douban.com/recommend/?url={url}&amp;title={title}&amp;v=1';
            break;
        case 'stumbleupon':
            $pattern = 'https://www.stumbleupon.com/submit?url={url}';
            break;
        case 'orkut':
            $pattern = 'http://promote.orkut.com/preview?nt=orkut.com&amp;du={url}&amp;tt={title}';
            break;
        case 'renren':
            $pattern = 'http://share.renren.com/share/buttonshare.do?link={url}&amp;title={title}';
            break;
        case 'kaixin':
            $pattern = 'http://www.kaixin001.com/repaste/share.php?rurl={url}&amp;rcontent={url}&amp;rtitle={title}';
            break;
        case 'sohu':
            $pattern = 'http://t.sohu.com/third/post.jsp?url={url}&amp;title={title}&amp;content=utf8';
            break;
    }
    $keys = array(
        'url',
        'escapedUrl',
        'title',
        'image',
        'copy',
    );
    $url = $pattern;
    foreach ($keys as $key) {
        $re = '/(&?)([a-z]+)=\{' . $key . '\}/';
        if (isset($params[$key])) {
            $encodedValue = urlencode($params[$key]);
            $url = str_replace('{' . $key . '}', $encodedValue, $url);
        }
    }

    $trackData = array(
        'action' => $params['trackAction'],
        'category' => $params['trackCategory'],
        'label' => $params['trackLabel']
    );


    echo '<a target="_blank" title="' . $params['type'] . '" class="share-button ' . $params['type'] . '" href="' . $url . '"' . (($params['withPattern']) ?  ' data-pattern="' . $pattern . '"' : '') . ' data-track="' . htmlentities(json_encode($trackData)) . '">'.$params['type'].'</a>';
}
