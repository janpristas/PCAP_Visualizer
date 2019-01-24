@extends('layout')
@section('title', 'Welcome page')
@section('draw_script')
	<script type="text/javascript">
		var EDGE_LENGTH = 150;
		var nodes = null;
	    var edges = null;
	    var network = null;

		<?php if (!empty($stations)) { ?>
			// Called when the Visualization API is loaded.
			function draw() {
			    // Create a data table with nodes.
			    var nodes = new vis.DataSet([
			    	@foreach($unique_stations as $station)
			    		@if ($loop->last)
			    			{id: "<?php echo $station->src_ip; ?>", label: "<?php echo $station->src_ip; ?>", image: "{{asset('img/computer_logo.png')}}", shape: "image"}
			    			@break
			    		@endif
			    		{id: "<?php echo $station->src_ip; ?>", label: "<?php echo $station->src_ip; ?>", image: "{{asset('img/computer_logo.png')}}", shape: "image"},
			    	@endforeach
			    ]);
			    // Create a data table with links.
			    var edges = new vis.DataSet({});
			    var $width = null;

		    	@foreach($unique_pairs as $station)
		    		$.ajax({
			        	url: "/api/edge-width/" + "<?php echo $station->src_ip; ?>" + "/" + "<?php echo $station->dst_ip; ?>",
			        	async: false, 
			        	success: function(result){
			        		$width = result;
				    	}
				    });
				    $width = Math.round($width/250);

				    // Set maximum width value to 15
				    if ($width < 1) {
				    	$width = 1;
				    } else if ($width > 15) {
				    	$width = 15;
				    }
		    		@if ($loop->last)
		    				edges.add({id: "<?php echo $station->src_ip; ?>-<?php echo $station->dst_ip; ?>", from: "<?php echo $station->src_ip; ?>", to: "<?php echo $station->dst_ip; ?>", length: EDGE_LENGTH, width: $width});
		    			@break
		    		@endif
		        	edges.add({id: "<?php echo $station->src_ip; ?>-<?php echo $station->dst_ip; ?>", from: "<?php echo $station->src_ip; ?>", to: "<?php echo $station->dst_ip; ?>", length: EDGE_LENGTH, width: $width},);
		        @endforeach

			    // Create a network
			    var container = document.getElementById('topology');
			    // Provide the data in the vis format
			    var data = {
			        nodes: nodes,
			        edges: edges
			    };
			    var options = {
			    	physics: {
			    		enabled: false
			    	},
			    	edges: {
			    		color: {
			    			color: '#5b98ef',
							highlight: 'red',
							opacity: 1.0
			    		},
			    		smooth: {
			    			enabled: false
			    		},
			    		labelHighlightBold: true

			    	}
			    };
			    // Initialize network!
			    var network = new vis.Network(container, data, options);

			    /**
			      * Get info about node/edge after click on topology
			      */
			    network.on("click", function (params) {
			        params.event = "[original event]";
			        var $edgeInfo = this.getEdgeAt(params.pointer.DOM);
			        var $nodeIp = this.getNodeAt(params.pointer.DOM);
			        var $mac = "";

			        if (($nodeIp == undefined) && ($edgeInfo != undefined)) {
			        	$("#info_text").hide();
			        	edgeInfo($edgeInfo);
			        } else if (($nodeIp == undefined) && ($edgeInfo == undefined)) {
			        	$("#info_text").hide();
			        	$("#popup_bg").hide();
						$("#edge_info_wrapper").hide();
			        } else {
			        	stationInfo($nodeIp);
			    	}
			    });
			}

			/**
			  * Display station info
			  */
			function stationInfo($nodeIp) {
				$("#info_text").show();
				$('#info_text').html('<h2 class="station_info">Station info</h2>');
		        $('#info_text').append('<p class="info_text_par">Source IP address:</p><i>' + $nodeIp + '</i>');

		        $.ajax({
		        	url: "/api/station-info/" + $nodeIp, 
		        	success: function(result){
		        		var $iterator = 1;
		        		$('#info_text').append('<p class="info_text_par">Source MAC address:</p><i>' + result[0].src_mac + '</i>');
		        		$('#info_text').append('<p class="info_text_par">Port:</p><i>' + result[0].src_port + '</i>');
		        		$('#info_text').append('<br />');
		        		$('#info_text').append('<h2 class="station_info">Communication info</h2>');
		        		result.forEach(function(element) {
		        			$('#info_text').append('<p class="info_text_par">Station' + $iterator + ':</p>');
		        			$('#info_text').append('<p class="text_par">IP address: <i>' + element.dst_ip + '</i></p>');
		        			$('#info_text').append('<p class="text_par">MAC address: <i>' + element.dst_mac + '</i></p>');
		        			$('#info_text').append('<p class="text_par">Port: <i>' + element.dst_port + '</i></p>');
		        			$('#info_text').append('<br />');
		        			$iterator += 1;
		        		});		        	
			    	}
			    });
			}

			/**
			  * Display edge info table
			  */
			function edgeInfo($edgeInfo) {
				$("#popup_bg").show();
				$("#edge_info_wrapper").show();

				$.ajax({
		        	url: "/api/edge-info/" + $edgeInfo,
		        	success: function(result){
			        	var $time = result[result.length - 1].time_relative - result[0].time_relative;
			        	var $bits = 0;

			        	result.forEach(function(element) {

			        		if ((element.bit_cnt == "-") && (element.byte_cnt != "-")) {
			        			$bits += (parseInt(element.byte_cnt) * 8);
			        		} else if ((element.bit_cnt != "-") && (element.byte_cnt == "-")) {
			        			$bits += parseInt(element.bit_cnt);
			        		}

			        		$('#communication_table').append(
			        				'<tr>'
			        					+ '<td>' 
			        						+ element.src_ip
			        					+ '</td><td>'
			        						+ element.dst_ip
			        					+ '</td><td>'
			        						+ element.time_relative
			        					+ '</td><td>'
			        						+ element.trans_id
			        					+ '</td><td>'
			        						+ element.unit_id
			        					+ '</td><td>'
			        						+ element.func_code
			        					+ '</td><td>'
			        						+ element.bit_cnt
			        					+ '</td><td>'
			        						+ element.byte_cnt
			        					+ '</td><td>'
			        						+ element.ip_len
			        					+ '</td>'
			        				+ '</tr>'
			        			);
			        	});

			        	$('#edge_header').html('<h1><i>' + result[0].src_ip + ' (port: ' + result[0].src_port + ')</i>......<i>' + result[0].dst_ip + ' (port: ' + result[0].dst_port + ')</i></h1>');
			        	$('#count').append('<i>' + result.length + '</i>');
			        	$('#time').append('<i>' + $time + ' sec</i>');
			        	$('#bits').append('<i>' + $bits + ' bits</i>');
			    	}
			    });
			}
		<?php } ?>
	</script>
