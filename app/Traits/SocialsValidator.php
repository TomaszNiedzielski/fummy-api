<?php

namespace App\Traits;

trait SocialsValidator
{
    public function validate($links) {
        $links = json_decode($links);
        extract(get_object_vars($links));

        if($instagram->link && !$this->validateInstagram($instagram->link)) {
            return false;
        }

        if($tiktok->link && !$this->validateTiktok($tiktok->link)) {
            return false;
        }

        if($youtube->link && !$this->validateYoutube($youtube->link)) {
            return false;
        }

        return true;
    }

    protected function validateInstagram($link) {
        if(preg_match('/^(https:\/\/www\.instagram\.com)\/.+$/', $link)) {
            return true;
        }

        return false;
    }

    protected function validateTiktok($link) {
        if(preg_match('/^(https:\/\/www\.tiktok\.com)\/@.+$/', $link)) {
            return true;
        }

        return false;
    }

    protected function validateYoutube($link) {
        if(preg_match('/^https:\/\/www\.(youtube\.com)\/.+\/.+$/', $link)) {
            return true;
        }

        return false;
    }
}