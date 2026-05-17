<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class RefleRefraLuzController extends Controller
{
    public function getImage($imagen)
    {
        try {

            $logo_path = "Logo-UV.jpg";
            $file_logo =  Storage::disk('public')->get($logo_path);
            $base64_logo = base64_encode($file_logo);
            $logo_mime = Storage::disk('public')->mimeType($logo_path);
            $logo_url = asset('storage/' . $logo_path);


            $loa_path = "LOA.jpeg";
            $file_loa =  Storage::disk('public')->get($loa_path);
            $base64_loa = base64_encode($file_loa);
            $loa_mime = Storage::disk('public')->mimeType($loa_path);
            $loa_url = asset('storage/' . $loa_path);

            if ($imagen == 0) {

                $path = "RefleRefraLuzCoord.png";
                $file = Storage::disk('public')->get($path);
                $base64 = base64_encode($file);
                $mime = Storage::disk('public')->mimeType($path);
                $url = asset('storage/' . $path);

                return response()->json([
                    "STATUS" => true,
                    "MESSAGE" => "Imagenes cargadas",
                    "IMAGEN" => $url,
                    "LOGO"=> $logo_url,
                    "LOA"=> $loa_url,
                ], 200);
            
            }else{

                $path = "RefleRefraLuzAngle.png";
                $file = Storage::disk('public')->get($path);
                $base64 = base64_encode($file);
                $mime = Storage::disk('public')->mimeType($path);
                $url = asset('storage/' . $path);
                return response()->json([
                    "STATUS" => true,
                    "MESSAGE" => "Imagenes cargadas",
                    "IMAGEN" => $url,
                    "LOGO"=> $logo_url,
                    "LOA"=> $loa_url,
                ], 200);

            }
        } catch (\Exception $th) {
            return response()->json(["STATUS" => false, "MESSAGE" => $th], 500);
        }

    }


    public function simulation(Request $request)
    {
        try {
            $idrObject = $request->idrObject;
            $idrSpace = $request->idrSpace;
            $mod = $request->mod;
            $angle = $request->angle;
            $coordX = $request->coordX;
            $coordY = $request->coordY;
            $coordZ = $request->coordZ;


            $script = base_path('app/Python/refle_refra.py');

            // Captura stdout y stderr
            $cmd = "python $script $mod $angle $coordX $coordY $coordZ $idrObject $idrSpace 2>&1";

            $output = shell_exec($cmd);
            $output = trim($output);

            return response()->json([
                'resultado' => $output
            ]);

        } catch (\Throwable $th) {
           return response()->json(["STATUS" => false, "MESSAGE" => $th->getMessage()], 500);
        }

    }


}



