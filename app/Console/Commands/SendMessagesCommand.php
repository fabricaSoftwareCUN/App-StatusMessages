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

        date_default_timezone_set('America/Bogota');

        $client = new Client();
        $data = [
            'id' => 0,
            'username' => getenv('API_MESSAGES_USER'),
            'password' => getenv('API_MESSAGES_PASSWORD'),
        ];

        try {
            $response = $client->post('https://apimessages.cunapp.pro/api/Auth', [
                'json' => $data,
            ]);

            $token = json_decode($response->getBody())->token;
            $currentDate = Carbon::now()->format('d/m/Y');

            $messagesSent = MessageSent::where([
                ['fecha_generacion', $currentDate],
                ['celular', '!=', null],
                ['mensaje_enviado', "0"]
            ])->get();


            foreach ($messagesSent as $messageSent) {
                $messageSent->MENSAJE_ENVIADO = "1";
                $messageSent->save();

                $client = new Client();

                $valor = "0";

                if (isset($messageSent->valor)) {
                    $valor = $messageSent->valor;
                }

                $data = [
                    'condicion' => strval($messageSent->id_condicion),
                    'numero' => strval($messageSent->celular),
                    'valor' => '$ '.strval($valor)
                ];

                //Storage::append('messages.txt', 'La data enviada es la siguiente '. print_r($data, true));

                try {
                    $response = $client->post('https://apimessages.cunapp.pro/api/Mensajeria/SendMessage', [
                        'json' => $data, // Usa 'json' en lugar de 'data'
                        'headers' => [
                            'Content-Type' => 'application/json', // Establece el encabezado Content-Type
                            'Accept' => 'application/json', // Asegúrate de que el servidor sepa que aceptas JSON
                            'Authorization' => 'Bearer ' . $token
                        ]
                    ]);

                    //Storage::append('messages.txt', 'El response es el siguiente '. print_r($response, true));

                } catch (GuzzleException $e) {
                    Storage::append('messages.txt', 'Error: ' . $e->getMessage());
                }
            }

            //Storage::append('messages.txt', 'El response es el siguiente '. print_r($response, true));

        } catch (GuzzleException $e) {
            Storage::append('messages.txt', 'Error: ' . $e->getMessage());
        }
    }
}
