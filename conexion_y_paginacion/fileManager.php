<?php

class FileMode extends Enum {

    const FILE_MODE_READ_FILE_FROM_BEGINNING = "r";
    const FILE_MODE_READ_FILE_FROM_BEGINNING_AND_ALLOW_WRITING = "r+";
    const FILE_MODE_WRITE_A_FILE_AND_CUT_ITS_CONTENT = "w";
    const FILE_MODE_WRITE_A_FILE_FROM_BEGINNING_CUTS_ITS_CONTENT_AND_ALLOWS_READING = "w+";
    const FILE_MODE_IS_ATTACHED_TO_END_OF_FILE = "a";
    const FILE_MODE_IT_ATTACHES_TO_END_OF_FILE_AND_ALLOWS_READING = "a+";

}

class FileManager {

    static function createFile($filename, $content, $mode = FileMode::FILE_MODE_WRITE_A_FILE_AND_CUT_ITS_CONTENT) {
        try {
            $fh = fopen($filename, $mode);
            fwrite($fh, $content . PHP_EOL);
            fclose($fh);
            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    static function modifyFile($filename, $content, $mode = FileMode::FILE_MODE_WRITE_A_FILE_AND_CUT_ITS_CONTENT) {
        try {
            if (file_exists($filename) == true) {
                $fh = fopen($filename, $mode);
                fwrite($fh, $content . PHP_EOL);
                fclose($fh);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

    static function readFile($filename) {
        $res = null;
        if (file_exists($filename) == true) {
            $fh = fopen($filename, FileMode::FILE_MODE_READ_FILE_FROM_BEGINNING);
            $res = fread($fh, filesize($filename));
            fclose($fh);
        }
        return $res;
    }

    static function deleteFile($filename) {
        try {
            if (file_exists($filename) == true) {
                $filename = (substr($filename, 0, 2) == "./") ? substr($filename, 2, strlen($filename)) : $filename;
                unlink($filename);
                return true;
            } else {
                return false;
            }
        } catch (Exception $e) {
            return false;
        }
    }

}
