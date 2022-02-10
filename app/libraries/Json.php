<?php

class Json
{
    private $string;

    public function fix()
    {
        $patterns = [];
        /** garbage removal */
        $patterns[0] = "/([\s:,\{}\[\]])\s*'([^:,\{}\[\]]*)'\s*([\s:,\{}\[\]])/"; //Find any character except colons, commas, curly and square brackets surrounded or not by spaces preceded and followed by spaces, colons, commas, curly or square brackets...
        $patterns[1] = '/([^\s:,\{}\[\]]*)\{([^\s:,\{}\[\]]*)/'; //Find any left curly brackets surrounded or not by one or more of any character except spaces, colons, commas, curly and square brackets...
        $patterns[2] = "/([^\s:,\{}\[\]]+)}/"; //Find any right curly brackets preceded by one or more of any character except spaces, colons, commas, curly and square brackets...
        $patterns[3] = "/(}),\s*/"; //JSON.parse() doesn't allow trailing commas
        /** reformatting */
        $patterns[4] = '/([^\s:,\{}\[\]]+\s*)*[^\s:,\{}\[\]]+/'; //Find or not one or more of any character except spaces, colons, commas, curly and square brackets followed by one or more of any character except spaces, colons, commas, curly and square brackets...
        $patterns[5] = '/["\']+([^"\':,\{}\[\]]*)["\']+/'; //Find one or more of quotation marks or/and apostrophes surrounding any character except colons, commas, curly and square brackets...
        $patterns[6] = '/(")([^\s:,\{}\[\]]+)(")(\s+([^\s:,\{}\[\]]+))/'; //Find or not one or more of any character except spaces, colons, commas, curly and square brackets surrounded by quotation marks followed by one or more spaces and  one or more of any character except spaces, colons, commas, curly and square brackets...
        $patterns[7] = "/(')([^\s:,\{}\[\]]+)(')(\s+([^\s:,\{}\[\]]+))/"; //Find or not one or more of any character except spaces, colons, commas, curly and square brackets surrounded by apostrophes followed by one or more spaces and  one or more of any character except spaces, colons, commas, curly and square brackets...
        $patterns[8] = '/(})(")/'; //Find any right curly brackets followed by quotation marks...
        $patterns[9] = '/,\s+(})/'; //Find any comma followed by one or more spaces and a right curly bracket...
        $patterns[10] = '/\s+/'; //Find one or more spaces...
        $patterns[11] = '/^\s+/'; //Find one or more spaces at start of string...

        $replacements = [];
        /** garbage removal */
        $replacements[0] = '$1 "$2" $3'; //...and put quotation marks surrounded by spaces between them;
        $replacements[1] = '$1 { $2'; //...and put spaces between them;
        $replacements[2] = '$1 }'; //...and put a space between them;
        $replacements[3] = '$1'; //...so, remove trailing commas of any right curly brackets;
        /** reformatting */
        $replacements[4] = '"$0"'; //...and put quotation marks surrounding them;
        $replacements[5] = '"$1"'; //...and replace by single quotation marks;
        $replacements[6] = '\\$1$2\\$3$4'; //...and add back slashes to its quotation marks;
        $replacements[7] = '\\$1$2\\$3$4'; //...and add back slashes to its apostrophes;
        $replacements[8] = '$1, $2'; //...and put a comma followed by a space character between them;
        $replacements[9] = ' $1'; //...and replace by a space followed by a right curly bracket;
        $replacements[10] = ' '; //...and replace by one space;
        $replacements[11] = ''; //...and remove it.

        $this->string = preg_replace($patterns, $replacements, $this->string);

        return $this;
    }

    /**
     * @return string
     * @noinspection PhpUnused
     */
    public function getString()
    {
        return $this->string;
    }

    /**
     * @param string $string
     */
    public function setString($string)
    {
        $this->string = $string;

        return $this;
    }

    /**
     * @return stdClass
     * @throws Exception
     */
    public function toObject()
    {
        $result = json_decode($this->string);
        $this->_validate();

        return $result;
    }

    /**
     * @return array
     * @throws Exception
     * @noinspection PhpUnused
     */
    public function toArray()
    {
        $result = json_decode($this->string, TRUE);
        $this->_validate();

        return $result;
    }

    /**
     * @return void
     * @throws Exception
     */
    private function _validate()
    {
        switch (json_last_error()) {
            case JSON_ERROR_NONE:
                return;
            case JSON_ERROR_DEPTH:
                $error = ' - Maximum stack depth exceeded';
                break;
            case JSON_ERROR_STATE_MISMATCH:
                $error = ' - Underflow or the modes mismatch';
                break;
            case JSON_ERROR_CTRL_CHAR:
                $error = ' - Unexpected control character found';
                break;
            case JSON_ERROR_SYNTAX:
                $error = ' - Syntax error, malformed JSON';
                break;
            case JSON_ERROR_UTF8:
                $error = ' - Malformed UTF-8 characters, possibly incorrectly encoded';
                break;
            default:
                $error = ' - Unknown error';
                break;
        }

        throw new Exception('JSON Error' . $error);
    }
}