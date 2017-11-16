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
                $vendor = "Airtel";
            default:
                $vendor = NULL;
                break;
        }

        return $vendor;
    }

}
