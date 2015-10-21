<?php

if($textMessage) {
	mail($textMessage['address'], 'New Lead', $textMessage['message']);
}