<?php

namespace App\Console\Commands;

use App\Models\MessageSent;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Mockery\Exception;

class SendMessagesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     * @throws GuzzleException
     */
    public function handle()
    {
        set_time_limit(0);

        $currentDate = Carbon::now()->format('d/m/Y');

        $messagesSent = MessageSent::where([
            ['fecha_generacion', $currentDate],
            ['celular', '!=', null],
            ['mensaje_enviado', "0"]
        ])->get();

        /*
        $messagesSent1 = MessageSent::where([
            ['fecha_generacion', $currentDate],
            ['celular', '!=', null],
            ['mensaje_enviado', "1"]
        ])->get();

        Storage::append('messages.txt', 'MENSAJES ENVIADOS ' . $messagesSent1->count());
        */
        //Storage::append('messages.txt', 'MENSAJES NO ENVIADOS ' . $messagesSent->count());


        //Storage::append('messages.txt', 'MENSAJES NO ENVIADOS ' . $messagesSent->count());

        foreach ($messagesSent as $messageSent) {
            $messageSent->MENSAJE_ENVIADO = "1";
            $messageSent->save();

            try {
                $valor = "0";
                //Storage::append('messages.txt', 'El mensaje es: ' . print_r($messageSent, true));

            } catch (\Exception $e) {
                //Storage::append('messages.txt', $e);
            }

            $client = new Client();
                //Storage::append('messages.txt', 'Entro');

                if (isset($messageSent->valor)) {
                    $valor = $messageSent->valor;
                }

                $data = [
                    'condicion' => strval($messageSent->id_condicion),
                    'numero' => strval($messageSent->celular),
                    'valor' => strval($valor)
                ];

                /*
                try {
                    Storage::append('messages.txt', 'El valor es: ' . $data['valor']);
                } catch (\Exception $e) {
                    Storage::append('messages.txt', 'el error de valor es: ' . $e);
                }
                */

                //Storage::append('messages.txt', 'la data enviada es: ' . print_r($data, true));

                try {
                    $response = $client->post('https://apimessages.cunapp.pro/api/Mensajeria/SendMessage', [
                        'json' => $data, // Usa 'json' en lugar de 'data'
                        'headers' => [
                            'Content-Type' => 'application/json', // Establece el encabezado Content-Type
                            'Accept' => 'application/json' // Asegúrate de que el servidor sepa que aceptas JSON
                        ]
                    ]);

                    //Storage::append('messages.txt', 'El response es el siguiente '. print_r($response, true));

                } catch (GuzzleException $e) {
                    //Storage::append('messages.txt', 'Error: ' . $e->getMessage());
                }

                //Storage::append('messages.txt', 'El response es el siguiente '. print_r($response));
                //Storage::append('messages.txt', 'El id del mensaje es el siguiente: ' . $messageSent->id);
                //$messageSent->mensaje_enviado = "1";
                //Storage::append('messages.txt', 'el celular es: ' . $messageSent->celular);
                //$result = $messageSent->update(['mensaje_enviado' => '1']);
                //Storage::append('messages.txt', 'el resultado del mensaje enviado es igual aa : '. print_r($result));
            }

        //Storage::append('messages.txt', 'recorrio el ciclo');
        //return 'api ejecutada';

        return true;
    }
}
