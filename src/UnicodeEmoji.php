<?php


class UnicodeEmoji {

    // const MODE_AUTO     = 99;
    const MODE_BINARY   = 1;
    const MODE_HTML     = 2;
    const MODE_EMOJIONE = 3;

    const EMOJIONE_URL = "//cdn.jsdelivr.net/emojione/assets/png/";
    const EMOJIONE_VER = "2.2.4";

    private $emoji_list ;

    public function __construct()
    {
        $emoji = array();
        $data = file(dirname(__FILE__)."/data/emoji-ordering.txt");
        foreach ($data as $line) {
            //コメント行は無視する
            if (preg_match("/^\# /", $line) === 1) continue;

            //Unicode絵文字定義を抽出
            preg_match("/^([^;]*) ; [0-9]{1,2}\.[0-9]{1} \# [^ ]* (.*)$/", $line, $matches);
            if (count($matches) === 3) {
                $emoji[$matches[2]] = $matches[1];
            }
        }
        $this->emoji_list = $emoji;
    }

    public function getEmojiList()
    {
        return $this->emoji_list;
    }

    /**
     * 指定されたUnicode絵文字を返却する
     * 
     * @param $key  string 絵文字の名称
     * @param $mode int    返却する絵文字の種類（1:Unicodeバイナリ 2:HTML参照形式 3:EmojiONE PNG）
     */
    public function get(string $key, int $mode = self::MODE_BINARY)
    {
        if (!array_key_exists($key,  $this->emoji_list)) return "";

        switch ($mode) {
            case self::MODE_BINARY:
                return $this->getUnicodeBinary($key);
                break;

            case self::MODE_HTML:
                return $this->getUnicodeHtml($key);
                break;

            // case self::MODE_EMOJIONE:
            //     return $this->getUnicodeEmojiOneImage($key);
            //     break;

            default:
                # code...
                break;
        }
    }


    /**
     * 指定されたUnicode絵文字をUnicodeバイナリで返却する
     * 
     * @param $key  string 絵文字の名称
     * @return string Unicodeバイナリ
     */
    private function getUnicodeBinary(string $key)
    {
        $code_point = explode(" ", $this->emoji_list[$key]);
        $code_point = str_replace("U+", "", $code_point);
        $bin = '';
        foreach ($code_point as $code) {
            $bin .= hex2bin(str_repeat('0', 8 - strlen($code)).$code);
        }
        $char = mb_convert_encoding($bin, 'UTF-8', 'UTF-32BE');

        return $char;
    }


    /**
     * 指定されたUnicode絵文字をHTML参照形式で返却する
     * 
     * @param $key  string 絵文字の名称
     * @return string Unicodeバイナリ
     */
    private function getUnicodeHtml(string $key)
    {
        $code_point = explode(" ", $this->emoji_list[$key]);
        $code_point = str_replace("U+", "&#x", $code_point);
        return implode("", $code_point);
    }


    /**
     * 指定されたUnicode絵文字をEmojiONEのimgタグ形式で返却する
     * 
     * @param $key  string 絵文字の名称
     * @return string Unicodeバイナリ
     */
    private function getUnicodeEmojiOneImage(string $key)
    {
        $code_point = $this->emoji_list[$key];
        $image_filename = str_replace(array("U+", " "), array("", '-'), $code_point);
        $image_filename = strtolower($image_filename).".png";
        return '<img src="'.self::EMOJIONE_URL.$image_filename.'?v='.self::EMOJIONE_VER.'">';
    }

}

?>
