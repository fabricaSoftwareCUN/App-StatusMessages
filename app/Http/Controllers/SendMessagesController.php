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

        $currentDate = Carbon::now()->format('d/m/Y');

        $messagesSent = MessageSent::where([
            ['fecha_generacion', $currentDate],
            ['celular', '!=', null],
            ['mensaje_enviado', "0"]
        ])->get();

        return $messagesSent;
    }
}
