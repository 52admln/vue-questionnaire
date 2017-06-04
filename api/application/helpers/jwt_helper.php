<?php

class jwt_helper extends CI_Controller
{
    const CONSUMER_KEY = 'dididasan';
    const CONSUMER_SECRET = 'dididasan';
    const CONSUMER_TTL = 86400;

    // 生成 token
    public static function create($userid)
    {
        $CI =& get_instance();
        $CI->load->library('JWT');
        $token = $CI->jwt->encode(array(
            'consumerKey' => self::CONSUMER_KEY,
            'userId' => $userid,
            'issuedAt' => date(DATE_ISO8601, strtotime("now")),
            'ttl' => self::CONSUMER_TTL
        ), self::CONSUMER_SECRET);
        return $token;
    }

    // 验证 token 有效性
    public static function validate($token)
    {
        $CI =& get_instance();
        $CI->load->library('JWT');
        try {
            $CI->jwt->decode($token, self::CONSUMER_SECRET);
            return true;
        } catch (Exception $e) {
            return false;
        }

    }

    // 解码 token
    public static function decode($token)
    {
        $CI =& get_instance();
        $CI->load->library('JWT');
        try {
            $decodeToken = $CI->jwt->decode($token, self::CONSUMER_SECRET);
            return $decodeToken;
        } catch (Exception $e) {
            return false;
        }
    }
}
