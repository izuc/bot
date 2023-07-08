<!DOCTYPE html>
<html lang="en">
	<head>
		<title>Alice</title>
		<meta content="text/html;charset=utf-8" http-equiv="Content-Type">
		<meta content="utf-8" http-equiv="encoding">
		<style>
			body {
				overflow: hidden;
				margin: 0;
				font-family: 'Arial';
			}
			
			#main {
				overflow: hidden;
			}
			
			#avatar {
				position: fixed;
				width: 500px;
				height: 1000px;
				margin-right: auto;
				margin-left: auto;
				left: 0;
				right: 0;
				bottom: 0px;
				overflow: hidden;
			}

			#avatar img {
				position: absolute;
			}

			#hair, #eyes, #nose, #mouth {
				position: absolute;
			}
			
			#eyes {
				top: 5px;
			}
			
			#nose {
				top: -3px;
			}
			
			#hair {
				width: 540px;
				z-index: 10;
				left: 75px;
				top: -25px;
			}

			#dress {
				position: absolute;
				width: 630px;
				left: -55px;
				top: 365px;
			}
			
			#skin {
				position: absolute;
				width: 500px;
				top: -100px;
				
			}

			#head {
				position: absolute;
				left: -20px;
				z-index: 10;
			}
			
			#head > #background {
				
			}
			
			#answer {
				position: fixed;
				width: 95%;
				height: 200px;
				z-index: 100;
				bottom: 0;
				left: 0;
				right: 0;
				margin-left: auto;
				margin-right: auto;
				display: none;
				background-color: #FFF;
				font-size: 30pt;
				padding: 10px;
				overflow-y: auto;
			}
			
			#ask {
				position: fixed;
				width: 95%;
				height: 200px;
				z-index: 100;
				bottom: 0;
				left: 0;
				right: 0;
				margin-left: auto;
				margin-right: auto;
			}
			
			#ask_input {
				float: left;
				position: relative;
				display: block;
				width: 69%;
				height: 97%;
				font-size: 30pt;
				font-family: 'Arial';
				padding: 10px;
			}
			
			#ask_button {
				float: left;
				display: block;
				width: 25%;
				height: 100%;
				cursor: pointer;
				font-size: 30pt;
			}
			
			#main {
				background: url(background<?php echo rand(1, 3);?>.png) no-repeat;
				background-position: center center;
				background-size: cover;
			}
			
			#log {
				position: absolute;
				z-index: 100;
				top: 0;
				left: 0;
				font-size: 24pt;
				color: #FFF;
				width: 98%;
				height: 500px;
				margin-top: 75px;
				padding: 1%;
				overflow-y: auto;
				overflow-wrap: anywhere;
			}
			
			#created_by {
				position: absolute;
				top: 10px;
				right: 10px;
				color: #FFF;
				font-size: 30pt;
				z-index: 1000;
			}
			
			.log-table {
				display: table;
				width: 100%;
			}

			.log-row {
				display: table-row;
			}

			.log-label, .log-entry {
				display: table-cell;
				padding: 10px;
			}

			.log-label {
				width: 30%; 
				font-weight: bold;
			}

			.log-entry {
				width: 70%; 
			}

			/* Large devices (landscape tablets, laptops, and desktops) */
			@media (min-width: 600px) {
				.log-label {
				width: 20%; 
				}

				.log-entry {
				width: 80%; 
				}
			}

		</style>
		<script>
			function resizeWindow() {
				var height = window.innerHeight;
				$('#main').css('height', height);
			}
		</script>
	</head>
	<body onresize="resizeWindow()">
		<div id="main" style="border: 1px solid; height: 700px; width: 100%;">
			<img src="loading.gif" id="loading" style="position: absolute; margin-left: auto; margin-right: auto; left: 0; right: 0; z-index: 10000; margin-top: 150px; width: 500px; display: none;" />
			<div id="avatar" style="display: none">
				<div id="head">
					<img id="hair" src="hair.svg" />
					<img id="background" src="head.svg" />
					<img id="eyes" src="eyes.svg" />
					<img id="nose" src="nose.svg" />
					<img id="mouth" src="mouth0.svg" />
				</div>
				<div id="body">
					<img id="skin" src="body.svg" />
					<img id="dress" src="blue_dress.svg" />
				</div>
			</div>
			<div id="log">
				
			</div>
			<div id="answer">
				
			</div>
			<div id="ask">
				<textarea id="ask_input" rows="4" cols="50">Hello</textarea>
				<button id="ask_button">ASK</button>
			</div>
			<a href="https://www.lance.name/" id="created_by">Created by Lance</a>
		</div>
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			$(document).ready(function() {
				let eyesStates = ["eyes.svg", "eyes1.svg", "eyes2.svg"];
				let log_data = {};
				
				$("#ask_input").keypress(function(e) {
					if (e.which == 13) {
						e.preventDefault(); // This prevents the default action of the enter key
						$("#ask_button").click(); // This triggers the click event of your ask_button
					}
				});
				
				$("#ask_button").click(function() {
					$('#loading').show();
					$('#answer').show();
					$('#ask').hide();
					let tts = $("#ask_input").val();
					$.post('https://bot.computer/talk.php', {text: tts, log: log_data}, function(response) {
						response = JSON.parse(response);
						log = JSON.parse(response.log);
						$('#answer').html(response.answer);
						
						if (log.visible) {
							let log_html = '<div class="log-table">';
							log.visible.forEach((pair, index) => {
							log_html += '<div class="log-row"><div class="log-label"><b>You</b></div><div class="log-entry">' + pair[0] + '</div></div>';
							log_html += '<div class="log-row"><div class="log-label"><b>Alice</b></div><div class="log-entry">' + pair[1] + '</div></div>';
							});

							log_html += '</div>';
							$('#log').html(log_html);

							log_data = log;
						}

						
						let audioUrl = 'https://tts.computer/speak.php?text=' + encodeURIComponent(response.answer);
						$.ajax({
							url: audioUrl,
							type: 'GET',
							dataType: 'json',
							success: function(response) {
								
								let comment = response.comment;

								// Access the audio_duration and lip_string fields
								let audio_duration = comment.audio_duration;
								let lip_string = comment.lip_string;
								
								let frames = {};
								let frameRate = lip_string.length / audio_duration;	// Adjust this value to match your TTS server's framerate

								for(let i = 0; i < lip_string.length; i++) {
									let frame = lip_string[i];

									// Determine the frame numbers for the start and end of this phoneme
									let startFrame = Math.ceil(frame.start / frameRate);
									let endFrame = Math.ceil(frame.end / frameRate);

									// Assign the shape to each frame in this range
									for(let j = startFrame; j <= endFrame; j++) {
										frames['f' + j] = { phoneme: frame.phoneme, shape: frame.shape, start: frame.start, end: frame.end, duration: frame.duration };
									}
								}
								
								frames = Object.entries(frames).map(([key, value]) => value);

								let mp3Url = 'https://tts.computer/download_mp3.php?hash=' + response.audio;

								playAudio({url: mp3Url, frames: frames});
							}
						});
					});
				});

				function playAudio(audioData) {
					let audio = new Audio(audioData.url);
					let frames = audioData.frames;
					
					$(audio).on('playing', function() {
						$('#loading').hide();
						let intervalId = setInterval(function() {
							if (!audio.paused && !audio.ended) {
								animateMouth(frames, audio, intervalId);
							}
						}, 50);
					});
					
					audio.play();
				}

				function animateMouth(frames, audio, intervalId) {
					let delay = 100; // delay in milliseconds
					let currentTime = (audio.currentTime * 1000) + delay;
					
					let currentPhoneme = frames.find(phoneme => currentTime >= phoneme.start && currentTime <= phoneme.end);

					if (currentPhoneme) {
						$('#mouth').attr('src', 'mouth' + currentPhoneme.shape + '.svg');
					}
					
					$(audio).on('ended paused', function() {
						clearInterval(intervalId);
						resetMouth();
						$('#answer').hide();
						$('#answer').html('');
						$('#ask').show();
					});
				}
				
				function resetMouth() {
					$('#mouth').attr('src', 'mouth0.svg');
				}
				
				function blink() {
					// slightly close the eyes
					$('#eyes').attr('src', eyesStates[1]);

					setTimeout(function() {
						// close the eyes
						$('#eyes').attr('src', eyesStates[2]);

						setTimeout(function() {
							// open the eyes
							$('#eyes').attr('src', eyesStates[0]);
						}, 100);	// eyes remain closed for 200ms

					}, 100);	// eyes remain slightly closed for 200ms
				}

				let minBlinkInterval = 2000;	// 2 seconds
				let maxBlinkInterval = 5000;	// 5 seconds

				setInterval(function() {
					blink();
				}, Math.random() * (maxBlinkInterval - minBlinkInterval) + minBlinkInterval);	// Randomly between 2 to 5 seconds

				$('#avatar').show();
				resizeWindow();
						});
		</script>
	</body>
</html>