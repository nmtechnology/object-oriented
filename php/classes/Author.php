<?php

namespace pleyba4\ObjectOriented;

require_once ("autoload.php");
require_once (dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;
/**
 *  Author of something.
 *
 * This Author class will hold the data that it has typed into it.
 *
 * @author pleyba4 <pleyba4@gmail.com>
 */

class Author implements \JsonSerializable {
	use ValidateDate;
	use ValidateUuid;
	/**
	 * constructor for Author
	 * @param string|Uuid $newAuthorId id of this Author or null if a new Author
	 * @param string $newAuthorActivationToken activation token for Author
	 * @param string $newAuthorAvatarUrl url for Author avatar
	 * @param string $newAuthorEmail email of Author
	 * @param string $newAuthorHash hash password of Author
	 * @param string $newAuthorUsername username of Author
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of set range
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception is some other exception occurs
	 */
	/**
	 * This is the author's Id.
	 * @var UUid $authorId
	 */
	private $authorId;
	/**
	 * This is the author's activation token for their account.
	 * @var $authorActivationToken
	 */
	private $authorActivationToken;
	/**
	 * This is the author's Avatar URL for their image.
	 * @var $authorAvatarUrl
	 */
	private $authorAvatarUrl;
	/**
	 * This is the author's Email linked to their account.
	 * @var $authorEmail
	 */
	private $authorEmail;
	/**
	 * This is the author's password.
	 * @var $authorHash
	 */
	private $authorHash;
	/**
	 * This is the author's user name linked to their account.
	 * @var $authorUsername
	 */
	private $authorUsername;

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id (or null if new author)
	 **/
	/**
	 * @return UUid
	 */
	public function __construct($newAuthorId, $newAuthorActivationToken, $newAuthorAvatarUrl, $newAuthorEmail, $newAuthorHash, $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUsername($newAuthorUsername);
		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}//end of construct function

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id (or null if new Profile)
	 **/
	public function getAuthorId(): Uuid {
		return $this->authorId;
	}

	/**
	 * mutator method for author Id
	 *
	 * @param Uuid| string $newAuthorId value of new author Id
	 * @throws \RangeException if $newAuthorId is not positive
	 * @throws \TypeError if the author Id is not
	 **/
	public function setAuthorId($newAuthorId): void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
		//convert and store author id
		$this->authorId = $uuid;
	}

	/**
	 * accessor method for author activation token
	 *
	 * @return string value of activation token
	 **/
	public function getAuthorActivationToken() {
		return $this->authorActivationToken;
	}
	/**
	 * mutator method for author activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 **/
	public function setAuthorActivationToken(string $newAuthorActivationToken): void {
		if($newAuthorActivationToken ===  null) {
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false) {
			throw(new\RangeException("activation token not valid"));
		}
		//checking if author token is only 32 characters
		if (strlen($newAuthorActivationToken) !== 32) {
			throw(new\RangeException("author token has to 32"));
		}
		$this->authorActivationToken = $newAuthorActivationToken;
	}
	/**
	 * accessor method for the author avatar url
	 *
	 * @return string value of avatar url
	 **/
	public function getAuthorAvatarUrl() {
		return $this->authorAvatarUrl;
	}
	/**
	 * mutator method for the author avatar url
	 *
	 * @param string $newAuthorAvatarUrl new value of the avatar url
	 * @throws \InvalidArgumentException if $newAuthorAvatarUrl is not a string or insecure
	 * @throws \RangeException if $newAuthorAvatarUrl is not an image
	 * @throws \TypeError if $newAuthorAvatarUrl is not an image
	 **/
	public function setAuthorAvatarUrl(string $newAuthorAvatarUrl): void {
		//verify the avatar url
		$newAuthorAvatarUrl = trim($newAuthorAvatarUrl);
		$newAuthorAvatarUrl = filter_var($newAuthorAvatarUrl, FILTER_SANITIZE_STRING);
		if(empty($newAuthorAvatarUrl) === true) {
			throw (new \InvalidArgumentException("Avatar Url empty or insecure"));
		}
		//verify that the Avatar will fit in the database
		if(strlen($newAuthorAvatarUrl) > 100) {
			throw (new \RangeException("Avatar Url is too long"));
		}
		//store that Avatar Url
		$this->authorAvatarUrl = $newAuthorAvatarUrl;
	} //end of avatar function.

	/**
	 * accessor method for email
	 *
	 * @return string value of email
	 */
	public function getAuthorEmail(): string {
		return $this->authorEmail;
	} // end getAuthorEmail function
	/**
	 * mutator method for email
	 *
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newAuthorEmail is not a valid email or insecure
	 * @throws \RangeException if $newAuthorEmail is > 128 characters
	 * @throws \TypeError if $newAuthorEmail is not a string
	 **/
	public function setAuthorEmail(string $newAuthorEmail): void {
		//verify that the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("author email is empty or insecure"));
		}
		//verify the email will fit in the database
		if(strlen($newAuthorEmail) > 128) {
			throw(new\RangeException("author email is too large"));
		}
		//store the email
		$this->authorEmail = $newAuthorEmail;
	} //end of setAuthorEmail function
	/**
	 * accessor method for authorHash
	 *
	 * @return string value of hash
	 */
	public function getAuthorHash(): string {
		return $this->authorHash;
	} //edn of getAuthorHash function
	/**
	 * mutator method for author hash pass
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if author hash is not a string
	 */
	public function setAuthorHash(string $newAuthorHash): void {
		// enforce that the hash is poorly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("author hash empty or insecure"));
		}
		//enforce the hash is really an Argon hash
		$authorHashInfo = password_get_info($newAuthorHash);
		if($authorHashInfo["algoName"] !== "argon2i") {
			throw(new \InvalidArgumentException("author hash is not a valid hash"));
		}
		//enforce that the hash is exactly 97 characters
		if(strlen($newAuthorHash) > 97) {
			throw(new \RangeException("author hash must be 97 characters to be valid"));
		}
		//store this hash
		$this->authorHash = $newAuthorHash;
	} //end os setAuthorHash function

	/**
	 * accessor method for author username
	 *
	 * @return string value of username
	 */
	public function getAuthorUsername(): string {
		return $this->authorUsername;
	} //end getAuthorUsername function

	/**
	 * mutator method for username
	 *
	 * @param string $newAuthorUsername new value of the username
	 * @throws \InvalidArgumentException if $newAuthorUsername is not a string or insecure
	 * @throws \RangeException if $newAuthorUsername is > 32 characters
	 * @throws \TypeError if $newAuthorUsername is not a string
	 */
	public function setAuthorUsername($newAuthorUsername): void {
		//verify the username is secure
		$newAuthorUsername = trim($newAuthorUsername);
		$newAuthorUsername = filter_var($newAuthorUsername, FILTER_SANITIZE_STRING, FILTER_FLAG_NO_ENCODE_QUOTES);
		if(empty($newAuthorUsername) === true) {
			throw (new \InvalidArgumentException("author username is empty or insecure"));
		}
		//verify the username will fit into the database
		if(strlen($newAuthorUsername) > 32) {
			throw(new \RangeException("author username is too long"));
		}
		//store the username
		$this->authorUsername = $newAuthorUsername;
	} //end of setAuthorUsername
	/**
	 * formats the state variables for JSON serialization
	 *
	 * @return array resulting state variables to serialize
	 **/
	public function jsonSerialize() : array {
		$fields = get_object_vars($this);
		$fields["authorId"] = $this->authorId->toString();
		return($fields);
	}
}