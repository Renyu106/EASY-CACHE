<?php

class CACHE
{
    private function RETURN($STATUS, $MSG = null, $DATA = null)
    {
        $RETURN = array(
            "STATUS" => ($STATUS == "OK") ? "OK" : "ERR",
            (isset($MSG)) ? "MSG" : "" => $MSG,
            (isset($DATA)) ? "DATAs" : "" => $DATA
        );
        foreach ($RETURN as $KEY => $VALUE) {
            if (empty($VALUE)) {
                unset($RETURN[$KEY]);
            }
        }
        return $RETURN;
    }

    private function _ENCODE_ARRAY($DATA)
    {
        return base64_encode(gzcompress(json_encode($DATA)));
    }

    public function SAVE($DATA_KEY, $ARRAY = array())
    {
        $NOW = time();
        $CACHE_META = array(
            "CREATED_AT" => $NOW,
            "UPDATED_AT" => $NOW,
            "DATA_KEY" => $DATA_KEY,
        );

        $FILE_NAME = CACHE_LOCATION . "/" . $DATA_KEY;

        if (file_exists($FILE_NAME) && $CACHE_FILE = file_get_contents($FILE_NAME)) {
            $OLD_CACHE = json_decode(gzuncompress(base64_decode($CACHE_FILE)), true);
            $CACHE_META['UPDATED_AT'] = $NOW;
            $ARRAY = array_merge($ARRAY, $OLD_CACHE['DATA']);
        }

        $CACHE_DATA = self::_ENCODE_ARRAY(array(
            "META" => $CACHE_META,
            "DATA" => $ARRAY,
        ));

        if (!is_dir($FILE_NAME)) {
            $FILE_NAME_DIR = explode("/", $FILE_NAME);
            array_pop($FILE_NAME_DIR);
            $FILE_NAME_DIR = implode("/", $FILE_NAME_DIR);
            if (!is_dir($FILE_NAME_DIR)) {
                mkdir($FILE_NAME_DIR, 0777, true);
            }
        }

        @chown($FILE_NAME, "www-data");
        if (!file_put_contents($FILE_NAME, $CACHE_DATA)) {
            return self::RETURN("ERR", "CACHE SAVE FAILED");
        } else {
            return self::RETURN("OK", "CACHE SAVE SUCCESS");
        }
    }

    public function GET($DATA_KEY)
    {
        $FILE_NAME = CACHE_LOCATION . "/" . $DATA_KEY;

        if ($CACHE_FILE = file_get_contents($FILE_NAME)) {
            $CACHE_DATA = json_decode(gzuncompress(base64_decode($CACHE_FILE)), true);
            return self::RETURN("OK", "CACHE FOUND", $CACHE_DATA);
        }

        return self::RETURN("ERR", "CACHE NOT FOUND");
    }

    public function DELETE($DATA_KEY)
    {
        $FILE_NAME = CACHE_LOCATION . "/" . $DATA_KEY;

        if (file_exists($FILE_NAME)) {
            unlink($FILE_NAME);
            return self::RETURN("OK", "CACHE DELETED");
        }

        return self::RETURN("ERR", "CACHE NOT FOUND");
    }

}
