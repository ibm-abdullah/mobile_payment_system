<?php

/**
 *  This class process telephone numbers to identify
 * the vendor uniques code
 */
class ProcessUserInput {

    public function identifyVendor($phone_number) {
        $vendorCode = substr($phone_number, 0, 3);
        $vendor = "";
        switch ($vendorCode) {
            case '054':
            case '024':
            case '055':
                $vendor = "MTN";
                break;
            case '020':
                $vendor = "Vodafone";
                break;
            case '027':
            case '057':
                $vendor = "Tigo";
                break;
            case '026':
            case '056':
                $vendor = "Airtel";
                break;
            default:
                $vendor = NULL;
                break;
        }

        return $vendor;
    }
    public function identifyVendor2($phone_number) {
        $vendorCode = substr($phone_number, 0, 5);
        $vendor = "";
        switch ($vendorCode) {
            case '23354':
            case '23324':
            case '23355':
                $vendor = "MTN";
                break;
            case '23320':
            case '23350':
                $vendor = "Vodafone";
                break;
            case '23327':
            case '23357':
                $vendor = "Tigo";
                break;
            case '23326':
            case '23356':
                $vendor = "Airtel";
                break;
            default:
                $vendor = NULL;
                break;
        }

        return $vendor;
    }

}
