<?php

namespace sanoha\Jobs;

use \Carbon\Carbon;
use sanoha\Jobs\Job;
use League\Csv\Reader;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Bus\SelfHandling;
use Illuminate\Contracts\Queue\ShouldQueue;

class ImportSiigoFile extends Job implements SelfHandling, ShouldQueue
{
    use InteractsWithQueue, SerializesModels;

    /**
     * El archivo a ser procesado
     */
    private $file;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        $this->file = $file;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $csv = Reader::createFromPath($this->file);
        $csv->setOffset(1); //because we don't want to insert the header
        $count = 0;

        \DB::beginTransaction();

        $nbInsert = $csv->each(function ($row) use (&$count) {
            // los datos del archivo a ser insertados en la base de datos
            $data = [

                'NIT'               =>  $row[0],
                'TERCERO'           =>  empty(trim($row[1])) ? null : $row[1],
                'NOMBRE_TERCERO'    =>  $row[2],
                'DIRECCION_TERCERO' =>  $row[3],
                'CUENTA'            =>  trim($row[4]),
                'CTA'               =>  trim($row[5]),
                'DESCRIPCION'       =>  $row[6],
                'COMPROBANTE'       =>  $row[7],
                'FECHA'             =>  empty(trim($row[8])) ? null : $row[8],
                'DETALLE'           =>  $row[9],
                'DOCUMENTO_CRUCE'   =>  $row[10],
                'DEBITOS'           =>  empty(trim($row[11])) ? 0 : $row[11],
                'CREDITOS'          =>  empty(trim($row[12])) ? 0 : $row[12],
                'SALDO'             =>  empty(trim($row[13])) ? 0 : $row[13],
                'SALDO_TOTAL'       =>  empty(trim($row[14])) ? 0 : $row[14],
                'created_at'        =>  date('Y-m-d H:i:s'),
                'updated_at'        =>  date('Y-m-d H:i:s'),

                ];

            if (\DB::table('siigo')->insert($data)){
                
                $count++;
                return true;

            }
            
            $count = 0; 
            return false;

        });

        $count > 0 ? \DB::commit() : \DB::rollback();
    }
}
