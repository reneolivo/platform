<?php

namespace Thor\Support;

class Social {

    const FACEBOOK_SHARE_BASE_URL = 'http://www.facebook.com/sharer/sharer.php';
    const TWITTER_SHARE_BASE_URL = 'http://twitter.com/intent/tweet';
    const GOOGLEPLUS_SHARE_BASE_URL = 'https://plus.google.com/share';

    /**
     * Get either a Gravatar URL or complete image tag for a specified email address.
     *
     * @param string $email The email address
     * @param string $s Size in pixels, defaults to 80px [ 1 - 2048 ]
     * @param string $d Default imageset to use [ 404 | mm | identicon | monsterid | wavatar ]
     * @param string $r Maximum rating (inclusive) [ g | pg | r | x ]
     * @param boole $img True to return a complete IMG tag False for just the URL
     * @param array $atts Optional, additional key/value attributes to include in the IMG tag
     * @return String containing either just a URL or a complete image tag
     * @source http://gravatar.com/site/implement/images/php/
     */
    public static function gravatarImageUrl($email, $s = 40, $img = true, $atts = array(), $d = 'mm', $r = 'g') {
        $url = 'http://www.gravatar.com/avatar/';
        $url .= md5(strtolower(trim($email)));
        $url .= "?s=$s&d=$d&r=$r";
        if ($img) {
            $url = '<img src="' . $url . '"';
            foreach ($atts as $key => $val) {
                $url .= ' ' . $key . '="' . $val . '"';
            }
            $url .= ' />';
        }
        return $url;
    }

    public static function facebookInCanvas($app_secret) {
        if (isset($_REQUEST['signed_request'])) {
            // We are in Canvas or Page now
            // Let's extract the data from the signed_request 
            // to check if we are inside a Facebook Page
            $data = self::facebookParseSignedRequest($_REQUEST["signed_request"], $app_secret);

            return is_array($data) && isset($data['algorithm']);
        }
        return false;
    }

    public static function facebookInPage($app_secret) {
        if (isset($_REQUEST['signed_request'])) {
            // We are in Canvas or Page now
            // Let's extract the data from the signed_request 
            // to check if we are inside a Facebook Page
            $data = self::facebookParseSignedRequest($_REQUEST["signed_request"], $app_secret);

            return is_array($data) && isset($data['algorithm']) && isset($data["page"]);
        }
        return false;
    }

    public static function facebookParseSignedRequest($signed_request, $secret) {
        list($encoded_sig, $payload) = explode('.', $signed_request, 2);

        // decode the data
        $sig = base64_decode(strtr($encoded_sig, '-_', '+/'));
        $data = json_decode(base64_decode(strtr($payload, '-_', '+/')), true);

        if (strtoupper($data['algorithm']) !== 'HMAC-SHA256') {
            error_log('Unknown algorithm. Expected HMAC-SHA256');
            return null;
        }

        // check sig
        $expected_sig = hash_hmac('sha256', $payload, $secret, $raw = true);
        if ($sig !== $expected_sig) {
            error_log('Bad Signed JSON signature!');
            return null;
        }

        return $data;
    }

    public static function facebookShareUrl($url, $title = '', $images = array(), $summary = '', $s = 100) {
        //http://www.facebook.com/sharer/sharer.php?s=100&p[url]=myurl&p[images][0]=myimage.png&p[title]=mytitle&p[summary]=mysummary

        $p = array(
            "url" => ($url)
        );
        if (!empty($title)) {
            $p['title'] = ($title);
        }
        if (!empty($images)) {
            $images = is_array($images) ? $images : array($images);
            foreach ($images as $i => $im) {
                $images[$i] = ($im);
            }
            $p['images'] = $images;
        }
        if (!empty($summary)) {
            $p['summary'] = $summary;
        }

        return self::FACEBOOK_SHARE_BASE_URL . '?' . http_build_query(array("s" => $s, "p" => $p));
    }

    public static function twitterShareUrl($text, $url = '', $via = '') {
        //http://twitter.com/intent/tweet?text=The+Inconvenient+Truth+of+Education+%22Reform%22%3A+http%3A%2F%2Fbit.ly%2F12nPcP2+%28via+%40otlcampaign%29

        $content = $url;

        if (!empty($via)) {
            $content.= ' (via @' . $via . ')';
        }

        $remaining_chars = 160 - strlen($content) - 2;

        if ($remaining_chars > 0) {
            $text = str_reduce($text, $remaining_chars - 3, '...');

            if (!empty($url)) {
                $text = $text . ': ';
            }
            $content = $text . $content;
        }

        return self::TWITTER_SHARE_BASE_URL . '?text=' . urlencode($content);
    }

    public static function googleplusShareUrl($url) {
        // https://plus.google.com/share?url=urrrl
        return self::GOOGLEPLUS_SHARE_BASE_URL . '?url=' . urlencode($url);
    }

}
