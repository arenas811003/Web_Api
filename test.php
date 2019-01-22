<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Data;
class test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'value';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Make a command';

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
     * @return mixed
     */
    public function handle()
    {   

        
        $curl = curl_init();
        $times = 0;
        $Startdata = 0;
    do{    
       
        curl_setopt_array($curl, array(
            CURLOPT_URL => "http://train.rd6/?start=2019-01-21T10:11:00&end=2019-01-21T10:11:30&from=$Startdata",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => "",
            CURLOPT_TIMEOUT => 30000,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "GET",
            CURLOPT_HTTPHEADER => array(
                // Set Here Your Requesred Headers
                'Content-Type: application/json',
            ),
        ));

        $response = curl_exec($curl);
        $response = json_decode($response,true);
        $Totalnum = $response['hits']['total'];//74423
        $Tenthousand = $response['hits']['hits'];//10000


        // print_r($totaldata);

        $Array=array();

        foreach($Tenthousand as $data){
            
            $data = [       
                '_index' => $data['_index'],
                '_type' => $data['_type'],
                '_id' => $data['_id'], 
                'score' => $data['_score'],
                'server_name'=> $data['_source']['server_name'],
                'remote' => $data['_source']['remote'],
                'route' => $data['_source']['route'],
                'route_path' => $data['_source']['route_path'],
                'request_method' => $data['_source']['request_method'],
                'user' => $data['_source']['user'],
                'http_args' => $data['_source']['http_args'],
                'log_id' => $data['_source']['log_id'],
                'status' => $data['_source']['status'],
                'size' => $data['_source']['size'],
                'referer' => $data['_source']['referer'],
                'user_agent' => $data['_source']['user_agent'],
                'timestamp' => $data['_source']['@timestamp'],
                         
            ];

            array_push($Array,$data);//
            $times++;
              
        }

        $Array = array_chunk($Array,3850);//

        foreach($Array as $array){
            Data::insert($array);
        }
        $Startdata = $times-1;
        print_r($Startdata."\n");

    }while($times<$Totalnum);
    
        // Data::insert($Array);
        // print_r($Array[0]);
        //  foreach($Array as $array){
        //     Data::insert($array);
        // }
        // $err = curl_error($curl);
        // curl_close($curl);

        // if ($err) {
        //     echo "cURL Error #:" . $err;
        // } else {
        //     print_r(json_decode($response));
        // }
// -------------------------------------------------------

        // $Value = [       
        //     'username' =>"Ten",
        //     'email' =>"Ten@gmail.com",
        //     'phone' =>"0912345678",          
        // ];
       
        // Mysql::insert($Value);
        // //$data = $this->arguments();
        // //print_r($data['send'][0]);
        // $value = $this->ask('Enter three text');
        // //print_r($value);
        // $RegExp = "/^\w{1,50},\w{1,50},\w{1,50}$/";
        // if (preg_match($RegExp, $value)) {
        //     $Array = preg_split("/[\s,]+/",$value);
        //     print_r($Array);
        // }else{
        //     print_r("輸入格式錯誤");
        // }        
        // $username = $Array[0];
        // $email = $Array[1];
        // $phone = $Array[2];
        // $Value = [       
        //     'username' =>$username,
        //     'email'=> $email,
        //     'phone'=> $phone
        // ];
        // //print_r($Value);
        // $TotalData=10;
        // $Array=[];
        
        // for($i=0;$i<$TotalData;$i++){
        //     $Array[$i]=$Value;            
        // }

        // $Array = array_chunk($Array,5);
        // foreach($Array as $array){
        //     Mysql::insert($array);
        // }
        
    }
}
