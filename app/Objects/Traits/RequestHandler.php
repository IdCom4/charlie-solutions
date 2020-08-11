<?php

namespace App\Objects\Traits;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Objects\Enums\{TypesEnum};

// trait that i use to always have some common function at disposal, more like a little dev demo in this case

trait RequestHandler {

    // this function return the formatted and secured value of a request param

    public function getQueryParam(Request $request, int $varType, string $name, $valIfNot = null, int $minVal = null, int $maxVal = null) {
        $result = null;

        switch ($varType) {
            case TypesEnum::_INT:
                $result = intval($request->query($name, $valIfNot), 10);
                if (is_null($result))
                    break;
                $result = (!is_null($minVal) && $result < $minVal) ? $minVal : $result;
                $result = (!is_null($maxVal) && $result > $maxVal) ? $maxVal : $result;
                break;
            case TypesEnum::_FLOAT:
                $result = floatval($request->query($name, $valIfNot));
                if (is_null($result))
                    break;
                $result = (!is_null($minVal) && $result < $minVal) ? $minVal : $result;
                $result = (!is_null($maxVal) && $result > $maxVal) ? $maxVal : $result;
                break;
            case TypesEnum::_STRING:
                $result = strval($request->query($name, $valIfNot));
                if (is_null($result))
                    break;
                $result = (!is_null($minVal) && strlen($result) < $minVal) ? str_pad($result, $minVal, '0') : $result;
                $result = (!is_null($maxVal) && strlen($result) > $maxVal) ? substr($result, 0, $maxVal) : $result;
                break;
            case TypesEnum::_BOOL:
                $result = intval($request->query($name, $valIfNot), 10) % 2;
                break;
            default:
                $result = (!is_null($valIfNot)) ? $valIfNot : 0;
                break;
        }

        return ($result);
    }

}