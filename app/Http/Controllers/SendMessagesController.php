<?php

namespace App\Http\Controllers;

use App\Models\MessageSent;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SendMessagesController extends Controller
{
    public function __invoke()
    {
        date_default_timezone_set('America/Bogota');
        /*
        $matriculas = collect(DB::select(DB::raw("SELECT UNIQUE (B.NUM_IDENTIFICACION)
        ,('3') AS ID_CONDICION
        ,B.TEL_CECULAR
        ,TO_CHAR (M.FEC_MATRICULA,'DD/MM/YYYY') AS MATRICULA
        FROM SRC_ENC_MATRICULA M
        INNER JOIN Src_Alum_Programa p on P.ID_ALUM_PROGRAMA = M.ID_ALUM_PROGRAMA
        INNER JOIN BAS_TERCERO B ON B.ID_TERCERO = P.ID_TERCERO
        WHERE M.COD_PERIODO IN ('24V04','2024B')
        AND B.TEL_CECULAR IS NOT NULL
        AND TO_CHAR (M.FEC_MATRICULA,'DD/MM/YYYY') = TO_CHAR (SYSDATE ,'DD/MM/YYYY')")));


        $recibos = DB::select(DB::raw("SELECT UNIQUE  O.CLIENTE_SOLICITADO,
           TO_CHAR (O.FECHA_LIQUIDACION,'DD/MM/YYYY') as fecha_generacion,
           ('1') AS ID_CONDICION,
           B.TEL_CECULAR,
           b.tel_residencia,
           B.NUM_FAX,
               (
                O.VALOR_BRUTO
                + COALESCE(
                    (
                        SELECT SUM(S.VALOR)
                        FROM SOLICITUD_NOTA S
                        WHERE S.CLIENTE = O.CLIENTE_SOLICITADO
                            AND S.PERIODO = O.PERIODO
                            AND S.ESTADO = 'A'
                            AND S.ORDEN = O.ORDEN
                            AND S.CONCEPTO_NOTA IN (701, 803)
                    ),
                    0
                )
                -   COALESCE(
                    (
                        SELECT SUM(S.VALOR)
                        FROM SOLICITUD_NOTA S
                        WHERE S.CLIENTE = O.CLIENTE_SOLICITADO
                            AND S.PERIODO = O.PERIODO
                            AND S.ESTADO = 'A'
                            AND S.ORDEN = O.ORDEN
                            AND S.CONCEPTO_NOTA NOT IN (701, 803)
                            AND S.VALOR<=0
                    ),
                    0
                )

                + COALESCE(
                    (
                        (
                            O.VALOR_BRUTO
                            - COALESCE(
                                (
                                    SELECT SUM(S.VALOR)
                                    FROM SOLICITUD_NOTA S
                                    WHERE S.CLIENTE = O.CLIENTE_SOLICITADO
                                        AND S.PERIODO = O.PERIODO
                                        AND S.ESTADO = 'A'
                                        AND S.ORDEN = O.ORDEN
                                        AND S.CONCEPTO_NOTA NOT IN (701, 803)
                                ),
                                0
                            )
                        ) * (
                            SELECT V.PORCENTAJE / 100
                            FROM VENCIMIENTO_PERIODO V
                            WHERE TO_CHAR(V.FECHA_VENCIMIENTO, 'YYYYMMDD') = TO_CHAR(O.FECHA_VENCIMIENTO, 'YYYYMMDD')
                                AND V.GRUPO = O.GRUPO
                                AND TO_DATE(V.FECHA_VENCIMIENTO) >= TRUNC(SYSDATE)
                                AND V.PORCENTAJE < 0
                        )
                    ),
                    0
                )
            ) valorFactura
        FROM ORDEN O
        LEFT JOIN SOLICITUD_NOTA N ON N.ORDEN = O.ORDEN
            AND N.CLIENTE = O.CLIENTE_SOLICITADO
            AND N.ESTADO = 'A'
        LEFT JOIN RECIBO_CONSIGNACION C ON C.CLIENTE = O.CLIENTE_SOLICITADO
            AND C.PERIODO = O.PERIODO
            AND C.ESTADO = 'I'
        LEFT JOIN VENCIMIENTO_PERIODO V ON V.PERIODO = O.PERIODO
            AND V.GRUPO = O.GRUPO
            AND TO_DATE(V.FECHA_VENCIMIENTO) >= TRUNC(SYSDATE)
        LEFT JOIN CREDITO CR ON O.ORDEN = CR.ORDEN
        INNER JOIN BAS_TERCERO B ON B.NUM_IDENTIFICACION = CAST ( O.CLIENTE_SOLICITADO AS VARCHAR2(30))
        WHERE O.estado = 'V'
        AND TO_CHAR (O.FECHA_LIQUIDACION,'DD/MM/YYYY') = TO_CHAR (SYSDATE ,'DD/MM/YYYY')
        --  AND O.CLIENTE_SOLICITADO = '1005417551'
        AND O.PERIODO = '2024B'"));


   $pagos = DB::select(DB::raw("SELECT UNIQUE (B.NUM_IDENTIFICACION),
    TO_CHAR (R.FECHA_PAGO,'DD/MM/YYYY') as fecha_generacion
    ,('2') AS ID_CONDICION
    ,B.TEL_CECULAR
    ,B.TEL_RESIDENCIA
    ,B.NUM_FAX
    ,R.VALOR
    FROM RECIBO_CAJA R
    INNER JOIN BAS_TERCERO B ON B.NUM_IDENTIFICACION = CAST (R.CLIENTE AS VARCHAR2 (20))
    WHERE TO_CHAR (R.FECHA_PAGO,'DD/MM/YYYY') = TO_CHAR (SYSDATE ,'DD/MM/YYYY')
    and b.tel_cecular IS NOT NULL
    AND R.VALOR > 0
    ---AND R.PERIODO = '2024B'"));

        return $pagos;
        return $matriculas->whereNotIn('id_condicion', ['2']);

        return 'ok';

*/
        $currentDate = Carbon::now()->format('d/m/Y');

        //return $currentDate;
        //$messagesSent = MessageSent::where('fecha_generacion', $currentDate)->get()[0];

        $messagesSent = MessageSent::where([
            ['fecha_generacion', $currentDate],
            ['celular', '!=', null],
            ['mensaje_enviado', "0"]
        ])->get();


        //return MessageSent::where('celular', '3152139217')->first();

        return $messagesSent;
    }
}
