<?php /** @noinspection PhpUnused */

class Media
{
    public function show_image($filename, $alt)
    {
        if (file_exists(FCPATH . $filename)) {
            $prop = array(
                'src' => base_url() . $filename,
                'class' => 'img-responsive',
                'width' => '120',
                'alt' => $alt
            );
            $capa = img($prop);
        } else {
            $capa = $alt;
        }

        return $capa;
    }

    /**
     * Return YouTube video ID by a URI
     *
     * @param string $url
     * @return string
     * @throws ErrorException
     */
    public function get_youtube_code($url)
    {
        $needles = ['?v=', '&v=', 'https://youtu.be/'];

        foreach ($needles as $needle) {
            if (strpos($url, $needle) !== FALSE) {
                $ex = explode($needle, $url);
                return $ex[1];
            }
        }

        throw new ErrorException('Invalid youtube url: ' . $url);
    }
}