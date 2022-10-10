<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TrackingRepository;

class TrackingController extends Controller
{
    private $trackingRepository;

    public function __construct(TrackingRepository $trackingRepository) {
        $this->trackingRepository = $trackingRepository;
    }

    public function obtenerInformacionDePedido($codigo_track) {

        $codigo_1 = explode("ENV", $codigo_track);
        $codigo_2 = explode("SV", $codigo_1[1]);
        $num_codigo = $codigo_2[0];

        $solicitud = $this->trackingRepository->obtenerInformacionDePedido($num_codigo);

        if ( $solicitud == null ) {
            return response()->json([
                'code' => '400',
                'status' => 'error',
                'data' => 'not found'
            ], 400);
        }

        return response()->json([
            'code' => '200',
            'status' => 'ok',
            'data' => $solicitud
        ], 200);
    }

}
