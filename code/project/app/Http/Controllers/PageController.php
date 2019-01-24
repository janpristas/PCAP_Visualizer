<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Support\Collection;

class PageController extends Controller
{
    /**
      * Basic info about stations
      */
    public function home() {
		
        $stations = null;
        $unique_pairs = null;
        $unique_stations = null;

        if (!\App\Station::all()->isEmpty()) {
            // Stations info
            $stations = \App\Station::all();
            // Unique communication pairs
            $unique_pairs = \App\Station::select('src_ip', 'dst_ip')->groupBy('src_ip', 'dst_ip')->get();
            // Unique stations            
            $unique_stations = \App\Station::select('src_ip')->groupBy('src_ip')->get();
        }

	    return view('welcome', ['stations' => $stations, 'unique_pairs' => $unique_pairs, 'unique_stations' => $unique_stations]);
    }

    /**
      * Station info
      */
    public function stationInfo($ip_address) {
        $stations = \App\Station::select('src_ip', 'dst_ip', 'src_mac', 'dst_mac', 'src_port', 'dst_port')->where('src_ip', $ip_address)->groupBy('src_ip', 'dst_ip', 'src_mac', 'dst_mac', 'src_port', 'dst_port')->get();

        return response()->json($stations);
    }

    /**
      * Width of edge
      * Depends on amount of communication between two stations
      */
    public function edgeWidth($src_ip, $dst_ip) {
        $edges = \App\Station::where([['src_ip', $src_ip],['dst_ip', $dst_ip]])->orWhere([['src_ip', $dst_ip],['dst_ip', $src_ip]])->get();
        $width = $edges->count();

        return response()->json($width);
    }

    /**
      * Edge info
      */
    public function edgeInfo($edgeInfo) {
        $temp = explode("-", $edgeInfo);

        $stations = \App\Station::where([['src_ip', $temp[0]], ['dst_ip', $temp[1]]])->orWhere([['src_ip', $temp[1]], ['dst_ip', $temp[0]]])->get();

        return response()->json($stations);
    }
}
