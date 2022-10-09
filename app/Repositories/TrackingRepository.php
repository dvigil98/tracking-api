<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;

class TrackingRepository {

    public function obtenerInformacionDePedido($num_codigo) {
        try {
            $solicitud = DB::table('solicitudes  as s')
            ->join('estados as e', 's.estado_id', 'e.id')
            ->join('sucursales as su', 's.sucursal_id', 'su.id')
            ->join('empresas as em', 'su.empresa_id', 'em.id')
            ->join('direcciones_destinatarios as dd', 's.direccion_destinatario_id', 'dd.id')
            ->join('municipios as m', 'dd.municipio_id', 'm.id')
            ->join('departamentos as dt', 'm.departamento_id', 'dt.id')
            ->join('destinatarios as d', 'dd.destinatario_id', 'd.id')
            ->select(
                's.id as id',
                'em.razon_social as empresa',
                DB::raw('CONCAT(d.nombre," ",d.apellido) as cliente'),
                'd.dui as dui',
                'd.telefono as telefono',
                DB::raw('CONCAT(dd.direccion,", ",m.nombre,", ",dt.nombre) as direccion'),
                's.descripcion as paquete',
                'e.nombre as estado'
            )
            ->where('num_codigo', $num_codigo)->first();

            $historial = DB::table('historial_movimientos as hm')
            ->join('estados as e', 'hm.estado_id', 'e.id')
            ->join('solicitudes as s', 'hm.solicitud_id', 's.id')
            ->select(
                'hm.fecha as fecha', 
                'hm.descripcion', 
                'e.nombre as estado'
            )
            ->where('solicitud_id', $solicitud->id)->get();


            return ['solicitud' => $solicitud, 'historial' => $historial];
        } catch (\Throwable $th) {
            return null;
        }
    }

}
