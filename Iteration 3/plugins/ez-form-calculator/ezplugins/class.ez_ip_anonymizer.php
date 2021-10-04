<?php

/**
    Modification of https://github.com/geertw/php-ip-anonymizer
**/

abstract class EZ_IP_Anonymizer {
    /**
     * @var string IPv4 netmask used to anonymize IPv4 address.
     */
    public static $ipv4NetMask      = "255.255.255.0";
    public static $ipv4NetMask_full = "0.0.0.0";

    /**
     * @var string IPv6 netmask used to anonymize IPv6 address.
     */
    public static $ipv6NetMask      = "ffff:ffff:ffff:ffff:0000:0000:0000:0000";
    public static $ipv6NetMask_full = "0000:0000:0000:0000:0000:0000:0000:0000";

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param $address string IP address that must be anonymized
     * @param $type type of anonymization (1 = partly, 2 = full)
     * @return string The anonymized IP address. Returns an empty string when the IP address is invalid.
     */
    public static function anonymizeIp($address, $type = 1) {
        return self::anonymize($address, $type);
    }

    /**
     * Anonymize an IPv4 or IPv6 address.
     *
     * @param $address string IP address that must be anonymized
     * @return string The anonymized IP address. Returns an empty string when the IP address is invalid.
     */
    public static function anonymize($address, $type = 1) {
        $packedAddress = inet_pton($address);

        if (strlen($packedAddress) == 4) {
            return self::anonymizeIPv4($address, $type);
        } elseif (strlen($packedAddress) == 16) {
            return self::anonymizeIPv6($address, $type);
        } else {
            return "";
        }
    }

    /**
     * Anonymize an IPv4 address
     * @param $address string IPv4 address
     * @return string Anonymized address
     */
    public static function anonymizeIPv4($address, $type = 1) {
        $mask = $type == 2 ? self::$ipv4NetMask_full : self::$ipv4NetMask;

        return inet_ntop(inet_pton($address) & inet_pton($mask));
    }

    /**
     * Anonymize an IPv6 address
     * @param $address string IPv6 address
     * @return string Anonymized address
     */
    public static function anonymizeIPv6($address, $type = 1) {
        $mask = $type == 2 ? self::$ipv6NetMask_full : self::$ipv6NetMask;

        return inet_ntop(inet_pton($address) & inet_pton(self::$ipv6NetMask));
    }
}