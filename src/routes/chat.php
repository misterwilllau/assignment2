<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

$app = new \Slim\App;

// Get All Chats
$app->get('/api/chat', function(Request $request, Response $response) {
	$sql = "SELECT * FROM chat";

	try {

		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$chats = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($chats);

	} catch(PDOException $e) {
		echo '{"error": {"text" : '. $e->getMessage() .'}}';
	}

});

// Get Chat
$app->get('/api/chat/{id}', function(Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$sql = "SELECT * FROM chat WHERE chat_id = $id";

	try {

		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->query($sql);
		$chat = $stmt->fetchAll(PDO::FETCH_OBJ);
		$db = null;
		echo json_encode($chat);
		
	} catch(PDOException $e) {
		echo '{"error": {"text" : '. $e->getMessage() .'}}';
	}

});

// Add Chat
$app->post('/api/chat/add', function(Request $request, Response $response) {

	$chat_content 		= $request->getParam('chat_content');
	$chat_sender_id 	= $request->getParam('chat_sender_id');
	$chat_receiver_id 	= $request->getParam('chat_receiver_id');

	$sql = "INSERT INTO chat (chat_content, chat_sender_id, chat_receiver_id) VALUES 
			(:chat_content, :chat_sender_id, :chat_receiver_id)";

	try {

		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':chat_content', $chat_content);
		$stmt->bindParam(':chat_sender_id', $chat_sender_id);
		$stmt->bindParam(':chat_receiver_id', $chat_receiver_id);

		$stmt->execute();

		echo '{"notice": {"text" : "Chat Added"}';
		
	} catch(PDOException $e) {
		echo '{"error": {"text" : '. $e->getMessage() .'}';
	}

});

// Update Chat
$app->put('/api/chat/update/{id}', function(Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$chat_content 		= $request->getParam('chat_content');
	$chat_sender_id 	= $request->getParam('chat_sender_id');
	$chat_receiver_id 	= $request->getParam('chat_receiver_id');

	$sql = "UPDATE chat SET
			chat_content = :chat_content,
			chat_sender_id = :chat_sender_id,
			chat_receiver_id = :chat_receiver_id
			WHERE chat_id = $id";

	try {

		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);

		$stmt->bindParam(':chat_content', $chat_content);
		$stmt->bindParam(':chat_sender_id', $chat_sender_id);
		$stmt->bindParam(':chat_receiver_id', $chat_receiver_id);

		$stmt->execute();

		echo '{"notice": {"text" : "Chat Updated"}';
		
	} catch(PDOException $e) {
		echo '{"error": {"text" : '. $e->getMessage() .'}';
	}

});

// Delete Chat
$app->delete('/api/chat/delete/{id}', function(Request $request, Response $response) {

	$id = $request->getAttribute('id');

	$sql = "DELETE FROM chat WHERE chat_id = $id";

	try {

		// Get DB Object
		$db = new db();
		// Connect
		$db = $db->connect();

		$stmt = $db->prepare($sql);
		$stmt->execute();
		$db = null;

		echo '{"notice": {"text" : "Chat Deleted"}';

	} catch(PDOException $e) {
		echo '{"error": {"text" : '. $e->getMessage() .'}}';
	}

});