@endsection

@section('content')
	<div id="page_wrapper">
		<div id="popup_bg"></div>
		<div id="edge_info_wrapper">
			<div class="edge_info">
				<div class="edge_header_wrapper">
					<div id="edge_header">
					</div>
					<div class="close_button">
						<i id="X-close" class="far fa-times-circle"></i>
					</div>
				</div>
				<div class="edge_header_info second">
					<p id="count">Count of messages: </p>
					<p id="time">Time of communication: </p>
					<p id="bits">Bits transfered: </p>
				</div>
			</div>
			<div class="edge_communication_info">
				<table id="communication_table">
					<tr class="first_row">
						<td>Source IP</td>
						<td>Destination IP</td>
						<td>Time</td>
						<td>Transfer ID</td>
						<td>Unit ID</td>
						<td>Function Code</td>
						<td>Bit Count</td>
						<td>Byte Count</td>
						<td>IP Length</td>
					</tr>
				</table>
			</div>
		</div>
		<div id="page_header">
			<div id="logo_wrapper">
				<img src="{{ asset('img/fit_logo.png') }}">
			</div>
		</div>
		<div id="content">
			<div id="content_left">
				<div class="left_content_wrapper">
					<div class="wrap left">
				        <div class="container">
				            <div class="row">
				                <form class="form-horizontal" action="/" method="post" name="upload_excel" enctype="multipart/form-data">

				                	{{ csrf_field() }}

				                    <fieldset>
				                    	<!-- Form Name -->
                        				<legend>Nahrať .csv súbor</legend>
				                        <!-- File Button -->
				                        <div class="form-group">
				                            <label class="col-md-4 control-label" for="filebutton">Vybrať súbor</label>
				                            <div class="col-md-4">
				                                <input type="file" name="file" id="file" class="input-large">
				                            </div>
				                        </div>
				                        <!-- Button -->
				                        <div class="form-group">
				                            <label class="col-md-4 control-label" for="singlebutton">Nahrať dáta</label>
				                            <div class="col-md-4">
				                                <button type="submit" id="submit" name="Import" class="btn btn-primary button-loading" data-loading-text="Loading...">Nahrať</button>
				                            </div>
				                        </div>
				                    </fieldset>
				                </form>
				 
				            </div>
				        </div>
				    </div>
				    <div class="info left bottom">
				    	<div class="container">
				    		<h1>Info</h1>
							<div id="info_text">
							</div>
				    	</div>
				    </div>
				</div>
			</div>
			<div id="content_right">
				<div class="topology_wrapper">
					<div id="topology"></div>
				</div>
			</div>
		</div>
	</div>
@endsection