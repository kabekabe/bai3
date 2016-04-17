<?php

// Połączenie z bazą danych
function getDBConnection() {
	$conn = new mysqli("localhost", "root", "pass", "bai2");
	if ($conn->connect_error) {
		die("Połączenie nieudane: " . $conn->connect_error);
	}
	return $conn;
}

function getAllMessages() {
		$conn = getDBConnection();
		
		$sql = "select message_id, text, modified
				from messages";
		$query = $conn->query($sql);
		
		$conn->close();
		
		if ($query->num_rows > 0) {
	    	return $query;
		} else {
    		return null;
		}
	}
	
function getUsernameForMessage($message_id) {
		$conn = getDBConnection();
		
		$sql = "select u.username
				from users u, messages m, allowed_messages a
				where u.user_id = a.user_id and
					m.message_id = a.message_id and
					m.message_id = $message_id";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();
		
		$conn->close();
		
		return $row["username"];
	}
	
function isAllowedToEdit($message_id, $user_id) {
		$conn = getDBConnection();
		
		$sql = "select user_id
				from allowed_messages
				where message_id = $message_id and
					user_id = $user_id";
		$query = $conn->query($sql);
		
		$conn->close();
		
		if ($query->num_rows > 0) {
	    	return true;
		} else {
    		return false;
		}
	}
	
function getOwner($message_id) {
		$conn = getDBConnection();
		
		$sql = "select owner
				from users u, messages m, allowed_messages a
				where u.user_id = a.user_id and
					m.message_id = a.message_id and
					m.message_id = $message_id";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();
		
		$conn->close();
		
		return $row["owner"];
	}
	
function addMessage($message_text) {
		$conn = getDBConnection();
		
		$user_id = getUserFromSession()["user_id"];
		
		$insert_message = "insert into messages (text, owner) values ('$message_text', $user_id)";
		$conn->query($insert_message);
	
		$user_id = getUserFromSession()["user_id"];
		$message_id = $conn->insert_id;
		$insert_allowed_message = "insert into allowed_messages (user_id, message_id) values ($user_id, $message_id)";
		$conn->query($insert_allowed_message);
		
		$conn->close();
	}
	
function deleteMessage($message_id, $owner) {
		$conn = getDBConnection();
		
		$logged_user_id = getUserFromSession()["user_id"];
		
		if ($logged_user_id == $owner) {		
			$delete_from_allowed_messages = "delete from allowed_messages where message_id=$message_id";
			$conn->query($delete_from_allowed_messages);
			
			$delete_from_messages = "delete from messages where message_id=$message_id";
			$conn->query($delete_from_messages);
		}
		
		$conn->close();
	}
	
function getMessageText($message_id) {
		$conn = getDBConnection();
		
		$sql = "select text 
				from messages
				where message_id = $message_id";
		$query = $conn->query($sql);
		$row = $query->fetch_assoc();
		
		$conn->close();
		
		return $row["text"];
	}
	
function getUsersButNotOwner($owner) {
		$conn = getDBConnection();
		
		$sql = "select user_id, username
				from users
				where user_id <> $owner";
		$query = $conn->query($sql);
		
		$conn->close();
		
		if ($query->num_rows > 0) {
	    	return $query;
		} else {
    		return null;
		}
	}
	
function updateMessage($message_id, $message, $user_id_for_permissions, $owner) {
		$conn = getDBConnection();
		
		$logged_user_id = getUserFromSession()["user_id"];
		
		$sql = "select user_id
				from allowed_messages
				where message_id = $message_id";
		$query = $conn->query($sql);
		
		while($row = $query->fetch_assoc()) {
			$user_with_permissions = $row["user_id"];
			if ($logged_user_id == $user_with_permissions) {
				$update_messages = "update messages set text='$message' where message_id=$message_id";
				$conn->query($update_messages);
			}
		}

		if ($logged_user_id == $user_with_permissions) {
			$update_messages = "update messages set text='$message' where message_id=$message_id";
			$conn->query($update_messages);
		}
				
		if ($logged_user_id == $owner) {
			if ($user_id_for_permissions == 0) {
				$remove_allowed_messages = "delete from allowed_messages where message_id=$message_id and user_id<>$owner";
				$conn->query($remove_allowed_messages);
			} else {
				$add_allowed_messages = "insert into allowed_messages (message_id, user_id) values ($message_id, $user_id_for_permissions)";
				$conn->query($add_allowed_messages);
			}
		}
		
		$conn->close();
	}

?>

