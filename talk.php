<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

function getAnswer($question, $history) {
	// Define the URL
	$url = "http://127.0.0.1:5000/api/v1/chat";

	// Define the data
	$data = array(
		'user_input' => $question,
		'max_new_tokens' => 250,
		'mode' => 'chat',
		'character' => 'Alice',
	);
	
	if (!empty($history)) {
		$data['history'] = $history;
	}

	// Convert the data to JSON
	$jsonData = json_encode($data);

	// Initialize cURL
	$ch = curl_init($url);

	// Set the options
	curl_setopt($ch, CURLOPT_POST, true);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonData);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Execute the cURL and get the response
	$response = curl_exec($ch);

	// Close the cURL
	curl_close($ch);

	// Convert the response to an array
	$responseArray = json_decode($response, true);
	
	return $responseArray;
}

if (isset($_REQUEST['text'])) {
	$text = $_REQUEST['text'];
	$history = isset($_REQUEST['log']) ? $_REQUEST['log']  : NULL;
	
	$result = getAnswer($text, $history);
	
	if (isset($result['results'])) {
		echo json_encode(array('log' => json_encode($result['results'][0]['history']), 'answer' => $result['results'][0]['history']['visible'][count($result['results'][0]['history']['visible']) - 1][1]));
	} else {
		echo json_encode(array('log' => json_encode(array()), 'answer' => 'Sorry, I have no answer.'));
	}
} 
?>