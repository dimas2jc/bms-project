<?php

namespace App\Libraries;

use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class HelperFunction{

    static function getSpaceDownloadUrl($space_url){
        $format_office = ['ppt','pot','pps','pptx','pptm','potx','potm','ppam','ppsx','ppsm','sldx','sldm','doc','dot','wbk','docx','docm','dotx','dotm','docb','xls','xlt','xlm','xlsx','xlsm','xltx','xltm','xlsb','xla','xlam','xll','xlw'];

        $ext = preg_replace('/^.*\.([^.]+)$/D', '$1', $space_url);

        if(in_array($ext, $format_office)){
            $download_url = 'https://view.officeapps.live.com/op/view.aspx?src='.Storage::disk('spaces')->url($space_url);
        }else{
            $download_url = Storage::disk('spaces')->url($space_url);
        }

        return $download_url;
    }

    static function convertToDate($date){
        return date_format(date_create($date), "d M Y");
    }

    static function convertToWIB($datetime){
        return date_format(date_create($datetime), "d M Y H:i").' WIB';
    }

    static function convertToRp($integer){
        return number_format($integer);
    }

    static function convertToBulan($integer){
        switch($integer){
            case  1: return 'Januari'; break;
            case  2: return 'Februari'; break;
            case  3: return 'Maret'; break;
            case  4: return 'April'; break;
            case  5: return 'Mei'; break;
            case  6: return 'Juni'; break;
            case  7: return 'Juli'; break;
            case  8: return 'Agustus'; break;
            case  9: return 'September'; break;
            case  10: return 'Oktober'; break;
            case  11: return 'Nopember'; break;
            case  12: return 'Desember'; break;
            default: ''; break;
        }
    }

	static function uploadImage($file, $tanggal) {
        $directory = 'uploads/img/'.$tanggal;
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $ext = $file->getClientOriginalExtension();
        $filename = rand(100000,1001238912).".".$ext;
        $file->move($directory, $filename);

        $path = $directory.'/'.$filename;
        
        return (object) array(
            'path' => $path,
            'ext' => $ext
        );
        return ;
    }

    static function uploadFile($file, $tanggal) {
        $directory = 'uploads/file/'.$tanggal;
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $ext = $file->getClientOriginalExtension();
        $filename = rand(100000,1001238912).".".$ext;
        $file->move($directory, $filename);

        $path = $directory.'/'.$filename;
        
        return (object) array(
            'path' => $path,
            'ext' => $ext
        );
        return ;
    }

    static function uploadBase64File($base64_img, $tanggal) {
        $image_parts = explode(";base64,", $base64_img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $ext = $image_type_aux[1];
        $file = base64_decode($image_parts[1]);

        $directory = 'uploads/img/'.$tanggal;
        if (!file_exists($directory)) {
            mkdir($directory, 0755, true);
        }

        $path = $directory.'/'.rand(100000,1001238912).".".$ext;
        file_put_contents($path, $file);

        return (object) array(
            'path' => $path,
            'ext' => $ext
        );
    }
    
}