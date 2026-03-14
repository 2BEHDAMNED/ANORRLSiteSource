<?php

	require_once $_SERVER['DOCUMENT_ROOT'].'/core/classes/user.php';
	require_once $_SERVER['DOCUMENT_ROOT'].'/core/utilities/assetutils.php';

	enum BlobValueTypes {
		case STRING;
		case INSTANCE;
		case BOOLEAN;
		case NUMBER;

		public function id() {
			return match($this) {
				BlobValueTypes::STRING => "string",
				BlobValueTypes::INSTANCE => "instance",
				BlobValueTypes::BOOLEAN => "boolean",
				BlobValueTypes::NUMBER => "number"
			};
		}
	}

	class Blob {
		public Place $place;
		public User $user;
		public array $tinys; // TinyBlob
		
	}

	class TinyBlob {
		public string $key;
		public BlobValueTypes $type;
		public string|float|int|bool $value;
	}

	
?>