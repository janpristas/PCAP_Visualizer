<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store info from input .csv file to db.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request) {

        \App\Station::truncate();

        if(isset($_POST["Import"])){
            $filename=$_FILES["file"]["tmp_name"];      
            if($_FILES["file"]["size"] > 0) {
                $file = fopen($filename, "r");
                while (($getData = fgetcsv($file, 10000, ";")) !== FALSE) {

                    $station = new \App\Station;
                    $station->id = $getData[0];
                    $station->src_ip = $getData[1];
                    $station->dst_ip = $getData[2];
                    $station->src_mac = $getData[3];
                    $station->dst_mac = $getData[4];
                    $station->src_port = $getData[5];
                    $station->dst_port = $getData[6];
                    $station->time_relative = $getData[7];
                    $station->trans_id = $getData[8];
                    $station->unit_id = $getData[9];

                    /* Save info about commant to db */
                    if ($getData[10] == "1") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read Coils)";
                    } elseif ($getData[10] == "2") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read Discrete Inputs)";
                    } elseif ($getData[10] == "3") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read Holding Registers)";
                    } elseif ($getData[10] == "4") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read Input Registers)";
                    } elseif ($getData[10] == "5") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Write Single Coil)";
                    } elseif ($getData[10] == "6") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Write Single Register)";
                    } elseif ($getData[10] == "7") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read Exception Status)";
                    } elseif ($getData[10] == "8") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Diagnostics)";
                    } elseif ($getData[10] == "11") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Get Comm Event Counter)";
                    } elseif ($getData[10] == "12") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Get Comm Event Log)";
                    } elseif ($getData[10] == "15") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Write Multiple Coils)";
                    } elseif ($getData[10] == "16") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Write Multiple registers)";
                    } elseif ($getData[10] == "17") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Report Server ID)";
                    } elseif ($getData[10] == "20") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read File Record)";
                    } elseif ($getData[10] == "21") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Write File Record)";
                    } elseif ($getData[10] == "22") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Mask Write Register)";
                    } elseif ($getData[10] == "23") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read/Write Multiple registers)";
                    } elseif ($getData[10] == "24") {
                            $station->func_code = $getData[10];
                            $station->func_code .= " (Read FIFO Queue)";
                    } else {
                        $station->func_code = $getData[10];
                    }
                    if ($getData[11] == "") {
                        $station->bit_cnt = "-";
                    } else {
                        $station->bit_cnt = $getData[11];
                    }
                    if ($getData[12] == "") {
                        $station->byte_cnt = "-";
                    } else {
                        $station->byte_cnt = $getData[12];
                    }
                    $station->ip_len = $getData[13];
                    $station->time = $getData[14];
                
                    /* Message shown after .csv file import */
                    if(!$station->save()) {
                        echo "<script type=\"text/javascript\">
                                alert(\"Invalid File:Please Upload CSV File.\");
                                window.location = \"index.php\"
                              </script>";       
                    } else {
                          echo "<script type=\"text/javascript\">
                            alert(\"CSV File has been successfully Imported.\");
                            window.location = \"index.php\"
                        </script>";
                    }
                }
                
                fclose($file); 
             }
        }

        return redirect('/');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
